# 🔧 Solución Paso a Paso - Error de Expo en Windows

## ❌ El Error

```
Error: ENOENT: no such file or directory, mkdir 'node:sea'
```

Windows no permite crear carpetas con dos puntos (`:`) en el nombre.

---

## ✅ SOLUCIÓN: Variable de Entorno

### 📋 Paso 1: Abrir PowerShell

1. Presiona `Windows + X`
2. Selecciona **"Windows PowerShell"** o **"Terminal"**
3. O busca "PowerShell" en el menú de inicio

---

### 📋 Paso 2: Navegar a la Carpeta del Proyecto

Copia y pega este comando:

```powershell
cd C:\Users\Yecsa\Documents\ProyectoFinalMovil\movil
```

Presiona **Enter**.

---

### 📋 Paso 3: Configurar la Variable de Entorno

Copia y pega este comando:

```powershell
$env:EXPO_NO_METRO_LAZY = "1"
```

Presiona **Enter**.

**Verificación:** Para confirmar que se configuró, ejecuta:

```powershell
Write-Host $env:EXPO_NO_METRO_LAZY
```

Debe mostrar: `1`

---

### 📋 Paso 4: Iniciar Expo

Copia y pega este comando:

```powershell
npx expo start --clear
```

Presiona **Enter**.

---

## 🚀 SOLUCIÓN EN UNA SOLA LÍNEA (Más Rápida)

Si prefieres hacerlo todo de una vez, copia y pega este comando completo:

```powershell
cd C:\Users\Yecsa\Documents\ProyectoFinalMovil\movil; $env:EXPO_NO_METRO_LAZY = "1"; npx expo start --clear
```

---

## 📝 Comandos Completos (Copia y Pega)

### Opción A: Comandos Separados (Recomendado para principiantes)

```powershell
# 1. Ir a la carpeta
cd C:\Users\Yecsa\Documents\ProyectoFinalMovil\movil

# 2. Configurar variable
$env:EXPO_NO_METRO_LAZY = "1"

# 3. Verificar (debe mostrar "1")
Write-Host $env:EXPO_NO_METRO_LAZY

# 4. Iniciar Expo
npx expo start --clear
```

### Opción B: Todo en Una Línea

```powershell
cd C:\Users\Yecsa\Documents\ProyectoFinalMovil\movil; $env:EXPO_NO_METRO_LAZY = "1"; npx expo start --clear
```

---

## ✅ ¿Cómo Saber que Funcionó?

Si ves algo como esto, **¡funcionó!** ✅

```
Starting project at C:\Users\Yecsa\Documents\ProyectoFinalMovil\movil
Metro waiting on exp://192.168.x.x:8081
```

O verás un código QR y opciones para abrir en Android/iOS.

---

## ⚠️ IMPORTANTE

### ❌ NO HAGAS ESTO:

```powershell
# ❌ Esto FALLA (falta la variable)
npx expo start
```

### ✅ SIEMPRE HAZ ESTO:

```powershell
# ✅ Esto FUNCIONA (con la variable)
$env:EXPO_NO_METRO_LAZY = "1"
npx expo start --clear
```

---

## 🔄 Si Cierras PowerShell

**IMPORTANTE:** Si cierras la ventana de PowerShell, la variable se pierde.

**Solución:** Vuelve a ejecutar los pasos 2, 3 y 4 cada vez que abras PowerShell.

---

## 💡 Solución Permanente (Opcional)

Si quieres evitar tener que configurar la variable cada vez, puedes usar el archivo `.bat`:

1. Haz doble clic en: `start.bat`

O ejecuta:

```powershell
.\start.bat
```

---

## 🆘 Si Sigue Fallando

1. **Cierra todas las ventanas de PowerShell**
2. **Abre una nueva ventana de PowerShell**
3. **Ejecuta los comandos de nuevo**

---

## 📋 Resumen Rápido

```powershell
# Copia y pega estos 3 comandos (uno por uno):

cd C:\Users\Yecsa\Documents\ProyectoFinalMovil\movil
$env:EXPO_NO_METRO_LAZY = "1"
npx expo start --clear
```

---

**¡Eso es todo!** 🎉

