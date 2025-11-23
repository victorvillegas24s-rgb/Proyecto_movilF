# 🚀 Inicio Rápido - Solución Error Expo Windows

## ⚡ Solución Inmediata

**Copia y pega este comando completo en PowerShell:**

```powershell
cd C:\Users\Yecsa\Documents\ProyectoFinalMovil\movil; $env:EXPO_NO_METRO_LAZY = "1"; npx expo start --clear
```

## 📋 Pasos Detallados

### Opción 1: Usar el Script (Recomendado)

```powershell
cd C:\Users\Yecsa\Documents\ProyectoFinalMovil\movil
.\start-expo.ps1
```

### Opción 2: Comando Manual

```powershell
# 1. Ir a la carpeta del proyecto
cd C:\Users\Yecsa\Documents\ProyectoFinalMovil\movil

# 2. Configurar variable de entorno
$env:EXPO_NO_METRO_LAZY = "1"

# 3. Iniciar Expo
npx expo start --clear
```

### Opción 3: Usar npm script

```powershell
cd C:\Users\Yecsa\Documents\ProyectoFinalMovil\movil
npm run start:windows
```

## 🔍 Verificar que Funciona

Si ves algo como esto, **¡funcionó!**

```
Starting project at C:\Users\Yecsa\Documents\ProyectoFinalMovil\movil
Metro waiting on exp://192.168.x.x:8081
```

## ❌ Si Sigue Fallando

1. **Cierra todas las ventanas de PowerShell**
2. **Abre una nueva ventana de PowerShell como Administrador**
3. **Ejecuta el comando completo de nuevo**

## 💡 Nota Importante

**SIEMPRE** debes configurar la variable `EXPO_NO_METRO_LAZY = "1"` **ANTES** de ejecutar `npx expo start`.

Si ejecutas solo `npx expo start` sin la variable, **volverá a fallar**.

