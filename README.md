# Shadow Ticket Support - Sistema Completo

Sistema completo de gestión de tickets con **Backend PHP + PostgreSQL** y **Frontend móvil React Native (Expo)**.

## 📋 Descripción del Proyecto

**Shadow Ticket Support** es un sistema de gestión de tickets de soporte técnico que permite:
- Autenticación de usuarios con roles (Administrador, Técnico, Estándar)
- Gestión de tickets para técnicos (aceptar/finalizar)
- Panel de administración y usuario estándar
- API RESTful completa en PHP
- Aplicación móvil React Native con Expo

---

## 🏗️ Estructura del Proyecto

```
ProyectoFinalMovil/
├── api/                          # Backend API RESTful
│   ├── login.php                # Endpoint POST /api/login
│   ├── tickets.php              # Endpoints GET/POST /api/tickets/*
│   ├── auth_helper.php          # Helper de autenticación con tokens
│   └── .htaccess                # Configuración de rutas (opcional)
├── movil/                        # Frontend React Native
│   ├── src/
│   │   ├── config/
│   │   │   └── api.js           # Configuración de la API
│   │   ├── contexts/
│   │   │   └── AuthContext.js   # Context API para autenticación
│   │   ├── screens/
│   │   │   ├── LoginScreen.js
│   │   │   ├── AdminScreen.js
│   │   │   ├── TecnicoScreen.js
│   │   │   └── EstandarScreen.js
│   │   └── navigation/
│   │       └── AppNavigator.js  # React Navigation
│   ├── App.js                   # Punto de entrada
│   ├── package.json
│   └── app.json
├── bd.php                        # Conexión a PostgreSQL (Render)
└── [archivos PHP web existentes]
```

---

## 🔧 Configuración del Backend (PHP + PostgreSQL)

### 0. Verificar Extensión PostgreSQL (pgsql)

**IMPORTANTE:** Antes de continuar, asegúrate de que la extensión `pgsql` esté habilitada en PHP.

**Verificación rápida:**
```bash
php verificar_pgsql.php
# o
php test_pgsql.php
```

**Si la extensión NO está habilitada:**
- Consulta el archivo `INSTRUCCIONES_PGSQL.md` para instrucciones detalladas
- En Windows: Edita `php.ini` y quita el `;` de `;extension=pgsql`
- En Linux: `sudo apt-get install php-pgsql`
- En macOS: `brew install php-pgsql`
- **Reinicia tu servidor web** después de habilitar la extensión

### 1. Credenciales de Base de Datos

Las credenciales de PostgreSQL están configuradas en `bd.php` para conectarse a Render:

```php
// Credenciales de Render PostgreSQL
$host = "dpg-d4evo8i4d50c73e4emlg-a.oregon-postgres.render.com";
$port = "5432";
$user = "ivan";
$password = "lRQPy6PBPUaXTQOHpTqe5ZvbEkLKYGqS";
$database = "shadowticketsupport_jqr2";
```

### 2. Endpoints de la API

#### **POST /api/login**
Autentica usuarios y devuelve token de sesión.

**Petición:**
```json
{
  "correo": "usuario@ejemplo.com",
  "pass": "contraseña"
}
```

**Respuesta Exitosa:**
```json
{
  "success": true,
  "user": {
    "Id_usuario": 123,
    "Nombre": "Juan",
    "Id_rol": 2,
    "token": "simulated_jwt_token"
  }
}
```

#### **GET /api/tickets/tecnico**
Obtiene la lista de tickets abiertos (Estado=FALSE). Requiere token de autenticación en headers.

**Headers:**
```
Authorization: Bearer {token}
X-Auth-Token: {token}
```

**Respuesta:**
```json
[
  {
    "Id_Ticket": 101,
    "Titulo": "Problema de red en oficina",
    "Id_Tecnico": null,
    "CreadorNombre": "Pedro Gómez",
    "Prioridad": "Alta"
  }
]
```

#### **POST /api/tickets/gestionar**
Permite al técnico aceptar o finalizar un ticket.

**Headers:**
```
Authorization: Bearer {token}
X-Auth-Token: {token}
Content-Type: application/json
```

**Body:**
```json
{
  "id_ticket": 101,
  "id_tecnico": 123,
  "accion": "aceptar"  // o "finalizar"
}
```

### 3. Configuración del Servidor Web

Asegúrate de que tu servidor web (Apache/Nginx) tenga habilitado:
- PHP con extensión `pgsql` (PostgreSQL)
- Mod_rewrite (Apache) o configuración equivalente (Nginx) para rutas RESTful
- Headers CORS habilitados

---

## 📱 Configuración del Frontend (React Native con Expo)

### 1. Instalación de Dependencias

```bash
cd movil
npm install
# o
yarn install
```

### 2. Configurar la URL de la API

Edita el archivo `movil/src/config/api.js` y actualiza la URL base de tu servidor:

```javascript
// Para desarrollo local, usar la IP de tu máquina
// Ejemplo: http://192.168.1.100/ProyectoFinalMovil/api
export const API_BASE_URL = 'http://tu-servidor.com/ProyectoFinalMovil/api';
```

**Notas importantes:**
- **Emulador Android**: Usa `http://10.0.2.2/ProyectoFinalMovil/api` (IP especial del emulador)
- **Dispositivo físico**: Usa la IP local de tu PC (ej: `http://192.168.1.100/ProyectoFinalMovil/api`)
- **Producción**: Usa la URL de tu servidor desplegado

### 3. Ejecutar la Aplicación

```bash
cd movil

# Iniciar Expo
npm start
# o
expo start

# Para Android
npm run android
# o
expo start --android

# Para iOS
npm run ios
# o
expo start --ios
```

### 4. Estructura de Navegación

La aplicación redirige automáticamente según el `Id_rol` del usuario:

- **Id_rol = 1** → `AdminScreen` (Administrador)
- **Id_rol = 2** → `TecnicoScreen` (Técnico)
- **Id_rol = 3** → `EstandarScreen` (Usuario Estándar)

---

## 🎨 Diseño Visual

El diseño sigue un esquema oscuro y minimalista:

- **Fondo**: Gradiente `#0f2027 → #203a43 → #2c5364`
- **Contenedores**: `#1a2c34` con bordes `#2c5364`
- **Inputs**: Fondo `#0d1a20`, borde `#2c5364`, focus `#4CAF50`
- **Botones**: `#2c5364` (hover: `#203a43`)
- **Texto**: `#f8f9fa` (blanco humo)

---

## 🔐 Autenticación y Roles

### Sistema de Tokens

El sistema usa tokens simulados (base64) para autenticación. En producción, se recomienda usar JWT real.

### Roles y Permisos

1. **Administrador (Id_rol = 1)**
   - Acceso al panel de administración
   - Funcionalidades pendientes de implementación

2. **Técnico (Id_rol = 2)**
   - Ver tickets abiertos
   - Aceptar tickets (si `Id_Tecnico` es `null`)
   - Finalizar tickets (si el ticket está asignado a él)

3. **Estándar (Id_rol = 3)**
   - Crear nuevos tickets
   - Ver sus propios tickets

---

## 📝 Funcionalidades Implementadas

### ✅ Backend (PHP)

- [x] Conexión a PostgreSQL en Render
- [x] Endpoint de login con generación de tokens
- [x] Endpoint para obtener tickets abiertos
- [x] Endpoint para gestionar tickets (aceptar/finalizar)
- [x] Validación de tokens en headers
- [x] CORS habilitado

### ✅ Frontend (React Native)

- [x] Autenticación con Context API
- [x] LoginScreen con validación
- [x] TecnicoScreen con FlatList de tickets
- [x] Botones dinámicos según estado del ticket
- [x] AdminScreen y EstandarScreen (placeholders)
- [x] Navegación automática según rol
- [x] Logout en todas las pantallas
- [x] Pull-to-refresh en lista de tickets

---

## 🐛 Solución de Problemas

### Error: "Error de conexión" en la app móvil
- Verifica que el servidor web esté corriendo
- Verifica que la URL en `api.js` sea correcta
- Para emulador Android, usa `10.0.2.2` en lugar de `localhost`
- Para dispositivo físico, usa la IP local de tu PC

### Error: "Token de autenticación inválido"
- Verifica que los headers de autenticación se envíen correctamente
- Revisa que el token se guarde en AsyncStorage después del login

### Error: "Endpoint no encontrado"
- Verifica la configuración de rutas en `.htaccess` (Apache)
- Asegúrate de que el archivo `tickets.php` detecte correctamente el tipo de petición
- Usa parámetros GET: `?tipo=tecnico` para tickets de técnico

### Error de conexión a PostgreSQL
- Verifica las credenciales en `bd.php`
- **Asegúrate de que la extensión `pgsql` esté instalada en PHP** (ver `INSTRUCCIONES_PGSQL.md`)
- Ejecuta `php verificar_pgsql.php` o `php test_pgsql.php` para verificar la extensión
- Verifica que el servidor PostgreSQL de Render esté accesible

---

## 🚀 Próximas Mejoras

- [ ] Implementar JWT real en lugar de tokens simulados
- [ ] Añadir funcionalidad completa de creación de tickets en móvil
- [ ] Implementar panel completo de administración
- [ ] Añadir notificaciones push
- [ ] Mejorar manejo de errores y validaciones
- [ ] Añadir tests unitarios

---

## 📄 Licencia

Este proyecto es de uso educativo/demostrativo.

---

## 👨‍💻 Desarrollo

Proyecto desarrollado con:
- **Backend**: PHP 7.4+ con PostgreSQL
- **Frontend**: React Native con Expo SDK 50
- **Navegación**: React Navigation v6
- **Estado**: Context API
- **HTTP**: Axios

---

**¡Listo para usar!** 🎉


