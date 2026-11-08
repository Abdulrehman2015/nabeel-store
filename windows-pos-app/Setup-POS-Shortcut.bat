@echo off
setlocal enabledelayedexpansion
title N&I Mini Mart POS - Setup

echo ======================================================================
echo                 N&I Mini Mart POS - Workstation Setup
echo ======================================================================
echo.

set "TARGET_URL=https://nabeel.site.je/admin/pos"
set "APP_NAME=N&I Mini Mart POS"
set "PROFILE_DIR=%LOCALAPPDATA%\N&IMiniMartPOS\BrowserProfile"

:: 1. Find modern browser (Edge or Chrome)
set "BROWSER_EXE="

if exist "%ProgramFiles(x86)%\Microsoft\Edge\Application\msedge.exe" (
    set "BROWSER_EXE=%ProgramFiles(x86)%\Microsoft\Edge\Application\msedge.exe"
) else if exist "%ProgramFiles%\Microsoft\Edge\Application\msedge.exe" (
    set "BROWSER_EXE=%ProgramFiles%\Microsoft\Edge\Application\msedge.exe"
) else if exist "%LocalAppData%\Microsoft\Edge\Application\msedge.exe" (
    set "BROWSER_EXE=%LocalAppData%\Microsoft\Edge\Application\msedge.exe"
) else if exist "%ProgramFiles%\Google\Chrome\Application\chrome.exe" (
    set "BROWSER_EXE=%ProgramFiles%\Google\Chrome\Application\chrome.exe"
) else if exist "%ProgramFiles(x86)%\Google\Chrome\Application\chrome.exe" (
    set "BROWSER_EXE=%ProgramFiles(x86)%\Google\Chrome\Application\chrome.exe"
) else (
    set "BROWSER_EXE=msedge.exe"
)

:: 2. Ensure profile directory exists
if not exist "%PROFILE_DIR%" mkdir "%PROFILE_DIR%"

:: 3. Create Windows Desktop & Start Menu Shortcuts via VBScript
set "VBS_SCRIPT=%TEMP%\create_pos_shortcut.vbs"
set "DESKTOP_LNK=%USERPROFILE%\Desktop\%APP_NAME%.lnk"
set "STARTMENU_DIR=%APPDATA%\Microsoft\Windows\Start Menu\Programs\N&I Mini Mart"
if not exist "%STARTMENU_DIR%" mkdir "%STARTMENU_DIR%"
set "STARTMENU_LNK=%STARTMENU_DIR%\%APP_NAME%.lnk"

(
echo Set oWS = WScript.CreateObject^("WScript.Shell"^)
echo Set oLink = oWS.CreateShortcut^("%DESKTOP_LNK%"^)
echo oLink.TargetPath = "%BROWSER_EXE%"
echo oLink.Arguments = "--app=""%TARGET_URL%"" --user-data-dir=""%PROFILE_DIR%"" --start-maximized --no-first-run --no-default-browser-check"
echo oLink.Description = "%APP_NAME% Register Workstation"
echo oLink.WindowStyle = 1
echo oLink.Save
echo Set oLinkStart = oWS.CreateShortcut^("%STARTMENU_LNK%"^)
echo oLinkStart.TargetPath = "%BROWSER_EXE%"
echo oLinkStart.Arguments = "--app=""%TARGET_URL%"" --user-data-dir=""%PROFILE_DIR%"" --start-maximized --no-first-run --no-default-browser-check"
echo oLinkStart.Description = "%APP_NAME% Register Workstation"
echo oLinkStart.WindowStyle = 1
echo oLinkStart.Save
) > "%VBS_SCRIPT%"

cscript //nologo "%VBS_SCRIPT%"
del "%VBS_SCRIPT%" 2>nul

echo [SUCCESS] Desktop Shortcut created successfully!
echo [SUCCESS] Start Menu Shortcut created!
echo.
echo Launching N&I Mini Mart POS now...
echo.

start "" "%BROWSER_EXE%" --app="%TARGET_URL%" --user-data-dir="%PROFILE_DIR%" --start-maximized --no-first-run --no-default-browser-check

timeout /t 3 >nul
exit

