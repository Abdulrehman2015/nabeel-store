using System;
using System.Diagnostics;
using System.Drawing;
using System.IO;
using System.Windows.Forms;

namespace NabeelStore.PosTerminal
{
    public class Program
    {
        [STAThread]
        public static void Main(string[] args)
        {
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);

            // If launched with --direct or --live, launch immediately without showing menu
            if (args.Length > 0 && (args[0] == "--direct" || args[0] == "--live"))
            {
                PosLauncher.Launch("https://nabeel.site.je/admin/pos", true, false);
                return;
            }

            if (args.Length > 0 && args[0] == "--local")
            {
                PosLauncher.Launch("http://127.0.0.1:8000/admin/pos", true, false);
                return;
            }

            Application.Run(new LauncherForm());
        }
    }

    public class PosLauncher
    {
        public static string GetBrowserPath()
        {
            string[] candidatePaths = new string[]
            {
                Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.ProgramFilesX86), @"Microsoft\Edge\Application\msedge.exe"),
                Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.ProgramFiles), @"Microsoft\Edge\Application\msedge.exe"),
                Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.LocalApplicationData), @"Microsoft\Edge\Application\msedge.exe"),
                Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.ProgramFiles), @"Google\Chrome\Application\chrome.exe"),
                Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.ProgramFilesX86), @"Google\Chrome\Application\chrome.exe")
            };

            foreach (string path in candidatePaths)
            {
                if (File.Exists(path))
                    return path;
            }

            return null;
        }

        public static bool Launch(string targetUrl, bool silentPrint, bool fullKiosk)
        {
            string browser = GetBrowserPath();
            if (string.IsNullOrEmpty(browser))
            {
                MessageBox.Show("Could not locate Microsoft Edge or Google Chrome to run the POS Terminal.\nPlease install Edge or Chrome.", "Browser Engine Missing", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return false;
            }

            string userDataDir = Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.LocalApplicationData), "NabeelStorePOS_Profile");
            Directory.CreateDirectory(userDataDir);

            // Construct Chromium isolated native App Mode arguments
            string arguments = string.Format(
                "--app=\"{0}\" --user-data-dir=\"{1}\" --window-size=1366,768 --start-maximized --disable-background-timer-throttling --disable-features=TranslateUI --disable-session-crashed-bubble --disable-infobars",
                targetUrl,
                userDataDir
            );

            if (silentPrint)
            {
                // Enables instant silent thermal receipt printing without print preview dialog
                arguments += " --kiosk-printing";
            }

            if (fullKiosk)
            {
                // Locked kiosk counter screen
                arguments += " --kiosk";
            }

            ProcessStartInfo psi = new ProcessStartInfo
            {
                FileName = browser,
                Arguments = arguments,
                UseShellExecute = false
            };

            try
            {
                Process.Start(psi);
                return true;
            }
            catch (Exception ex)
            {
                MessageBox.Show("Failed to launch POS Terminal:\n" + ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return false;
            }
        }
    }

    public class LauncherForm : Form
    {
        private CheckBox chkSilentPrint;
        private CheckBox chkKioskMode;

        public LauncherForm()
        {
            this.Text = "POS Counter Terminal Launcher";
            this.Size = new Size(520, 480);
            this.StartPosition = FormStartPosition.CenterScreen;
            this.FormBorderStyle = FormBorderStyle.FixedDialog;
            this.MaximizeBox = false;
            this.BackColor = Color.FromArgb(248, 250, 252); // #f8fafc

            // Try load icon
            string iconPath = Path.Combine(AppDomain.CurrentDomain.BaseDirectory, @"public\images\app.ico");
            if (File.Exists(iconPath))
            {
                try { this.Icon = new Icon(iconPath); } catch {}
            }

            InitUi();
        }

        private void InitUi()
        {
            // Top Brand Banner
            Panel topPanel = new Panel
            {
                Dock = DockStyle.Top,
                Height = 110,
                BackColor = Color.FromArgb(15, 23, 42) // Slate 900
            };

            Label lblTitle = new Label
            {
                Text = "Point of Sale (POS) Terminal",
                Font = new Font("Segoe UI", 15, FontStyle.Bold),
                ForeColor = Color.White,
                Location = new Point(24, 22),
                AutoSize = true
            };

            Label lblSubtitle = new Label
            {
                Text = "Windows Desktop Cash Register & Thermal Receipt Terminal",
                Font = new Font("Segoe UI", 9, FontStyle.Regular),
                ForeColor = Color.FromArgb(148, 163, 184), // Slate 400
                Location = new Point(25, 58),
                AutoSize = true
            };

            topPanel.Controls.Add(lblTitle);
            topPanel.Controls.Add(lblSubtitle);
            this.Controls.Add(topPanel);

            // Main Action Area
            int y = 130;

            // Primary Button: Cloud Live POS
            Button btnLive = new Button
            {
                Text = "▶   Launch Live Cloud POS (nabeel.site.je)",
                Font = new Font("Segoe UI", 11, FontStyle.Bold),
                ForeColor = Color.White,
                BackColor = Color.FromArgb(2, 132, 199), // Sky 600
                FlatStyle = FlatStyle.Flat,
                Location = new Point(30, y),
                Size = new Size(445, 55),
                Cursor = Cursors.Hand
            };
            btnLive.FlatAppearance.BorderSize = 0;
            btnLive.Click += (s, e) =>
            {
                PosLauncher.Launch("https://nabeel.site.je/admin/pos", chkSilentPrint.Checked, chkKioskMode.Checked);
                this.Close();
            };
            this.Controls.Add(btnLive);

            y += 68;

            // Secondary Button: Local Server POS
            Button btnLocal = new Button
            {
                Text = "💻   Launch Localhost POS (127.0.0.1:8000)",
                Font = new Font("Segoe UI", 10, FontStyle.Bold),
                ForeColor = Color.FromArgb(30, 41, 59),
                BackColor = Color.White,
                FlatStyle = FlatStyle.Flat,
                Location = new Point(30, y),
                Size = new Size(445, 48),
                Cursor = Cursors.Hand
            };
            btnLocal.FlatAppearance.BorderColor = Color.FromArgb(203, 213, 225);
            btnLocal.Click += (s, e) =>
            {
                PosLauncher.Launch("http://127.0.0.1:8000/admin/pos", chkSilentPrint.Checked, chkKioskMode.Checked);
                this.Close();
            };
            this.Controls.Add(btnLocal);

            y += 65;

            // Options Group Box
            GroupBox grpOptions = new GroupBox
            {
                Text = "Cash Register Printer & Display Settings",
                Font = new Font("Segoe UI", 9, FontStyle.Bold),
                ForeColor = Color.FromArgb(71, 85, 105),
                Location = new Point(30, y),
                Size = new Size(445, 105),
                BackColor = Color.White
            };

            chkSilentPrint = new CheckBox
            {
                Text = "Instant Silent Printing (Skip Windows print dialog for thermal slips)",
                Font = new Font("Segoe UI", 9, FontStyle.Regular),
                ForeColor = Color.FromArgb(15, 23, 42),
                Checked = true,
                Location = new Point(15, 26),
                AutoSize = true
            };

            chkKioskMode = new CheckBox
            {
                Text = "Lock in Fullscreen Kiosk Mode (F11 to toggle)",
                Font = new Font("Segoe UI", 9, FontStyle.Regular),
                ForeColor = Color.FromArgb(15, 23, 42),
                Checked = false,
                Location = new Point(15, 60),
                AutoSize = true
            };

            grpOptions.Controls.Add(chkSilentPrint);
            grpOptions.Controls.Add(chkKioskMode);
            this.Controls.Add(grpOptions);

            y += 120;

            // Footer note
            Label lblFooter = new Label
            {
                Text = "Hardware barcode scanners & 58mm/80mm thermal printers supported.",
                Font = new Font("Segoe UI", 8, FontStyle.Regular),
                ForeColor = Color.FromArgb(100, 116, 139),
                Location = new Point(30, y),
                AutoSize = true
            };
            this.Controls.Add(lblFooter);
        }
    }
}

