# 🔧 Solución: Cambiar "node:sea" por "node-sea"

## 📋 Problema

Expo intenta crear un directorio llamado `node:sea` que Windows no permite porque los dos puntos (`:`) son caracteres reservados.

## ✅ Solución: Script de Corrección

He creado un script que **reemplaza automáticamente** `node:sea` por `node-sea` (sin dos puntos) en los archivos de Expo.

### Opción 1: Usar el Script Completo (Recomendado)

```powershell
.\start-fixed.ps1
```

Este script:
1. ✅ Busca y corrige todos los archivos con `node:sea`
2. ✅ Limpia la caché
3. ✅ Inicia Expo

### Opción 2: Solo Corregir (sin iniciar)

```powershell
.\fix-expo-node-sea.ps1
```

Luego ejecuta normalmente:
```powershell
npx expo start
```

### Opción 3: Usar npm script

```powershell
npm run fix:node-sea
```

Luego:
```powershell
npx expo start
```

## 🔍 ¿Qué hace el script?

1. **Busca** todos los archivos en `node_modules/@expo` que contengan `node:sea`
2. **Reemplaza** `node:sea` por `node-sea` (sin dos puntos)
3. **Guarda** los archivos modificados

## ⚠️ Nota Importante

**Después de ejecutar `npm install`, necesitarás volver a ejecutar el script de corrección**, ya que `npm install` regenera los archivos en `node_modules`.

## 🚀 Uso Rápido

```powershell
# Corrección completa + inicio
.\start-fixed.ps1

# O solo corrección
.\fix-expo-node-sea.ps1
npx expo start
```

## 📝 Alternativa: Variable de Entorno

Si prefieres no modificar archivos, también puedes usar:

```powershell
$env:EXPO_NO_METRO_LAZY = "1"
npx expo start --clear
```

---

**¡El script cambia los dos puntos por guiones automáticamente!** 🎉

