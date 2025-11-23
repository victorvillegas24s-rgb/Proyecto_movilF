# Instrucciones para la Aplicación Móvil Shadow Ticket Support

## 📋 Resumen del Proyecto

Se ha creado una aplicación Flutter completa que se conecta a la base de datos PostgreSQL existente a través de una API PHP. La aplicación replica el diseño oscuro de la versión web.

## 📁 Archivos Creados

1. **movil_api.php** - API REST en PHP ubicada en la raíz del proyecto
2. **shadowsupport/lib/main.dart** - Aplicación Flutter completa
3. **shadowsupport/pubspec.yaml** - Actualizado con dependencias necesarias

## 🔧 Configuración Inicial

### 1. Instalar Dependencias de Flutter

Navega a la carpeta `shadowsupport` y ejecuta:

```bash
cd shadowsupport
flutter pub get
```

Esto instalará las dependencias:
- `http: ^1.1.0` - Para peticiones HTTP
- `intl: ^0.19.0` - Para formateo de fechas

### 2. Configurar la URL de la API

Si tu servidor web está configurado de manera diferente, edita la constante `API_BASE_URL` en `shadowsupport/lib/main.dart`:

```dart
// Línea 7 del archivo main.dart
const String API_BASE_URL = 'http://10.0.2.2/ShadowTicket/movil_api.php';
```

**Nota importante:**
- `10.0.2.2` es la IP especial que el emulador de Android usa para referirse al localhost de tu PC
- Si estás usando un servidor diferente o un puerto específico, ajusta la URL
- Para dispositivos físicos, usa la IP local de tu PC (ej: `http://192.168.1.100/ShadowTicket/movil_api.php`)

### 3. Verificar el Servidor Web

Asegúrate de que:
- Tu servidor web (Apache/Nginx) esté corriendo
- PHP esté habilitado con la extensión `pgsql` (PostgreSQL)
- El archivo `movil_api.php` esté accesible desde el navegador
- La base de datos PostgreSQL esté corriendo

## 🚀 Ejecutar la Aplicación

### En el Emulador de Android:

1. Abre Android Studio
2. Inicia un emulador de Android
3. En la terminal, desde la carpeta `shadowsupport`, ejecuta:

```bash
flutter run
```

O desde Android Studio, simplemente presiona el botón "Run"

## 📱 Funcionalidades Implementadas

### 1. Pantalla de Login
- Diseño idéntico a la web con gradiente oscuro
- Validación de campos
- Mensajes de error con SnackBar
- Solo permite acceso a usuarios con rol 3 (Estándar)

### 2. Dashboard con Pestañas
- **Pestaña "Crear Ticket"**: Formulario para crear nuevos tickets
- **Pestaña "Mis Tickets"**: Lista de tickets del usuario usando `ListView.builder`

### 3. Lista de Tickets
- Muestra título, fecha, descripción (extracto), prioridad
- Indicador de estado: "ABIERTO" (rojo) o "FINALIZADO" (verde)
- Pull-to-refresh para actualizar la lista
- Manejo de estados vacíos y errores

## 🎨 Diseño Visual

La aplicación replica exactamente el diseño oscuro de la web:

- **Fondo**: Gradiente lineal `#0f2027 → #203a43 → #2c5364` (135deg)
- **Contenedores**: `#1a2c34` con bordes `#2c5364`
- **Inputs**: Fondo `#0d1a20`, borde `#2c5364`, focus `#4CAF50`
- **Botones**: `#2c5364` (hover: `#203a43`)
- **Texto**: `#f8f9fa` (blanco humo)

## 🔌 Endpoints de la API

La API (`movil_api.php`) maneja tres operaciones:

1. **Login** (`opcion=login`)
   - POST: `correo`, `pass`
   - Retorna: `{success, id_usuario, nombre, rol}`

2. **Crear Ticket** (`opcion=crear_ticket`)
   - POST: `id_usuario`, `titulo`, `descripcion`
   - Retorna: `{success, message}`

3. **Listar Tickets** (`opcion=listar_tickets`)
   - GET/POST: `id_usuario`
   - Retorna: `{success, tickets: [...]}`

## ⚠️ Solución de Problemas

### Error: "Error de conexión"
- Verifica que el servidor web esté corriendo
- Verifica que `movil_api.php` sea accesible
- Revisa la URL en `API_BASE_URL`
- Para emulador, asegúrate de usar `10.0.2.2`

### Error: "Acceso denegado"
- Solo usuarios con `Id_rol = 3` pueden usar la app móvil
- Verifica las credenciales en la base de datos

### Error: "Error al crear el ticket"
- Verifica que la base de datos PostgreSQL esté corriendo
- Revisa los logs del servidor PHP
- Verifica que la tabla `Ticket` tenga la estructura correcta

### La aplicación no se conecta
- Verifica permisos de internet en `AndroidManifest.xml`
- Para Android, asegúrate de tener:
  ```xml
  <uses-permission android:name="android.permission.INTERNET" />
  ```

## 📝 Notas Adicionales

- La aplicación está diseñada específicamente para usuarios Estándar (rol 3)
- Los tickets se crean con estado inicial 'FALSE' (abierto) y prioridad por defecto 1
- Las fechas se formatean como `dd/MM/yyyy HH:mm`
- La descripción en la lista se trunca a 100 caracteres

## ✅ Checklist de Verificación

- [ ] Dependencias instaladas (`flutter pub get`)
- [ ] Servidor web corriendo
- [ ] PostgreSQL corriendo
- [ ] `movil_api.php` accesible
- [ ] URL de API configurada correctamente
- [ ] Emulador de Android iniciado
- [ ] Usuario de prueba con rol 3 creado en la base de datos

---

**Desarrollado para Shadow Ticket Support - Proyecto Universitario**

