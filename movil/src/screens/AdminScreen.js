import React from 'react';
import { View, Text, TouchableOpacity, StyleSheet, Alert } from 'react-native';
import { useAuth } from '../contexts/AuthContext';
import { StatusBar } from 'expo-status-bar';

const AdminScreen = ({ navigation }) => {
  const { user, logout } = useAuth();

  const handleLogout = () => {
    Alert.alert('Cerrar sesión', '¿Estás seguro de que deseas cerrar sesión?', [
      { text: 'Cancelar', style: 'cancel' },
      {
        text: 'Cerrar sesión',
        style: 'destructive',
        onPress: async () => {
          await logout();
          // La navegación se manejará automáticamente en App.js
        },
      },
    ]);
  };

  return (
    <View style={styles.container}>
      <StatusBar style="light" />
      
      <View style={styles.content}>
        <Text style={styles.title}>Panel de Administrador</Text>
        <Text style={styles.welcome}>Bienvenido, {user?.Nombre || 'Administrador'}</Text>
        <Text style={styles.description}>
          Esta es la pantalla para usuarios con rol de Administrador.
        </Text>
        <Text style={styles.description}>
          Funcionalidades adicionales estarán disponibles próximamente.
        </Text>

        <TouchableOpacity style={styles.logoutButton} onPress={handleLogout}>
          <Text style={styles.logoutButtonText}>Cerrar Sesión</Text>
        </TouchableOpacity>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#0f2027',
  },
  content: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: 30,
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#f8f9fa',
    marginBottom: 20,
    textAlign: 'center',
  },
  welcome: {
    fontSize: 20,
    color: '#adb5bd',
    marginBottom: 30,
    textAlign: 'center',
  },
  description: {
    fontSize: 16,
    color: '#adb5bd',
    textAlign: 'center',
    marginBottom: 15,
    lineHeight: 24,
  },
  logoutButton: {
    marginTop: 40,
    paddingVertical: 14,
    paddingHorizontal: 30,
    backgroundColor: '#dc3545',
    borderRadius: 8,
  },
  logoutButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: '600',
  },
});

export default AdminScreen;


