# 🔧 Instrucciones para Habilitar Extensión PostgreSQL (pgsql) en PHP

## 📋 Verificación Rápida

Ejecuta el script de verificación que se ha creado:

```bash
php verificar_pgsql.php
```

O desde el navegador:
```
http://localhost:8000/verificar_pgsql.php
```

---

## 🪟 Windows

### Método 1: Editar php.ini

1. **Encontrar el archivo php.ini:**
   ```bash
   php --ini
   ```
   Esto mostrará la ruta del archivo php.ini cargado.

2. **Abrir php.ini** con un editor de texto (como Notepad++ o Visual Studio Code)

3. **Buscar la línea:**
   ```ini
   ;extension=pgsql
   ```

4. **Quitar el punto y coma (;) al inicio:**
   ```ini
   extension=pgsql
   ```

5. **Si no existe, agregar al final del archivo:**
   ```ini
   extension=pgsql
   extension=pdo_pgsql
   ```

6. **Verificar que exista el archivo DLL:**
   - Busca en la carpeta `ext/` de tu instalación de PHP
   - Debe existir `php_pgsql.dll` y `php_pdo_pgsql.dll`
   - Si no existen, descárgalos desde [php.net](https://windows.php.net/download/)

7. **Reiniciar el servidor web:**
   - Si usas XAMPP: Reinicia Apache desde el panel de control
   - Si usas WAMP: Reinicia todos los servicios
   - Si usas IIS: Reinicia el servicio IIS

### Método 2: Usando XAMPP

1. Abre el panel de control de XAMPP
2. Haz clic en "Config" junto a Apache
3. Selecciona "PHP (php.ini)"
4. Busca `;extension=pgsql`
5. Quita el `;` al inicio
6. Guarda y reinicia Apache

### Método 3: Verificar desde línea de comandos

```bash
php -m | findstr pgsql
```

Si aparece `pgsql` y `pdo_pgsql`, la extensión está cargada.

---

## 🐧 Linux (Ubuntu/Debian)

### Instalar extensión pgsql:

```bash
# Actualizar repositorios
sudo apt-get update

# Instalar extensión PHP para PostgreSQL
sudo apt-get install php-pgsql

# O si usas una versión específica de PHP:
sudo apt-get install php7.4-pgsql
# o
sudo apt-get install php8.1-pgsql
```

### Reiniciar servidor web:

```bash
# Para Apache
sudo systemctl restart apache2

# Para Nginx con PHP-FPM
sudo systemctl restart php-fpm
sudo systemctl restart nginx
```

### Verificar instalación:

```bash
php -m | grep pgsql
```

Deberías ver:
```
pgsql
pdo_pgsql
```

---

## 🍎 macOS

### Usando Homebrew:

```bash
# Instalar extensión pgsql
brew install php-pgsql

# O si usas una versión específica:
brew install php@7.4-pgsql
```

### Reiniciar PHP:

```bash
brew services restart php
# o
brew services restart php@7.4
```

### Verificar:

```bash
php -m | grep pgsql
```

---

## 🐳 Docker

Si usas Docker, el `Dockerfile` ya incluye la instalación:

```dockerfile
FROM php:7.4-apache

RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql
```

Solo necesitas reconstruir la imagen:

```bash
docker-compose build
docker-compose up -d
```

---

## ✅ Verificación Final

### 1. Desde línea de comandos:

```bash
php -r "echo extension_loaded('pgsql') ? 'pgsql cargada ✓' : 'pgsql NO cargada ✗';"
```

### 2. Crear un archivo de prueba (test_pgsql.php):

```php
<?php
if (extension_loaded('pgsql')) {
    echo "✓ Extensión pgsql está habilitada\n";
    echo "Versión: " . phpversion('pgsql') . "\n";
} else {
    echo "✗ Extensión pgsql NO está habilitada\n";
    echo "Sigue las instrucciones en INSTRUCCIONES_PGSQL.md\n";
}
?>
```

Ejecutar:
```bash
php test_pgsql.php
```

### 3. Probar conexión real:

Ejecuta el script `verificar_pgsql.php` que incluye una prueba de conexión a tu base de datos.

---

## 🐛 Solución de Problemas

### Error: "Call to undefined function pg_connect()"

**Causa:** La extensión pgsql no está cargada.

**Solución:**
1. Verifica que `extension=pgsql` esté sin `;` en php.ini
2. Verifica que el archivo DLL exista en la carpeta `ext/`
3. Reinicia el servidor web
4. Verifica con `php -m | grep pgsql`

### Error: "Unable to load dynamic library 'pgsql'"

**Causa:** Falta la librería libpq o el DLL.

**Solución (Windows):**
- Descarga los DLLs desde [php.net](https://windows.php.net/download/)
- Colócalos en la carpeta `ext/` de PHP

**Solución (Linux):**
```bash
sudo apt-get install libpq-dev
```

### Error: "Fatal error: Uncaught Error: Call to undefined function"

**Causa:** PHP no puede encontrar las funciones de PostgreSQL.

**Solución:**
1. Verifica que la extensión esté habilitada: `php -m | grep pgsql`
2. Verifica que el archivo php.ini correcto esté siendo usado: `php --ini`
3. Reinicia el servidor web completamente

---

## 📝 Notas Importantes

- **Siempre reinicia el servidor web** después de modificar php.ini
- En **Windows**, asegúrate de usar el php.ini correcto (puede haber varios)
- En **Linux**, puede que necesites instalar `libpq-dev` antes de instalar la extensión PHP
- La extensión `pdo_pgsql` también es útil si usas PDO en lugar de funciones nativas

---

## 🔗 Recursos Adicionales

- [Documentación oficial de PHP PostgreSQL](https://www.php.net/manual/es/book.pgsql.php)
- [Instalación en Windows](https://www.php.net/manual/es/pgsql.installation.php)
- [Instalación en Linux](https://www.php.net/manual/es/pgsql.installation.php)

---

**¡Una vez habilitada la extensión, ejecuta `verificar_pgsql.php` para confirmar que todo funciona correctamente!**




