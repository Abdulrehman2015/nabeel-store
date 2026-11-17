@echo off
setlocal enabledelayedexpansion
title N&I Mini Mart - Offline-First POS Workstation

echo ======================================================================
echo           N&I Mini Mart POS - Starting Offline Workstation
echo ======================================================================
echo.

set "SCRIPT_DIR=%~dp0"
cd /d "%SCRIPT_DIR%.."

:: 1. Check if PHP is available
set "PHP_CMD=php"
where php >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    if exist "C:\laragon\bin\php\php-8.2.30-Win32-vs16-x64\php.exe" (
        set "PHP_CMD=C:\laragon\bin\php\php-8.2.30-Win32-vs16-x64\php.exe"
    ) else if exist "C:\xampp\php\php.exe" (
        set "PHP_CMD=C:\xampp\php\php.exe"
    ) else (
        echo [ERROR] PHP runtime not found in PATH or standard directories.
        echo Please ensure PHP 8.2+ is installed.
        pause
        exit /b 1
    )
)

:: 2. Ensure local database & migrations are ready
echo [1/3] Checking local database...
if not exist "database\database.sqlite" (
    type nul > "database\database.sqlite"
)

:: 3. Check if local background server is already running on port 8765
echo [2/3] Checking local background server...
netstat -ano | findstr ":8765" >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo Starting embedded local server on http://127.0.0.1:8765 ...
    start /b "" "%PHP_CMD%" -S 127.0.0.1:8765 -t public >nul 2>&1
    timeout /t 2 >nul
)

:: 4. Launch Desktop Window
echo [3/3] Launching N&I Mini Mart POS Desktop Window...
if exist "windows-pos-app\POS_App.exe" (
    start "" "windows-pos-app\POS_App.exe" --url="http://127.0.0.1:8765/admin/pos"
) else (
    start msedge --app="http://127.0.0.1:8765/admin/pos" --start-maximized
)

echo.
echo ======================================================================
echo [SUCCESS] POS Workstation is running in Offline-First mode!
echo All sales are saved locally and will auto-sync when online.
echo ======================================================================
timeout /t 3 >nul
exit
