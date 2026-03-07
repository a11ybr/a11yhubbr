param(
  [string]$Root = "."
)

$ErrorActionPreference = "Stop"

$rootPath = Resolve-Path $Root
$extensions = @("*.php", "*.css", "*.js", "*.json", "*.md", "*.txt")
$files = Get-ChildItem -Path $rootPath -Recurse -File -Include $extensions

$charC3 = [string][char]0x00C3
$charC2 = [string][char]0x00C2
$charE2 = [string][char]0x00E2
$markerIQuestion = ([string][char]0x00EF) + ([char]0x00BF) + ([char]0x00BD)

$issues = @()

foreach ($file in $files) {
  $bytes = [System.IO.File]::ReadAllBytes($file.FullName)
  $hasBom = ($bytes.Length -ge 3 -and $bytes[0] -eq 239 -and $bytes[1] -eq 187 -and $bytes[2] -eq 191)

  $text = [System.IO.File]::ReadAllText($file.FullName, [System.Text.Encoding]::UTF8)
  $hasReplacementChar = $text.IndexOf([char]0xFFFD) -ge 0
  $hasMojibake = $text.Contains($charC3) -or $text.Contains($charC2) -or $text.Contains($charE2) -or $text.Contains($markerIQuestion)

  if ($hasBom -or $hasReplacementChar -or $hasMojibake) {
    $issues += [PSCustomObject]@{
      File = $file.FullName
      Bom = $hasBom
      ReplacementChar = $hasReplacementChar
      Mojibake = $hasMojibake
    }
  }
}

if ($issues.Count -eq 0) {
  Write-Host "OK: encoding limpo (UTF-8 sem BOM, sem replacement char, sem mojibake)."
  exit 0
}

Write-Host "ERRO: problemas de encoding detectados:" -ForegroundColor Red
$issues | ForEach-Object {
  Write-Host "- $($_.File)"
  Write-Host "  BOM=$($_.Bom) ReplacementChar=$($_.ReplacementChar) Mojibake=$($_.Mojibake)"
}
exit 1