# ============================================
# SOLUCIÓN PARA ERROR DE EXPO EN WINDOWS
# ============================================
# Copia y pega TODO este contenido en PowerShell
# O ejecuta: .\EJECUTAR_AQUI.ps1

# Ir a la carpeta correcta
Set-Location "C:\Users\Yecsa\Documents\ProyectoFinalMovil\movil"

# Configurar variable de entorno (CRÍTICO)
$env:EXPO_NO_METRO_LAZY = "1"

# Verificar que se configuró
Write-Host "Variable EXPO_NO_METRO_LAZY = $env:EXPO_NO_METRO_LAZY" -ForegroundColor Green

# Limpiar caché
if (Test-Path ".expo") {
    Write-Host "Limpiando caché..." -ForegroundColor Yellow
    Remove-Item -Recurse -Force ".expo" -ErrorAction SilentlyContinue
}

# Iniciar Expo
Write-Host "`nIniciando Expo..." -ForegroundColor Cyan
Write-Host "Si ves un error, la variable no se configuró correctamente" -ForegroundColor Yellow
Write-Host ""

npx expo start --clear

