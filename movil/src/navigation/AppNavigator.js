import React, { useEffect, useRef } from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import LoginScreen from '../screens/LoginScreen';
import AdminScreen from '../screens/AdminScreen';
import TecnicoScreen from '../screens/TecnicoScreen';
import EstandarScreen from '../screens/EstandarScreen';
import { useAuth } from '../contexts/AuthContext';
import { View, ActivityIndicator, StyleSheet, Text } from 'react-native';

const Stack = createNativeStackNavigator();

const AppNavigator = () => {
  const { user, loading } = useAuth();
  const navigationRef = useRef(null);

  // Determinar la pantalla inicial según el estado de autenticación
  const getInitialRoute = () => {
    if (!user) {
      return 'Login';
    }
    if (user.Id_rol === 1) return 'Admin';
    if (user.Id_rol === 2) return 'Tecnico';
    if (user.Id_rol === 3) return 'Estandar';
    return 'Login';
  };

  // Navegar automáticamente cuando cambie el usuario
  useEffect(() => {
    if (!loading && navigationRef.current) {
      const routeName = getInitialRoute();
      navigationRef.current.reset({
        index: 0,
        routes: [{ name: routeName }],
      });
    }
  }, [user, loading]);

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#2c5364" />
        <Text style={styles.loadingText}>Cargando...</Text>
      </View>
    );
  }

  return (
    <NavigationContainer ref={navigationRef}>
      <Stack.Navigator 
        initialRouteName={getInitialRoute()}
        screenOptions={{ headerShown: false }}
      >
        <Stack.Screen name="Login" component={LoginScreen} />
        <Stack.Screen name="Admin" component={AdminScreen} />
        <Stack.Screen name="Tecnico" component={TecnicoScreen} />
        <Stack.Screen name="Estandar" component={EstandarScreen} />
      </Stack.Navigator>
    </NavigationContainer>
  );
};

const styles = StyleSheet.create({
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#0f2027',
  },
  loadingText: {
    color: '#f8f9fa',
    marginTop: 16,
    fontSize: 16,
  },
});

export default AppNavigator;


