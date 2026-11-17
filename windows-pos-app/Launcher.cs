using System;
using System.Diagnostics;
using System.Drawing;
using System.IO;
using System.Net;
using System.Net.Sockets;
using System.Runtime.InteropServices;
using System.Threading;
using System.Windows.Forms;

namespace NabeelStore.POS
{
    public class Program
    {
        private const string LOCAL_POS_URL = "http://127.0.0.1:8765/admin/pos";
        private const string REMOTE_POS_URL = "https://nabeel.site.je/admin/pos";
        private const string APP_NAME = "N&I Mini Mart POS";
        private const string APP_ID = "NabeelStore.POS.Workstation";
        private const int LOCAL_PORT = 8765;

        [DllImport("user32.dll")]
        private static extern bool SetForegroundWindow(IntPtr hWnd);

        [DllImport("user32.dll")]
        private static extern bool ShowWindow(IntPtr hWnd, int nCmdShow);

        [DllImport("shell32.dll", SetLastError = true)]
        private static extern void SetCurrentProcessExplicitAppUserModelID([MarshalAs(UnmanagedType.LPWStr)] string AppID);

        private static NotifyIcon trayIcon;
        private static Process browserProcess;
        private static Process localPhpProcess;
        private static string targetUrl = LOCAL_POS_URL;
        private static bool isKioskMode = false;
        private static string projectRootPath = "";

        [STAThread]
        public static void Main(string[] args)
        {
            try
            {
                SetCurrentProcessExplicitAppUserModelID(APP_ID);
            }
            catch { }

            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);

            // Determine workspace and local app availability
            DetectProjectRoot();

            // Parse command line arguments
            for (int i = 0; i < args.Length; i++)
            {
                string arg = args[i].ToLowerInvariant();
                if (arg == "--kiosk")
                {
                    isKioskMode = true;
                }
                else if (arg.StartsWith("--url="))
                {
                    targetUrl = args[i].Substring(6);
                }
                else if (arg == "--url" && i + 1 < args.Length)
                {
                    targetUrl = args[++i];
                }
                else if (arg == "--remote")
                {
                    targetUrl = REMOTE_POS_URL;
                }
            }

            // If local offline store files exist, launch embedded background PHP server
            if (!string.IsNullOrEmpty(projectRootPath) && targetUrl == LOCAL_POS_URL)
            {
                EnsureLocalServerRunning();
            }
            else if (string.IsNullOrEmpty(projectRootPath))
            {
                // Fallback to live remote URL if no local files found
                targetUrl = REMOTE_POS_URL;
            }

            // Single Instance Mutex
            bool createdNew;
            using (Mutex mutex = new Mutex(true, "NabeelStore_POS_SingleInstance_Mutex", out createdNew))
            {
                if (!createdNew)
                {
                    LaunchOrFocusBrowser();
                    return;
                }

                InitializeTray();
                LaunchOrFocusBrowser();

                Application.ApplicationExit += (s, e) => ShutdownAll();
                AppDomain.CurrentDomain.ProcessExit += (s, e) => ShutdownAll();

                Application.Run();
            }
        }

        private static void DetectProjectRoot()
        {
            string baseDir = AppDomain.CurrentDomain.BaseDirectory;

            // 1. Direct subfolder
            if (File.Exists(Path.Combine(baseDir, "artisan")))
            {
                projectRootPath = baseDir;
                return;
            }

            // 2. Parent directory (e.g. running from windows-pos-app folder)
            DirectoryInfo pInfo = Directory.GetParent(baseDir);
            string parentDir = pInfo != null ? pInfo.FullName : "";
            if (!string.IsNullOrEmpty(parentDir) && File.Exists(Path.Combine(parentDir, "artisan")))
            {
                projectRootPath = parentDir;
                return;
            }

            // 3. Subfolder 'app' or 'store'
            string appSub = Path.Combine(baseDir, "app");
            if (File.Exists(Path.Combine(appSub, "artisan")))
            {
                projectRootPath = appSub;
                return;
            }
        }

        private static void EnsureLocalServerRunning()
        {
            if (IsPortInUse(LOCAL_PORT))
            {
                return; // Server is already listening
            }

            string phpExe = FindPhpExecutable();
            if (string.IsNullOrEmpty(phpExe))
            {
                // PHP not found on machine, fallback to remote live URL
                targetUrl = REMOTE_POS_URL;
                return;
            }

            try
            {
                ProcessStartInfo psi = new ProcessStartInfo();
                psi.FileName = phpExe;
                psi.Arguments = string.Format("-S 127.0.0.1:{0} -t \"{1}\"", LOCAL_PORT, Path.Combine(projectRootPath, "public"));
                psi.WorkingDirectory = projectRootPath;
                psi.CreateNoWindow = true;
                psi.UseShellExecute = false;
                psi.WindowStyle = ProcessWindowStyle.Hidden;

                localPhpProcess = Process.Start(psi);

                // Give server up to 2 seconds to bind
                for (int i = 0; i < 20; i++)
                {
                    if (IsPortInUse(LOCAL_PORT)) break;
                    Thread.Sleep(100);
                }
            }
            catch
            {
                targetUrl = REMOTE_POS_URL;
            }
        }

        private static bool IsPortInUse(int port)
        {
            try
            {
                using (TcpClient client = new TcpClient())
                {
                    IAsyncResult result = client.BeginConnect("127.0.0.1", port, null, null);
                    bool success = result.AsyncWaitHandle.WaitOne(200);
                    if (success && client.Connected)
                    {
                        client.EndConnect(result);
                        return true;
                    }
                }
            }
            catch { }
            return false;
        }

        private static string FindPhpExecutable()
        {
            string baseDir = AppDomain.CurrentDomain.BaseDirectory;

            string[] candidates = new string[]
            {
                Path.Combine(baseDir, @"php\php.exe"),
                Path.Combine(baseDir, @"bin\php.exe"),
                @"C:\laragon\bin\php\php-8.2.30-Win32-vs16-x64\php.exe",
                @"C:\laragon\bin\php\php-8.3.0-Win32-vs16-x64\php.exe",
                @"C:\laragon\bin\php\php-8.4.0-Win32-vs16-x64\php.exe",
                @"C:\xampp\php\php.exe",
                "php.exe"
            };

            foreach (string p in candidates)
            {
                if (File.Exists(p)) return p;
            }

            return "php.exe";
        }

        private static void InitializeTray()
        {
            trayIcon = new NotifyIcon();
            trayIcon.Text = APP_NAME + " (Offline-First)";

            try
            {
                string iconPath = Path.Combine(AppDomain.CurrentDomain.BaseDirectory, "app.ico");
                if (File.Exists(iconPath))
                {
                    trayIcon.Icon = new Icon(iconPath);
                }
                else
                {
                    trayIcon.Icon = SystemIcons.Application;
                }
            }
            catch
            {
                trayIcon.Icon = SystemIcons.Application;
            }

            trayIcon.Visible = true;

            ContextMenu menu = new ContextMenu();
            menu.MenuItems.Add(new MenuItem("Open POS Register", (s, e) => LaunchOrFocusBrowser()));
            menu.MenuItems.Add(new MenuItem("Open Admin Dashboard", (s, e) => OpenUrl(targetUrl.Contains("127.0.0.1") ? "http://127.0.0.1:8765/admin" : "https://nabeel.site.je/admin")));
            menu.MenuItems.Add(new MenuItem("Database Sync Center", (s, e) => OpenUrl(targetUrl.Contains("127.0.0.1") ? "http://127.0.0.1:8765/admin/sync" : "https://nabeel.site.je/admin/sync")));
            menu.MenuItems.Add("-");
            menu.MenuItems.Add(new MenuItem("Sync with Live Cloud Now", (s, e) => TriggerBackgroundSync()));
            menu.MenuItems.Add("Reload POS (F5)", (s, e) => { CloseBrowser(); LaunchOrFocusBrowser(); });
            menu.MenuItems.Add("-");
            menu.MenuItems.Add(new MenuItem("Open Live Online Website", (s, e) => Process.Start("https://nabeel.site.je")));
            menu.MenuItems.Add("-");
            menu.MenuItems.Add(new MenuItem("Exit POS App", (s, e) => {
                ShutdownAll();
                Application.Exit();
            }));

            trayIcon.ContextMenu = menu;
            trayIcon.DoubleClick += (s, e) => LaunchOrFocusBrowser();
        }

        private static void TriggerBackgroundSync()
        {
            new Thread(() =>
            {
                try
                {
                    string syncEndpoint = targetUrl.Contains("127.0.0.1") 
                        ? "http://127.0.0.1:8765/admin/sync/trigger" 
                        : "https://nabeel.site.je/admin/sync/trigger";

                    HttpWebRequest req = (HttpWebRequest)WebRequest.Create(syncEndpoint);
                    req.Method = "POST";
                    req.Timeout = 8000;
                    req.ContentType = "application/json";
                    using (var res = (HttpWebResponse)req.GetResponse())
                    {
                        trayIcon.ShowBalloonTip(3000, "Sync Complete", "Offline sales and products synchronized with cloud database.", ToolTipIcon.Info);
                    }
                }
                catch (Exception ex)
                {
                    trayIcon.ShowBalloonTip(3000, "Cloud Sync Notice", "Saved locally. Server will sync when internet restores: " + ex.Message, ToolTipIcon.Warning);
                }
            }).Start();
        }

        private static void OpenUrl(string url)
        {
            try
            {
                targetUrl = url;
                LaunchOrFocusBrowser();
            }
            catch { }
        }

        private static void CloseBrowser()
        {
            try
            {
                if (browserProcess != null && !browserProcess.HasExited)
                {
                    browserProcess.Kill();
                }
            }
            catch { }
        }

        private static void ShutdownAll()
        {
            try
            {
                if (trayIcon != null)
                {
                    trayIcon.Visible = false;
                    trayIcon.Dispose();
                }
            }
            catch { }

            CloseBrowser();

            try
            {
                if (localPhpProcess != null && !localPhpProcess.HasExited)
                {
                    localPhpProcess.Kill();
                }
            }
            catch { }
        }

        private static void LaunchOrFocusBrowser()
        {
            try
            {
                if (browserProcess != null && !browserProcess.HasExited)
                {
                    IntPtr handle = browserProcess.MainWindowHandle;
                    if (handle != IntPtr.Zero)
                    {
                        ShowWindow(handle, 9); // SW_RESTORE
                        SetForegroundWindow(handle);
                        return;
                    }
                }

                string browserPath = FindBestBrowser();
                string profileDir = Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.LocalApplicationData), "N&IMiniMartPOS", "BrowserProfile");
                if (!Directory.Exists(profileDir))
                {
                    Directory.CreateDirectory(profileDir);
                }

                string arguments = string.Format(
                    "--app=\"{0}\" --user-data-dir=\"{1}\" --start-maximized --disable-features=Translate,InterestFeedContentSuggestions --enable-features=OverlayScrollbar --no-first-run --no-default-browser-check",
                    targetUrl,
                    profileDir
                );

                if (isKioskMode)
                {
                    arguments += " --kiosk --kiosk-printing";
                }

                ProcessStartInfo psi = new ProcessStartInfo();
                psi.FileName = browserPath;
                psi.Arguments = arguments;
                psi.UseShellExecute = false;

                browserProcess = Process.Start(psi);
            }
            catch (Exception ex)
            {
                try
                {
                    Process.Start(targetUrl);
                }
                catch
                {
                    MessageBox.Show("Unable to launch POS: " + ex.Message, APP_NAME, MessageBoxButtons.OK, MessageBoxIcon.Error);
                }
            }
        }

        private static string FindBestBrowser()
        {
            string[] possiblePaths = new string[]
            {
                Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.ProgramFilesX86), @"Microsoft\Edge\Application\msedge.exe"),
                Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.ProgramFiles), @"Microsoft\Edge\Application\msedge.exe"),
                Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.LocalApplicationData), @"Microsoft\Edge\Application\msedge.exe"),
                Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.ProgramFiles), @"Google\Chrome\Application\chrome.exe"),
                Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.ProgramFilesX86), @"Google\Chrome\Application\chrome.exe"),
                Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.LocalApplicationData), @"Google\Chrome\Application\chrome.exe"),
                "msedge.exe",
                "chrome.exe"
            };

            foreach (string path in possiblePaths)
            {
                if (File.Exists(path)) return path;
            }

            return "msedge.exe";
        }
    }
}
