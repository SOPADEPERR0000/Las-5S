$bytes = [System.IO.File]::ReadAllBytes("index.html")
$content = [System.Text.Encoding]::Latin1.GetString($bytes)
[System.IO.File]::WriteAllText("index.html", $content, (New-Object System.Text.UTF8Encoding $false))
Write-Host "Done"
