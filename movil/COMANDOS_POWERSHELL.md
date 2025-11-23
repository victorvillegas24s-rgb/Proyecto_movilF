# 📝 Comandos Correctos de PowerShell

## ❌ Comandos INCORRECTOS (CMD)

Estos comandos NO funcionan en PowerShell:

```powershell
# ❌ NO funciona en PowerShell
rmdir /s /q node_modules
rmdir /s /q .expo
```

## ✅ Comandos CORRECTOS (PowerShell)

### Eliminar Carpetas

```powershell
# Eliminar carpeta node_modules
Remove-Item -Recurse -Force node_modules

# Eliminar carpeta .expo
Remove-Item -Recurse -Force .expo

# Eliminar múltiples carpetas
Remove-Item -Recurse -Force node_modules, .expo
```

### Verificar si existe una carpeta

```powershell
# Verificar si existe
Test-Path node_modules

# Listar contenido
Get-ChildItem
# o
dir
# o
ls
```

### Navegar entre carpetas

```powershell
# Ir a una carpeta
cd movil

# Volver atrás
cd ..

# Ir a la raíz del proyecto
cd C:\Users\Yecsa\Documents\ProyectoFinalMovil
```

### Instalar dependencias

```powershell
# Instalar (correcto)
npm install

# ❌ NO funciona
nmp install  # Error de tipeo
```

### Ejecutar Expo con solución para Windows

```powershell
# Opción 1: Variable de entorno en la misma línea
$env:EXPO_NO_METRO_LAZY = "1"; npx expo start --clear

# Opción 2: Usar el script incluido
.\start-expo.ps1

# Opción 3: Usar npm script
npm run start:windows
```

### Limpiar y reinstalar todo

```powershell
# Limpiar todo
Remove-Item -Recurse -Force node_modules, .expo -ErrorAction SilentlyContinue
npm cache clean --force
npm install
```

### Ver versión de Node y npm

```powershell
node --version
npm --version
```

## 🔧 Scripts Útiles Incluidos

### 1. `start-expo.ps1` - Iniciar Expo con solución
```powershell
.\start-expo.ps1
```

### 2. `fix-expo.ps1` - Limpiar y reparar
```powershell
.\fix-expo.ps1
```

## 💡 Consejos

1. **Siempre usa PowerShell**, no CMD, para comandos modernos
2. **Usa `-ErrorAction SilentlyContinue`** para evitar errores si la carpeta no existe
3. **Usa `-Force`** para eliminar carpetas con contenido
4. **Usa `-Recurse`** para eliminar carpetas y su contenido

## 🆘 Si tienes problemas de permisos

Ejecuta PowerShell como Administrador:
1. Clic derecho en PowerShell
2. "Ejecutar como administrador"
3. Navega a tu proyecto y ejecuta los comandos

