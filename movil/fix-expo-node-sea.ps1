# Script para corregir el problema de node:sea en Windows
# Este script reemplaza "node:sea" por "node-sea" en los archivos de Expo

Write-Host "Corrigiendo problema de node:sea en Expo..." -ForegroundColor Cyan
Write-Host ""

# Buscar todos los archivos que contengan "node:sea"
$searchPath = "node_modules\@expo"
$filesFound = @()

if (Test-Path $searchPath) {
    Write-Host "Buscando archivos con 'node:sea'..." -ForegroundColor Yellow
    
    # Buscar en archivos TypeScript y JavaScript (incluyendo compilados)
    $files = Get-ChildItem -Path $searchPath -Recurse -Include *.ts,*.js,*.tsx,*.jsx -ErrorAction SilentlyContinue | Where-Object { $_.FullName -notmatch '\\node_modules\\' -or $_.FullName -match '@expo\\cli' }
    
    foreach ($file in $files) {
        $content = Get-Content $file.FullName -Raw -ErrorAction SilentlyContinue
        if ($content -match 'node:sea') {
            $filesFound += $file.FullName
            Write-Host "  Encontrado: $($file.FullName)" -ForegroundColor Gray
        }
    }
    
    if ($filesFound.Count -gt 0) {
        Write-Host ""
        Write-Host "Modificando $($filesFound.Count) archivo(s)..." -ForegroundColor Yellow
        
        foreach ($filePath in $filesFound) {
            try {
                $content = Get-Content $filePath -Raw -Encoding UTF8
                $originalContent = $content
                
                # Reemplazar todas las variantes de "node:sea" por "node-sea"
                $content = $content -replace 'node:sea', 'node-sea'
                $content = $content -replace '"node:sea"', '"node-sea"'
                $content = $content -replace "'node:sea'", "'node-sea'"
                $content = $content -replace '`node:sea`', '`node-sea`'
                $content = $content -replace 'node:sea', 'node-sea'
                
                if ($content -ne $originalContent) {
                    Set-Content -Path $filePath -Value $content -Encoding UTF8 -NoNewline
                    Write-Host "  ✓ Modificado: $filePath" -ForegroundColor Green
                }
            } catch {
                Write-Host "  ✗ Error al modificar: $filePath - $($_.Exception.Message)" -ForegroundColor Red
            }
        }
        
        Write-Host ""
        Write-Host "✓ Corrección completada!" -ForegroundColor Green
        Write-Host "Ahora puedes ejecutar: npx expo start" -ForegroundColor Cyan
    } else {
        Write-Host "No se encontraron archivos con 'node:sea'" -ForegroundColor Yellow
        Write-Host "El problema puede estar en otro lugar. Usa la variable de entorno:" -ForegroundColor Yellow
        Write-Host "  `$env:EXPO_NO_METRO_LAZY = '1'; npx expo start --clear" -ForegroundColor Cyan
    }
} else {
    Write-Host "✗ No se encontró la carpeta: $searchPath" -ForegroundColor Red
    Write-Host "Asegúrate de haber ejecutado 'npm install' primero" -ForegroundColor Yellow
}

