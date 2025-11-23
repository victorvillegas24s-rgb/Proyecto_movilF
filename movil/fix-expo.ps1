# Script para corregir el error de Expo en Windows
# Ejecutar: .\fix-expo.ps1

Write-Host "Limpiando caché de Expo..." -ForegroundColor Yellow

# Limpiar caché de Expo
if (Test-Path ".expo") {
    Remove-Item -Recurse -Force ".expo" -ErrorAction SilentlyContinue
}

# Limpiar caché de npm
Write-Host "Limpiando caché de npm..." -ForegroundColor Yellow
npm cache clean --force

# Limpiar watchman si está instalado
if (Get-Command watchman -ErrorAction SilentlyContinue) {
    Write-Host "Limpiando watchman..." -ForegroundColor Yellow
    watchman watch-del-all
}

Write-Host "`nReinstalando dependencias..." -ForegroundColor Yellow
Remove-Item -Recurse -Force "node_modules" -ErrorAction SilentlyContinue
npm install

Write-Host "`n✓ Limpieza completada. Ahora ejecuta: npx expo start --clear" -ForegroundColor Green

