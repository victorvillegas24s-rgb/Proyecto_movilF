import React, { createContext, useState, useContext, useEffect } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';
import axios from 'axios';
import { API_ENDPOINTS } from '../config/api';

const AuthContext = createContext({});

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth debe ser usado dentro de un AuthProvider');
  }
  return context;
};

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(null);
  const [loading, setLoading] = useState(true);

  // Cargar datos de autenticación al iniciar
  useEffect(() => {
    loadStoredAuth();
  }, []);

  const loadStoredAuth = async () => {
    try {
      const storedUser = await AsyncStorage.getItem('user');
      const storedToken = await AsyncStorage.getItem('token');
      
      if (storedUser && storedToken) {
        const parsedUser = JSON.parse(storedUser);
        
        // CRÍTICO: Convertir strings a tipos correctos si vienen de AsyncStorage
        if (parsedUser && typeof parsedUser === 'object') {
          // Convertir "true"/"false" string a boolean
          Object.keys(parsedUser).forEach(key => {
            if (parsedUser[key] === "true") {
              parsedUser[key] = true;
            } else if (parsedUser[key] === "false") {
              parsedUser[key] = false;
            }
          });
          
          // Asegurar que Id_rol e Id_usuario sean números
          if (parsedUser.Id_rol !== undefined) {
            parsedUser.Id_rol = parseInt(parsedUser.Id_rol, 10);
          }
          if (parsedUser.Id_usuario !== undefined) {
            parsedUser.Id_usuario = parseInt(parsedUser.Id_usuario, 10);
          }
        }
        
        setUser(parsedUser);
        setToken(storedToken);
      } else {
        // No hay sesión guardada, asegurar estado limpio
        setUser(null);
        setToken(null);
      }
    } catch (error) {
      console.error('Error al cargar autenticación:', error);
    } finally {
      setLoading(false);
    }
  };

  const login = async (correo, pass) => {
    try {
      const response = await axios.post(API_ENDPOINTS.LOGIN, {
        correo,
        pass,
      });

      if (response.data.success && response.data.user) {
        const { user: userData, token: userToken } = response.data;
        
        // Asegurar que Id_rol e Id_usuario sean números
        if (userData.Id_rol !== undefined) {
          userData.Id_rol = parseInt(userData.Id_rol, 10);
        }
        if (userData.Id_usuario !== undefined) {
          userData.Id_usuario = parseInt(userData.Id_usuario, 10);
        }
        
        // Guardar en estado
        setUser(userData);
        setToken(userToken);
        
        // Guardar en AsyncStorage
        await AsyncStorage.setItem('user', JSON.stringify(userData));
        await AsyncStorage.setItem('token', userToken);
        
        return { success: true, user: userData };
      } else {
        return { success: false, message: response.data.message || 'Error en el login' };
      }
    } catch (error) {
      console.error('Error en login:', error);
      return {
        success: false,
        message: error.response?.data?.message || 'Error de conexión. Verifica tu servidor.',
      };
    }
  };

  const logout = async () => {
    try {
      await AsyncStorage.removeItem('user');
      await AsyncStorage.removeItem('token');
      setUser(null);
      setToken(null);
    } catch (error) {
      console.error('Error al cerrar sesión:', error);
    }
  };

  const getAuthHeaders = () => {
    if (!token) return {};
    return {
      'Authorization': `Bearer ${token}`,
      'X-Auth-Token': token,
      'Content-Type': 'application/json',
    };
  };

  const value = {
    user,
    token,
    loading,
    login,
    logout,
    isAuthenticated: !!user && !!token,
    getAuthHeaders,
  };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};


