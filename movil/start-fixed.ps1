# Script completo: Corrige el problema y luego inicia Expo

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Corrección y Inicio de Expo" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Paso 1: Corregir el problema de node:sea
Write-Host "Paso 1: Corrigiendo problema de node:sea..." -ForegroundColor Yellow
& ".\fix-expo-node-sea.ps1"

Write-Host ""

# Paso 2: Limpiar caché
Write-Host "Paso 2: Limpiando caché..." -ForegroundColor Yellow
if (Test-Path ".expo") {
    Remove-Item -Recurse -Force ".expo" -ErrorAction SilentlyContinue
    Write-Host "✓ Caché limpiada" -ForegroundColor Green
}

Write-Host ""

# Paso 3: Iniciar Expo
Write-Host "Paso 3: Iniciando Expo..." -ForegroundColor Green
Write-Host ""
npx expo start --clear

