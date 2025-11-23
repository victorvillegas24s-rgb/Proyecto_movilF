# Script de inicio de Expo con solución para Windows
# Ejecutar: .\start-expo.ps1

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Iniciando Expo con solución Windows" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Configurar variable de entorno para evitar error de node:sea
Write-Host "Configurando variable de entorno EXPO_NO_METRO_LAZY..." -ForegroundColor Yellow
$env:EXPO_NO_METRO_LAZY = "1"

# Verificar que se configuró correctamente
if ($env:EXPO_NO_METRO_LAZY -eq "1") {
    Write-Host "✓ Variable de entorno configurada correctamente" -ForegroundColor Green
} else {
    Write-Host "✗ Error al configurar variable de entorno" -ForegroundColor Red
    exit 1
}

# Limpiar caché si existe
if (Test-Path ".expo") {
    Write-Host "`nLimpiando caché de Expo..." -ForegroundColor Yellow
    Remove-Item -Recurse -Force ".expo" -ErrorAction SilentlyContinue
    Write-Host "✓ Caché limpiada" -ForegroundColor Green
} else {
    Write-Host "`nNo hay caché para limpiar" -ForegroundColor Gray
}

Write-Host ""
Write-Host "Iniciando servidor de desarrollo Expo..." -ForegroundColor Green
Write-Host "Presiona Ctrl+C para detener el servidor" -ForegroundColor Gray
Write-Host ""

# Iniciar Expo con caché limpia
npx expo start --clear

