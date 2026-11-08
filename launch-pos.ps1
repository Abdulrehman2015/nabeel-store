# Ensure MySQL is running
$mysql = Get-Process mysqld -ErrorAction SilentlyContinue
if (-not $mysql) {
    if (Test-Path "C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqld.exe") {
        Start-Process "C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqld.exe" -ArgumentList "--defaults-file=C:\laragon\bin\mysql\mysql-8.4.3-winx64\my.ini" -WindowStyle Hidden
        Start-Sleep -Seconds 2
    }
}

# Ensure Laravel artisan serve is running on 8000
$portListening = $false
try {
    $tcp = New-Object System.Net.Sockets.TcpClient
    $tcp.Connect("127.0.0.1", 8000)
    $tcp.Close()
    $portListening = $true
} catch {}

if (-not $portListening) {
    $phpExe = "C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe"
    if (Test-Path $phpExe) {
        Start-Process $phpExe -ArgumentList "artisan serve --host=127.0.0.1 --port=8000" -WorkingDirectory "F:\My AI\nabeel-store" -WindowStyle Hidden
        Start-Sleep -Seconds 2
    }
}

# Launch POS Register in Chrome or Edge standalone app mode
$url = "http://127.0.0.1:8000/admin/pos"

$chrome64 = "$env:ProgramFiles\Google\Chrome\Application\chrome.exe"
$chrome86 = "${env:ProgramFiles(x86)}\Google\Chrome\Application\chrome.exe"
$chromeUser = "$env:LocalAppData\Google\Chrome\Application\chrome.exe"
$edge = "$env:ProgramFiles(x86)\Microsoft\Edge\Application\msedge.exe"

if (Test-Path $chrome64) {
    Start-Process $chrome64 -ArgumentList "--app=$url"
} elseif (Test-Path $chrome86) {
    Start-Process $chrome86 -ArgumentList "--app=$url"
} elseif (Test-Path $chromeUser) {
    Start-Process $chromeUser -ArgumentList "--app=$url"
} elseif (Test-Path $edge) {
    Start-Process $edge -ArgumentList "--app=$url"
} else {
    Start-Process $url
}

