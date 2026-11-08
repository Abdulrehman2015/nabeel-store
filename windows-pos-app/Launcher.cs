using System;
using System.Diagnostics;
using System.Drawing;
using System.IO;
using System.Runtime.InteropServices;
using System.Threading;
using System.Windows.Forms;

namespace NabeelStore.POS
{
    public class Program
    {
        private const string DEFAULT_POS_URL = "https://nabeel.site.je/admin/pos";
        private const string APP_NAME = "N&I Mini Mart POS";
        private const string APP_ID = "NabeelStore.POS.Workstation";

        [DllImport("user32.dll")]
        private static extern bool SetForegroundWindow(IntPtr hWnd);

        [DllImport("user32.dll")]
        private static extern bool ShowWindow(IntPtr hWnd, int nCmdShow);

        [DllImport("shell32.dll", SetLastError = true)]
        private static extern void SetCurrentProcessExplicitAppUserModelID([MarshalAs(UnmanagedType.LPWStr)] string AppID);

        private static NotifyIcon trayIcon;
        private static Process browserProcess;
        private static string targetUrl = DEFAULT_POS_URL;
        private static bool isKioskMode = false;

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
            }

            // Single Instance Mutex
            bool createdNew;
            using (Mutex mutex = new Mutex(true, "NabeelStore_POS_SingleInstance_Mutex", out createdNew))
            {
                if (!createdNew)
                {
                    // Already running: bring to front
                    LaunchOrFocusBrowser();
                    return;
                }

                InitializeTray();
                LaunchOrFocusBrowser();

                Application.Run();
            }
        }

        private static void InitializeTray()
        {
            trayIcon = new NotifyIcon();
            trayIcon.Text = APP_NAME;

            // Load Icon
            try
            {
                string exeDir = AppDomain.CurrentDomain.BaseDirectory;
                string iconPath = Path.Combine(exeDir, "app.ico");
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
            menu.MenuItems.Add(new MenuItem("Reload POS (F5)", (s, e) => { CloseBrowser(); LaunchOrFocusBrowser(); }));
            menu.MenuItems.Add("-");
            menu.MenuItems.Add(new MenuItem("Open Storefront", (s, e) => Process.Start("https://nabeel.site.je")));
            menu.MenuItems.Add(new MenuItem("Store Expenses", (s, e) => Process.Start("https://nabeel.site.je/admin/expenses")));
            menu.MenuItems.Add("-");
            menu.MenuItems.Add(new MenuItem("Exit POS App", (s, e) => {
                CloseBrowser();
                trayIcon.Visible = false;
                Application.Exit();
            }));

            trayIcon.ContextMenu = menu;
            trayIcon.DoubleClick += (s, e) => LaunchOrFocusBrowser();
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
                // Fallback to default system browser
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
                if (File.Exists(path))
                {
                    return path;
                }
            }

            return "msedge.exe";
        }
    }
}
