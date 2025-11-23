/**
 * Configuración de la API
 * IMPORTANTE: Actualiza esta URL con la dirección de tu servidor
 * 
 * Para desarrollo local:
 * - Emulador Android: usar http://10.0.2.2/shadowticketsupport/api
 * - Dispositivo físico: usar la IP local de tu PC (ej: http://192.168.1.100/shadowticketsupport/api)
 * - Cambiar 'shadowticketsupport' por el nombre de tu carpeta del proyecto en el servidor
 */

// URL base de la API
// TODO: Cambiar esta URL por la dirección real de tu servidor PHP
// IMPORTANTE: Debe incluir el protocolo http:// o https://
// Ejemplo para servidor local en puerto 8000: http://localhost:8000/shadowticketsupport/api
// Ejemplo para servidor en red: http://192.168.1.100/shadowticketsupport/api
export const API_BASE_URL = 'http://localhost:8000/shadowticketsupport/api';

// Endpoints
export const API_ENDPOINTS = {
  LOGIN: `${API_BASE_URL}/login.php`,
  TICKETS_TECNICO: `${API_BASE_URL}/tickets.php?tipo=tecnico`,
  TICKETS_GESTIONAR: `${API_BASE_URL}/tickets.php`,
};

export default API_BASE_URL;


