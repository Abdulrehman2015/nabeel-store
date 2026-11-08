@echo off
echo ========================================================
echo   Compiling Store POS Terminal for Windows (.exe)
echo ========================================================

C:\Windows\Microsoft.NET\Framework64\v4.0.30319\csc.exe /target:winexe /out:POSTerminal.exe /win32icon:public\images\app.ico /reference:System.Windows.Forms.dll,System.Drawing.dll POSTerminal.cs

if %ERRORLEVEL% EQU 0 (
    echo [SUCCESS] POSTerminal.exe compiled successfully!
    copy /Y POSTerminal.exe "%USERPROFILE%\Desktop\POS-Terminal.exe" >nul 2>&1
    copy /Y POSTerminal.exe "%USERPROFILE%\OneDrive\Desktop\POS-Terminal.exe" >nul 2>&1
) else (
    echo [ERROR] Compilation failed.
)
