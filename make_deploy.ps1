$ErrorActionPreference = 'Stop'
$base = "F:\My AI\nabeel-store"
$stage = Join-Path $base "_deploy_stage"
$zip = Join-Path $base "deploy.zip"

if (Test-Path $stage) { Remove-Item -Recurse -Force $stage }
if (Test-Path $zip) { Remove-Item -Force $zip }

New-Item -ItemType Directory -Path $stage | Out-Null

$itemsToCopy = @(
    'app',
    'bootstrap',
    'config',
    'database',
    'public',
    'resources',
    'routes',
    'storage',
    '.htaccess',
    'artisan',
    'composer.json',
    'composer.lock',
    '.env.example',
    'database.sql'
)

foreach ($item in $itemsToCopy) {
    $src = Join-Path $base $item
    if (Test-Path $src) {
        Copy-Item -Path $src -Destination $stage -Recurse -Force
    }
}

# Clean cache files from stage storage and bootstrap
Get-ChildItem -Path (Join-Path $stage "storage\logs\*.log") -ErrorAction SilentlyContinue | Remove-Item -Force
Get-ChildItem -Path (Join-Path $stage "storage\framework\cache\data\*") -ErrorAction SilentlyContinue | Remove-Item -Recurse -Force
Get-ChildItem -Path (Join-Path $stage "storage\framework\views\*") -ErrorAction SilentlyContinue | Remove-Item -Force
Get-ChildItem -Path (Join-Path $stage "storage\framework\sessions\*") -ErrorAction SilentlyContinue | Remove-Item -Force
Get-ChildItem -Path (Join-Path $stage "bootstrap\cache\*.php") -ErrorAction SilentlyContinue | Remove-Item -Force

Write-Host "Staging ready. Compressing with tar.exe to deploy.zip..."
tar.exe -a -c -f "$zip" -C "$stage" .

Remove-Item -Recurse -Force $stage

$fileInfo = Get-Item $zip
Write-Host "Done! File created: $($fileInfo.FullName) ($([math]::Round($fileInfo.Length / 1MB, 2)) MB)"
