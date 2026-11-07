$desktopPaths = @(
    [Environment]::GetFolderPath('Desktop'),
    'C:\Users\ss487\Desktop'
) | Select-Object -Unique

$wsh = New-Object -ComObject WScript.Shell
$icoPath = "F:\My AI\nabeel-store\public\app-icon.ico"
$vbsPath = "F:\My AI\nabeel-store\start-pos.vbs"

foreach ($dir in $desktopPaths) {
    if (Test-Path $dir) {
        # 1. Local One-Click POS App Shortcut (.lnk)
        $shortcutFile = Join-Path $dir "N&I Mini Mart POS.lnk"
        $shortcut = $wsh.CreateShortcut($shortcutFile)
        $shortcut.TargetPath = "wscript.exe"
        $shortcut.Arguments = "`"$vbsPath`""
        $shortcut.WorkingDirectory = "F:\My AI\nabeel-store"
        $shortcut.IconLocation = "$icoPath,0"
        $shortcut.Description = "N&I Mini Mart - POS Register & Admin"
        $shortcut.Save()
        Write-Output "Created Desktop Shortcut: $shortcutFile"

        # 2. Live Cloud Server Shortcut (.url)
        $liveFile = Join-Path $dir "N&I Mini Mart (Live Server).url"
        $lines = @(
            "[InternetShortcut]",
            "URL=https://nabeel.site.je/admin/pos",
            "IconIndex=0",
            "IconFile=$icoPath"
        )
        [System.IO.File]::WriteAllLines($liveFile, $lines)
        Write-Output "Created Live Server Shortcut: $liveFile"
    }
}
