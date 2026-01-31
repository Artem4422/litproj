$json = Get-Content -Path "vk_products.json" -Encoding UTF8 | ConvertFrom-Json
$imagesDir = "vk_products_images"

if (-not (Test-Path $imagesDir)) {
    New-Item -ItemType Directory -Path $imagesDir | Out-Null
}

$client = New-Object System.Net.WebClient
$client.Headers.Add("User-Agent", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36")

for ($i = 0; $i -lt $json.Count; $i++) {
    $product = $json[$i]
    $url = $product.imageUrl
    $title = $product.title -replace '[^\w\s-]', '' -replace '\s+', '-'
    $title = $title.Substring(0, [Math]::Min(50, $title.Length))
    
    $ext = ".jpg"
    if ($url -match '\.(jpg|jpeg|png|gif|webp)') {
        $ext = "." + $matches[1]
    }
    
    $filename = "{0:D3}_{1}{2}" -f ($i + 1), $title, $ext
    $filepath = Join-Path $imagesDir $filename
    
    Write-Host "Скачивание $($i + 1)/$($json.Count): $($product.title)"
    
    try {
        $client.DownloadFile($url, $filepath)
        $product.localImage = "vk_products_images/$filename"
        Start-Sleep -Milliseconds 500
    } catch {
        Write-Host "Ошибка при скачивании: $_" -ForegroundColor Red
    }
}

$json | ConvertTo-Json -Depth 10 | Set-Content -Path "vk_products.json" -Encoding UTF8
Write-Host ""
Write-Host "Ready! Downloaded images to folder: $imagesDir"
