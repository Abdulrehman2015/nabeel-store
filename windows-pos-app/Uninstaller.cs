using System;
using System.Diagnostics;
using System.IO;
using System.Windows.Forms;
using Microsoft.Win32;

namespace NabeelStore.POS
{
    public class Uninstaller
    {
        [STAThread]
        public static void Main()
        {
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);

            DialogResult confirm = MessageBox.Show(
                "Are you sure you want to completely uninstall N&I Mini Mart POS?",
                "Uninstall N&I Mini Mart POS",
                MessageBoxButtons.YesNo,
                MessageBoxIcon.Question
            );

            if (confirm != DialogResult.Yes) return;

            try
            {
                // Kill running processes
                Process[] procs = Process.GetProcessesByName("N&I Mini Mart POS");
                foreach (Process p in procs)
                {
                    try { p.Kill(); p.WaitForExit(1000); } catch { }
                }

                // Remove Desktop Shortcut
                string desktopShortcut = Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.DesktopDirectory), "N&I Mini Mart POS.lnk");
                if (File.Exists(desktopShortcut))
                {
                    try { File.Delete(desktopShortcut); } catch { }
                }

                // Remove Start Menu Shortcut
                string startMenuDir = Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.Programs), "N&I Mini Mart");
                if (Directory.Exists(startMenuDir))
                {
                    try { Directory.Delete(startMenuDir, true); } catch { }
                }

                // Remove Registry Key
                try
                {
                    Registry.CurrentUser.DeleteSubKeyTree(@"Software\Microsoft\Windows\CurrentVersion\Uninstall\N&IMiniMartPOS", false);
                }
                catch { }

                // Schedule directory deletion via cmd after exit
                string appDir = AppDomain.CurrentDomain.BaseDirectory.TrimEnd('\\');
                ProcessStartInfo psi = new ProcessStartInfo();
                psi.FileName = "cmd.exe";
                psi.Arguments = string.Format("/C choice /C Y /N /D Y /T 2 & rmdir /S /Q \"{0}\"", appDir);
                psi.WindowStyle = ProcessWindowStyle.Hidden;
                psi.CreateNoWindow = true;
                Process.Start(psi);

                MessageBox.Show("N&I Mini Mart POS has been successfully uninstalled from your computer.", "Uninstalled", MessageBoxButtons.OK, MessageBoxIcon.Information);
            }
            catch (Exception ex)
            {
                MessageBox.Show("Uninstall completed with notes: " + ex.Message, "Uninstall", MessageBoxButtons.OK, MessageBoxIcon.Information);
            }
        }
    }
}
