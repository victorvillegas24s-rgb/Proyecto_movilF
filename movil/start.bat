@echo off
REM Script batch para iniciar Expo con solución para Windows
echo ========================================
echo   Iniciando Expo con solucion Windows
echo ========================================
echo.

REM Configurar variable de entorno
set EXPO_NO_METRO_LAZY=1

REM Verificar que se configuro
echo Variable EXPO_NO_METRO_LAZY = %EXPO_NO_METRO_LAZY%
echo.

REM Limpiar cache si existe
if exist ".expo" (
    echo Limpiando cache...
    rmdir /s /q .expo 2>nul
)

REM Iniciar Expo
echo Iniciando Expo...
echo.
npx expo start --clear

pause

