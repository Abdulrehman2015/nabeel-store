using System;
using System.Diagnostics;
using System.Drawing;
using System.IO;
using System.Reflection;
using System.Runtime.InteropServices;
using System.Windows.Forms;
using Microsoft.Win32;

namespace NabeelStore.POS.Setup
{
    public class SetupForm : Form
    {
        private ProgressBar progressBar;
        private Label statusLabel;
        private Button installButton;
        private CheckBox chkDesktop;
        private CheckBox chkStartMenu;
        private CheckBox chkLaunchNow;
        private Panel contentPanel;
        private string installDir;

        public SetupForm()
        {
            this.Text = "N&I Mini Mart POS - Setup Wizard";
            this.Size = new Size(540, 420);
            this.FormBorderStyle = FormBorderStyle.FixedDialog;
            this.MaximizeBox = false;
            this.StartPosition = FormStartPosition.CenterScreen;
            this.BackColor = Color.FromArgb(248, 250, 252);
            this.Font = new Font("Segoe UI", 9F, FontStyle.Regular, GraphicsUnit.Point);

            installDir = Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.LocalApplicationData), "Programs", "N&I Mini Mart POS");

            // Try load icon
            try
            {
                using (Stream s = Assembly.GetExecutingAssembly().GetManifestResourceStream("APP_ICO"))
                {
                    if (s != null) this.Icon = new Icon(s);
                }
            }
            catch { }

            BuildUI();
        }

        private void BuildUI()
        {
            // Header Panel
            Panel header = new Panel();
            header.Dock = DockStyle.Top;
            header.Height = 85;
            header.BackColor = Color.FromArgb(15, 23, 42); // slate-900

            Label title = new Label();
            title.Text = "N&I Mini Mart POS";
            title.ForeColor = Color.White;
            title.Font = new Font("Segoe UI", 16F, FontStyle.Bold);
            title.Location = new Point(24, 16);
            title.AutoSize = true;
            header.Controls.Add(title);

            Label sub = new Label();
            sub.Text = "Retail Cashier Workstation & Point of Sale System";
            sub.ForeColor = Color.FromArgb(148, 163, 184); // slate-400
            sub.Font = new Font("Segoe UI", 9.5F);
            sub.Location = new Point(26, 48);
            sub.AutoSize = true;
            header.Controls.Add(sub);

            this.Controls.Add(header);

            // Content Panel
            contentPanel = new Panel();
            contentPanel.Dock = DockStyle.Fill;
            contentPanel.Padding = new Padding(24);
            contentPanel.Location = new Point(0, 85);

            Label lblIntro = new Label();
            lblIntro.Text = "This wizard will install the official N&I Mini Mart POS desktop application on your Windows PC. It provides a fast, full-screen, dedicated cashier register with instant barcode scanning and thermal receipt printing.";
            lblIntro.Location = new Point(24, 105);
            lblIntro.Size = new Size(475, 55);
            lblIntro.ForeColor = Color.FromArgb(51, 65, 85);
            this.Controls.Add(lblIntro);

            // Install Path box
            GroupBox grpPath = new GroupBox();
            grpPath.Text = "Installation Folder";
            grpPath.Location = new Point(24, 165);
            grpPath.Size = new Size(475, 60);
            grpPath.ForeColor = Color.FromArgb(71, 85, 105);

            TextBox txtPath = new TextBox();
            txtPath.Text = installDir;
            txtPath.ReadOnly = true;
            txtPath.BackColor = Color.White;
            txtPath.Location = new Point(16, 24);
            txtPath.Size = new Size(440, 24);
            grpPath.Controls.Add(txtPath);
            this.Controls.Add(grpPath);

            // Shortcuts Options
            chkDesktop = new CheckBox();
            chkDesktop.Text = "Create Desktop Shortcut (N&I Mini Mart POS)";
            chkDesktop.Checked = true;
            chkDesktop.Location = new Point(26, 235);
            chkDesktop.AutoSize = true;
            chkDesktop.ForeColor = Color.FromArgb(30, 41, 59);
            this.Controls.Add(chkDesktop);

            chkStartMenu = new CheckBox();
            chkStartMenu.Text = "Create Start Menu Folder & Shortcut";
            chkStartMenu.Checked = true;
            chkStartMenu.Location = new Point(26, 260);
            chkStartMenu.AutoSize = true;
            chkStartMenu.ForeColor = Color.FromArgb(30, 41, 59);
            this.Controls.Add(chkStartMenu);

            chkLaunchNow = new CheckBox();
            chkLaunchNow.Text = "Launch POS Register immediately after setup";
            chkLaunchNow.Checked = true;
            chkLaunchNow.Location = new Point(26, 285);
            chkLaunchNow.AutoSize = true;
            chkLaunchNow.ForeColor = Color.FromArgb(30, 41, 59);
            this.Controls.Add(chkLaunchNow);

            // Status Label & Progress Bar (Hidden initially)
            progressBar = new ProgressBar();
            progressBar.Location = new Point(26, 315);
            progressBar.Size = new Size(470, 16);
            progressBar.Visible = false;
            this.Controls.Add(progressBar);

            statusLabel = new Label();
            statusLabel.Text = "Ready to install.";
            statusLabel.Location = new Point(26, 335);
            statusLabel.Size = new Size(470, 20);
            statusLabel.ForeColor = Color.FromArgb(100, 116, 139);
            statusLabel.Visible = false;
            this.Controls.Add(statusLabel);

            // Bottom Buttons
            installButton = new Button();
            installButton.Text = "Install POS Now";
            installButton.BackColor = Color.FromArgb(14, 165, 233); // sky-500
            installButton.ForeColor = Color.White;
            installButton.FlatStyle = FlatStyle.Flat;
            installButton.FlatAppearance.BorderSize = 0;
            installButton.Font = new Font("Segoe UI", 10F, FontStyle.Bold);
            installButton.Location = new Point(345, 335);
            installButton.Size = new Size(150, 36);
            installButton.Cursor = Cursors.Hand;
            installButton.Click += (s, e) => PerformInstall();
            this.Controls.Add(installButton);

            Button cancelButton = new Button();
            cancelButton.Text = "Cancel";
            cancelButton.BackColor = Color.FromArgb(226, 232, 240);
            cancelButton.FlatStyle = FlatStyle.Flat;
            cancelButton.FlatAppearance.BorderSize = 0;
            cancelButton.Location = new Point(255, 335);
            cancelButton.Size = new Size(80, 36);
            cancelButton.Cursor = Cursors.Hand;
            cancelButton.Click += (s, e) => this.Close();
            this.Controls.Add(cancelButton);
        }

        private void PerformInstall()
        {
            installButton.Enabled = false;
            progressBar.Visible = true;
            progressBar.Value = 15;
            statusLabel.Visible = true;
            statusLabel.Text = "Creating installation directory...";

            try
            {
                if (!Directory.Exists(installDir))
                {
                    Directory.CreateDirectory(installDir);
                }

                progressBar.Value = 35;
                statusLabel.Text = "Extracting application components...";

                // Extract files from embedded resources
                string exePath = Path.Combine(installDir, "N&I Mini Mart POS.exe");
                string uninstPath = Path.Combine(installDir, "Uninstall.exe");
                string iconPath = Path.Combine(installDir, "app.ico");

                ExtractResource("POS_EXE", exePath);
                ExtractResource("UNINSTALL_EXE", uninstPath);
                ExtractResource("APP_ICO", iconPath);

                progressBar.Value = 65;
                statusLabel.Text = "Creating Windows shortcuts...";

                // Create Desktop Shortcut
                if (chkDesktop.Checked)
                {
                    string desktopPath = Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.DesktopDirectory), "N&I Mini Mart POS.lnk");
                    CreateShortcut(desktopPath, exePath, iconPath, "N&I Mini Mart POS Cashier Register");
                }

                // Create Start Menu Shortcut
                if (chkStartMenu.Checked)
                {
                    string startMenuFolder = Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.Programs), "N&I Mini Mart");
                    if (!Directory.Exists(startMenuFolder)) Directory.CreateDirectory(startMenuFolder);

                    string startShortcut = Path.Combine(startMenuFolder, "N&I Mini Mart POS.lnk");
                    CreateShortcut(startShortcut, exePath, iconPath, "N&I Mini Mart POS Cashier Register");

                    string uninstShortcut = Path.Combine(startMenuFolder, "Uninstall POS.lnk");
                    CreateShortcut(uninstShortcut, uninstPath, iconPath, "Uninstall N&I Mini Mart POS");
                }

                progressBar.Value = 85;
                statusLabel.Text = "Registering with Windows Apps...";

                // Register in Windows Add/Remove Programs
                RegisterUninstaller(installDir, uninstPath, iconPath);

                progressBar.Value = 100;
                statusLabel.Text = "Installation completed successfully! 🎉";
                statusLabel.ForeColor = Color.FromArgb(22, 101, 52); // green-800

                MessageBox.Show(
                    "N&I Mini Mart POS has been installed successfully!\n\nA shortcut has been created on your Desktop.",
                    "Setup Complete",
                    MessageBoxButtons.OK,
                    MessageBoxIcon.Information
                );

                if (chkLaunchNow.Checked && File.Exists(exePath))
                {
                    Process.Start(exePath);
                }

                this.Close();
            }
            catch (Exception ex)
            {
                MessageBox.Show("Installation error: " + ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                installButton.Enabled = true;
            }
        }

        private void ExtractResource(string resName, string destPath)
        {
            using (Stream s = Assembly.GetExecutingAssembly().GetManifestResourceStream(resName))
            {
                if (s == null) throw new Exception("Resource not found: " + resName);
                using (FileStream fs = new FileStream(destPath, FileMode.Create, FileAccess.Write))
                {
                    byte[] buffer = new byte[8192];
                    int bytesRead;
                    while ((bytesRead = s.Read(buffer, 0, buffer.Length)) > 0)
                    {
                        fs.Write(buffer, 0, bytesRead);
                    }
                }
            }
        }

        private void CreateShortcut(string shortcutPath, string targetPath, string iconPath, string description)
        {
            try
            {
                Type shellType = Type.GetTypeFromProgID("WScript.Shell");
                dynamic shell = Activator.CreateInstance(shellType);
                dynamic shortcut = shell.CreateShortcut(shortcutPath);
                shortcut.TargetPath = targetPath;
                shortcut.WorkingDirectory = Path.GetDirectoryName(targetPath);
                shortcut.WindowStyle = 1;
                shortcut.Description = description;
                if (!string.IsNullOrEmpty(iconPath) && File.Exists(iconPath))
                {
                    shortcut.IconLocation = iconPath + ",0";
                }
                shortcut.Save();
            }
            catch { }
        }

        private void RegisterUninstaller(string appDir, string uninstPath, string iconPath)
        {
            try
            {
                string keyPath = @"Software\Microsoft\Windows\CurrentVersion\Uninstall\N&IMiniMartPOS";
                using (RegistryKey key = Registry.CurrentUser.CreateSubKey(keyPath))
                {
                    if (key != null)
                    {
                        key.SetValue("DisplayName", "N&I Mini Mart POS");
                        key.SetValue("DisplayVersion", "1.0.0");
                        key.SetValue("Publisher", "N&I Mini Mart");
                        key.SetValue("DisplayIcon", iconPath);
                        key.SetValue("InstallLocation", appDir);
                        key.SetValue("UninstallString", "\"" + uninstPath + "\"");
                        key.SetValue("NoModify", 1, RegistryValueKind.DWord);
                        key.SetValue("NoRepair", 1, RegistryValueKind.DWord);
                        key.SetValue("EstimatedSize", 2048, RegistryValueKind.DWord); // 2 MB
                    }
                }
            }
            catch { }
        }

        [STAThread]
        public static void Main()
        {
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);
            Application.Run(new SetupForm());
        }
    }
}

