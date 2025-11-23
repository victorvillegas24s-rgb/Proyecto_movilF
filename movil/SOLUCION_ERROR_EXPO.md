# 🔧 Solución para Error de Expo en Windows

## ❌ Error Encontrado

```
Error: ENOENT: no such file or directory, mkdir 'node:sea'
```

Este es un bug conocido de Expo/Metro en Windows donde intenta crear un directorio con dos puntos (`:`) en el nombre, lo cual Windows no permite.

## ✅ Soluciones

### Solución 1: Usar Variable de Entorno (Recomendada)

Ejecuta Expo con la variable de entorno `EXPO_NO_METRO_LAZY`:

**PowerShell:**
```powershell
$env:EXPO_NO_METRO_LAZY = "1"
npx expo start --clear
```

**CMD:**
```cmd
set EXPO_NO_METRO_LAZY=1
npx expo start --clear
```

### Solución 2: Actualizar Expo y Dependencias

```powershell
# Actualizar Expo CLI globalmente
npm install -g @expo/cli@latest

# Limpiar y reinstalar
Remove-Item -Recurse -Force node_modules
Remove-Item -Recurse -Force .expo
npm install
npx expo start --clear
```

### Solución 3: Usar Script de PowerShell

Ejecuta el script incluido:

```powershell
.\fix-expo.ps1
```

Luego:
```powershell
$env:EXPO_NO_METRO_LAZY = "1"
npx expo start --clear
```

### Solución 4: Crear Script de Inicio Permanente

Crea un archivo `start-expo.ps1`:

```powershell
# start-expo.ps1
$env:EXPO_NO_METRO_LAZY = "1"
npx expo start --clear
```

Ejecuta con:
```powershell
.\start-expo.ps1
```

### Solución 5: Actualizar package.json

Agrega un script en `package.json`:

```json
"scripts": {
  "start": "cross-env EXPO_NO_METRO_LAZY=1 expo start --clear",
  "start:windows": "$env:EXPO_NO_METRO_LAZY='1'; expo start --clear"
}
```

Luego instala `cross-env`:
```powershell
npm install --save-dev cross-env
```

Y ejecuta:
```powershell
npm run start
```

## 🔍 Verificación

Si el error persiste, verifica:

1. **Versión de Node.js:** Debe ser 18.x o superior
   ```powershell
   node --version
   ```

2. **Versión de npm:** Debe ser 9.x o superior
   ```powershell
   npm --version
   ```

3. **Permisos:** Ejecuta PowerShell como Administrador si es necesario

4. **Antivirus:** Algunos antivirus bloquean la creación de carpetas. Agrega una excepción para la carpeta del proyecto

## 📝 Notas

- El flag `--clear` limpia la caché de Metro
- La variable `EXPO_NO_METRO_LAZY` desactiva la carga diferida de Metro, evitando el problema del directorio `node:sea`
- Si ninguna solución funciona, considera usar WSL2 (Windows Subsystem for Linux)

## 🆘 Si Nada Funciona

1. Usa WSL2:
   ```bash
   # En WSL2
   cd /mnt/c/Users/Yecsa/Documents/ProyectoFinalMovil/movil
   npm install
   npx expo start
   ```

2. O usa Docker (si tienes Docker Desktop instalado)

