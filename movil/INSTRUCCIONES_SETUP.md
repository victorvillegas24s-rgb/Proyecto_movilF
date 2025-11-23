# Instrucciones de Configuración - Shadow Ticket Support Mobile

## 📱 Configuración Inicial del Proyecto React Native

### 1. Instalación de Dependencias

Primero, asegúrate de tener instalado:
- **Node.js** (v16 o superior)
- **npm** o **yarn**
- **Expo CLI** (opcional, puede usar npx)

Luego, instala las dependencias del proyecto:

```bash
cd movil
npm install
```

### 2. Configurar la URL de la API

**IMPORTANTE:** Edita el archivo `src/config/api.js` y actualiza la URL base de tu servidor.

```javascript
// Para desarrollo local:
export const API_BASE_URL = 'http://192.168.1.100/ProyectoFinalMovil/api';

// Para emulador Android:
export const API_BASE_URL = 'http://10.0.2.2/ProyectoFinalMovil/api';

// Para producción:
export const API_BASE_URL = 'https://tu-servidor.com/api';
```

**Guía de URLs según el entorno:**

- **Emulador Android**: `http://10.0.2.2/ProyectoFinalMovil/api`
  - `10.0.2.2` es la IP especial que el emulador usa para referirse al localhost de tu PC
  
- **Dispositivo físico (misma red WiFi)**:
  1. Encuentra la IP local de tu PC:
     - Windows: `ipconfig` en CMD
     - Mac/Linux: `ifconfig` en Terminal
  2. Usa esa IP: `http://192.168.1.XXX/ProyectoFinalMovil/api`
  
- **Servidor en producción**: Usa la URL completa de tu servidor

### 3. Ejecutar la Aplicación

#### Opción A: Con Expo CLI (si está instalado globalmente)

```bash
cd movil
expo start
```

#### Opción B: Con npx (recomendado)

```bash
cd movil
npx expo start
```

Esto abrirá el **Expo Dev Tools** en tu navegador. Puedes:

- Presionar `a` para abrir en Android
- Presionar `i` para abrir en iOS
- Escanear el código QR con la app Expo Go en tu dispositivo físico

### 4. Probar la Aplicación

1. **Instala Expo Go** en tu dispositivo móvil:
   - Android: [Google Play Store](https://play.google.com/store/apps/details?id=host.exp.exponent)
   - iOS: [App Store](https://apps.apple.com/app/expo-go/id982107779)

2. **Conecta tu dispositivo**:
   - Asegúrate de que tu PC y móvil estén en la misma red WiFi
   - Escanea el código QR desde Expo Go

3. **Prueba el login**:
   - Usa las credenciales de un usuario en tu base de datos
   - El sistema redirigirá automáticamente según el rol:
     - **Rol 1** → Pantalla de Administrador
     - **Rol 2** → Pantalla de Técnico (con lista de tickets)
     - **Rol 3** → Pantalla de Usuario Estándar

### 5. Solución de Problemas Comunes

#### Error: "Network request failed"
- Verifica que la URL en `api.js` sea correcta
- Asegúrate de que el servidor web esté corriendo
- Para dispositivo físico, verifica que esté en la misma red WiFi
- Verifica que el firewall no bloquee las conexiones

#### Error: "Unable to resolve module"
- Ejecuta `npm install` nuevamente
- Limpia la caché: `npx expo start -c`
- Elimina `node_modules` y reinstala: `rm -rf node_modules && npm install`

#### Error: "Error de conexión" en login
- Verifica que el servidor PHP esté corriendo
- Verifica que la API responda: visita `http://tu-servidor/api/login.php` en el navegador
- Revisa los logs del servidor PHP

#### La app no se conecta al servidor
- **Para emulador Android**: Asegúrate de usar `10.0.2.2` en lugar de `localhost`
- **Para dispositivo físico**: Usa la IP local de tu PC (ej: `192.168.1.100`)
- Verifica que tu servidor web permita conexiones desde otros dispositivos

### 6. Estructura del Proyecto Móvil

```
movil/
├── src/
│   ├── config/
│   │   └── api.js           # ⚠️ CONFIGURAR AQUÍ LA URL
│   ├── contexts/
│   │   └── AuthContext.js   # Gestión de autenticación
│   ├── screens/
│   │   ├── LoginScreen.js
│   │   ├── AdminScreen.js
│   │   ├── TecnicoScreen.js
│   │   └── EstandarScreen.js
│   └── navigation/
│       └── AppNavigator.js  # Navegación por roles
├── App.js                   # Punto de entrada
├── package.json
└── app.json
```

### 7. Comandos Útiles

```bash
# Iniciar el servidor de desarrollo
npx expo start

# Limpiar caché y reiniciar
npx expo start -c

# Ejecutar en Android
npx expo start --android

# Ejecutar en iOS
npx expo start --ios

# Ver logs
npx expo start --tunnel
```

---

## 🔧 Configuración del Backend (Recordatorio)

Asegúrate de que tu servidor web tenga:

1. **PHP** con extensión `pgsql` habilitada
2. **CORS** configurado (ya está en los archivos PHP)
3. **Mod_rewrite** habilitado (Apache) para rutas RESTful

Verifica que los endpoints funcionen:
- `POST /api/login.php`
- `GET /api/tickets.php?tipo=tecnico`
- `POST /api/tickets.php`

---

¡Listo para desarrollar! 🚀


