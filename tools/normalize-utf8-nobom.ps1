param(
  [string]$Root = "."
)

$ErrorActionPreference = "Stop"

$rootPath = Resolve-Path $Root
$extensions = @("*.php", "*.css", "*.js", "*.json", "*.md", "*.txt")
$files = Get-ChildItem -Path $rootPath -Recurse -File -Include $extensions

$utf8NoBom = New-Object System.Text.UTF8Encoding($false)
$changed = 0

foreach ($file in $files) {
  $text = [System.IO.File]::ReadAllText($file.FullName, [System.Text.Encoding]::UTF8)
  $bytes = [System.IO.File]::ReadAllBytes($file.FullName)
  $hasBom = ($bytes.Length -ge 3 -and $bytes[0] -eq 239 -and $bytes[1] -eq 187 -and $bytes[2] -eq 191)

  if ($hasBom) {
    [System.IO.File]::WriteAllText($file.FullName, $text, $utf8NoBom)
    $changed++
  }
}

Write-Host "Arquivos normalizados para UTF-8 sem BOM: $changed"

