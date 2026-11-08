# N&I Mini Mart POS - Windows Desktop Workstation App

Fast, native, and lightweight Windows Desktop Application & Installer for N&I Mini Mart POS.

## 🚀 Key Features
- **Ultra-Fast & Lightweight:** 48 KB standalone setup installer, starts in < 0.2 seconds with ~30MB memory.
- **Dedicated POS Window:** No address bar, no browser tabs, distraction-free cashier counter.
- **Permanent Session:** Keeps the cashier securely logged in with an isolated browser profile (`%LOCALAPPDATA%\N&IMiniMartPOS\BrowserProfile`).
- **Hardware Ready:** Full support for USB/Bluetooth barcode scanners, ESC/POS thermal printers, receipt popup printing, and POS shortcut keys (`F2`, `F4`, `Enter`, `Esc`).
- **System Tray Integration:** Runs quietly in the notification area with 1-click reopen, F5 reload, and storefront shortcuts.
- **Complete Windows Setup Wizard:** Desktop shortcut (`.lnk`), Start Menu group, and Windows Add/Remove Programs uninstaller.

## 📦 Download & Install
Download `N_I_POS_Setup_v1.0.exe` directly from the website:
- **Direct Link:** `https://nabeel.site.je/downloads/N_I_POS_Setup_v1.0.exe`
- Or click the **"Windows App"** button inside the POS header or admin sidebar.

## 🔨 How to Recompile
Run via PowerShell on any Windows PC:
```powershell
# 1. Compile Launcher
& 'C:\Windows\Microsoft.NET\Framework64\v4.0.30319\csc.exe' /target:winexe /optimize+ /win32icon:app.ico /out:POS_App.exe Launcher.cs

# 2. Compile Uninstaller
& 'C:\Windows\Microsoft.NET\Framework64\v4.0.30319\csc.exe' /target:winexe /optimize+ /win32icon:app.ico /out:Uninstall.exe Uninstaller.cs

# 3. Compile Standalone Setup Wizard
$argsList = @(
    '/target:winexe',
    '/optimize+',
    '/win32icon:app.ico',
    '/resource:POS_App.exe,POS_EXE',
    '/resource:Uninstall.exe,UNINSTALL_EXE',
    '/resource:app.ico,APP_ICO',
    '/out:N_I_POS_Setup_v1.0.exe',
    'SetupInstaller.cs'
)
& 'C:\Windows\Microsoft.NET\Framework64\v4.0.30319\csc.exe' $argsList
```
