// metro.config.js
// Configuración personalizada de Metro para evitar el error de "node:sea" en Windows

const { getDefaultConfig } = require('expo/metro-config');

const config = getDefaultConfig(__dirname);

// Configuración para evitar el problema de node:sea en Windows
config.resolver = {
  ...config.resolver,
  // Deshabilitar la carga diferida de externals que causa el problema
  unstable_enablePackageExports: false,
};

// Configuración del servidor para evitar crear directorios problemáticos
config.server = {
  ...config.server,
  enhanceMiddleware: (middleware) => {
    return middleware;
  },
};

module.exports = config;

