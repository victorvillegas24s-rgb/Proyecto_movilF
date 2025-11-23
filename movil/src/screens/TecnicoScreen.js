import React, { useState, useEffect, useCallback } from 'react';
import {
  View,
  Text,
  FlatList,
  TouchableOpacity,
  StyleSheet,
  RefreshControl,
  Alert,
  ActivityIndicator,
  ScrollView,
} from 'react-native';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons } from '@expo/vector-icons';
import { useAuth } from '../contexts/AuthContext';
import { StatusBar } from 'expo-status-bar';
import axios from 'axios';
import { API_ENDPOINTS } from '../config/api';

const TecnicoScreen = ({ navigation }) => {
  const [tickets, setTickets] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const { user, logout, getAuthHeaders } = useAuth();

  const loadTickets = useCallback(async () => {
    try {
      const headers = getAuthHeaders();
      const response = await axios.get(API_ENDPOINTS.TICKETS_TECNICO, { headers });

      if (Array.isArray(response.data)) {
        setTickets(response.data);
      } else {
        console.error('Respuesta inválida de la API:', response.data);
        Alert.alert('Error', 'Error al cargar los tickets');
      }
    } catch (error) {
      console.error('Error al cargar tickets:', error);
      Alert.alert(
        'Error',
        error.response?.data?.message || 'Error de conexión. Verifica tu servidor.'
      );
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  }, [getAuthHeaders]);

  useEffect(() => {
    loadTickets();
  }, [loadTickets]);

  const onRefresh = useCallback(() => {
    setRefreshing(true);
    loadTickets();
  }, [loadTickets]);

  const handleAction = async (ticket, action) => {
    if (!user) return;

    const confirmMessage =
      action === 'aceptar'
        ? '¿Deseas aceptar este ticket?'
        : '¿Deseas finalizar este ticket?';

    Alert.alert('Confirmar acción', confirmMessage, [
      { text: 'Cancelar', style: 'cancel' },
      {
        text: 'Confirmar',
        onPress: async () => {
          try {
            const headers = getAuthHeaders();
            const response = await axios.post(
              API_ENDPOINTS.TICKETS_GESTIONAR,
              {
                id_ticket: ticket.Id_Ticket,
                id_tecnico: user.Id_usuario,
                accion: action,
              },
              { headers }
            );

            if (response.data.success) {
              Alert.alert('Éxito', response.data.message || 'Acción realizada correctamente');
              loadTickets(); // Recargar la lista
            } else {
              Alert.alert('Error', response.data.message || 'Error al realizar la acción');
            }
          } catch (error) {
            console.error('Error al gestionar ticket:', error);
            Alert.alert(
              'Error',
              error.response?.data?.message || 'Error de conexión. Verifica tu servidor.'
            );
          }
        },
      },
    ]);
  };

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

  const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    try {
      const date = new Date(dateString);
      return date.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
      });
    } catch (error) {
      return dateString;
    }
  };

  const renderTicket = ({ item }) => {
    const isAssigned = item.Id_Tecnico !== null;
    const isMyTicket = item.Id_Tecnico === user?.Id_usuario;
    const canAccept = !isAssigned;
    const canFinalize = isMyTicket;

    return (
      <View style={styles.ticketCard}>
        <View style={styles.ticketHeader}>
          <View style={styles.ticketIdContainer}>
            <Text style={styles.ticketId}>Ticket ID: ST-{item.Id_Ticket.toString().padStart(7, '0')}</Text>
          </View>
          <View style={styles.badgesContainer}>
            <View
              style={[
                styles.statusBadge,
                isAssigned && isMyTicket && styles.statusInProcess,
                !isAssigned && styles.statusPending,
                isAssigned && !isMyTicket && styles.statusAssigned,
              ]}
            >
              <Text style={styles.statusText}>
                {isAssigned && isMyTicket ? 'En proceso' : !isAssigned ? 'Pendiente' : 'Asignado'}
              </Text>
            </View>
            <View
              style={[
                styles.priorityBadge,
                item.Prioridad === 'Alta' && styles.priorityHigh,
                item.Prioridad === 'Media' && styles.priorityMedium,
                item.Prioridad === 'Baja' && styles.priorityLow,
              ]}
            >
              <Text style={styles.priorityText}>{item.Prioridad}</Text>
            </View>
          </View>
        </View>

        <Text style={styles.ticketTitle}>{item.Titulo}</Text>

        <View style={styles.ticketInfo}>
          <View style={styles.infoRow}>
            <Ionicons name="person-outline" size={16} color="#adb5bd" />
            <Text style={styles.infoText}>Creador: {item.CreadorNombre || 'N/A'}</Text>
          </View>
          <View style={styles.infoRow}>
            <Ionicons name="calendar-outline" size={16} color="#adb5bd" />
            <Text style={styles.infoText}>Fecha: {formatDate(item.Fecha_Inicio)}</Text>
          </View>
        </View>

        <View style={styles.ticketActions}>
          {canAccept && (
            <TouchableOpacity
              style={[styles.actionButton, styles.acceptButton]}
              onPress={() => handleAction(item, 'aceptar')}
            >
              <Ionicons name="checkmark-circle-outline" size={20} color="#fff" style={styles.buttonIcon} />
              <Text style={styles.actionButtonText}>Aceptar Ticket</Text>
            </TouchableOpacity>
          )}

          {canFinalize && (
            <TouchableOpacity
              style={[styles.actionButton, styles.finalizeButton]}
              onPress={() => handleAction(item, 'finalizar')}
            >
              <Ionicons name="checkmark-done-circle-outline" size={20} color="#fff" style={styles.buttonIcon} />
              <Text style={styles.actionButtonText}>Finalizar Ticket</Text>
            </TouchableOpacity>
          )}

          {!canAccept && !canFinalize && (
            <View style={styles.statusContainer}>
              <Ionicons name="lock-closed-outline" size={20} color="#fff" />
              <Text style={styles.statusText}>Asignado a otro técnico</Text>
            </View>
          )}
        </View>
      </View>
    );
  };

  if (loading) {
    return (
      <View style={styles.centered}>
        <ActivityIndicator size="large" color="#2c5364" />
        <Text style={styles.loadingText}>Cargando tickets...</Text>
      </View>
    );
  }

  return (
    <LinearGradient colors={['#0f2027', '#203a43', '#2c5364']} style={styles.container}>
      <StatusBar style="light" />
      
      <View style={styles.header}>
        <View style={styles.headerContent}>
          <View>
            <Text style={styles.headerTitle}>Tickets Abiertos</Text>
            <Text style={styles.headerSubtitle}>Bienvenido, {user?.Nombre || 'Técnico'}</Text>
          </View>
          <TouchableOpacity style={styles.logoutButton} onPress={handleLogout}>
            <Ionicons name="log-out-outline" size={20} color="#fff" />
            <Text style={styles.logoutButtonText}>Salir</Text>
          </TouchableOpacity>
        </View>
      </View>

      {tickets.length === 0 ? (
        <View style={styles.centered}>
          <Ionicons name="ticket-outline" size={64} color="#adb5bd" />
          <Text style={styles.emptyText}>No hay tickets abiertos</Text>
          <Text style={styles.emptySubtext}>Los nuevos tickets aparecerán aquí</Text>
        </View>
      ) : (
        <FlatList
          data={tickets}
          renderItem={renderTicket}
          keyExtractor={(item) => item.Id_Ticket.toString()}
          contentContainerStyle={styles.listContent}
          refreshControl={
            <RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor="#2c5364" />
          }
        />
      )}
    </LinearGradient>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
  },
  centered: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: 30,
  },
  loadingText: {
    color: '#f8f9fa',
    marginTop: 10,
    fontSize: 16,
  },
  header: {
    backgroundColor: 'rgba(26, 44, 52, 0.95)',
    paddingTop: 50,
    paddingBottom: 20,
    paddingHorizontal: 20,
    borderBottomWidth: 1,
    borderBottomColor: '#2c5364',
  },
  headerContent: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  headerTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#f8f9fa',
    marginBottom: 5,
  },
  headerSubtitle: {
    fontSize: 14,
    color: '#adb5bd',
  },
  logoutButton: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 8,
    paddingHorizontal: 16,
    backgroundColor: '#dc3545',
    borderRadius: 6,
    gap: 6,
  },
  logoutButtonText: {
    color: '#fff',
    fontWeight: '600',
    fontSize: 14,
  },
  listContent: {
    padding: 16,
  },
  ticketCard: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 16,
    marginBottom: 16,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  ticketHeader: {
    marginBottom: 12,
  },
  ticketIdContainer: {
    marginBottom: 8,
  },
  ticketId: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#000',
  },
  badgesContainer: {
    flexDirection: 'row',
    gap: 8,
    marginTop: 8,
  },
  statusBadge: {
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 16,
    backgroundColor: '#ffc107',
  },
  statusInProcess: {
    backgroundColor: '#ffc107',
  },
  statusPending: {
    backgroundColor: '#dc3545',
  },
  statusAssigned: {
    backgroundColor: '#6c757d',
  },
  priorityBadge: {
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 16,
    backgroundColor: '#2c5364',
  },
  priorityHigh: {
    backgroundColor: '#dc3545',
  },
  priorityMedium: {
    backgroundColor: '#ffc107',
  },
  priorityLow: {
    backgroundColor: '#198754',
  },
  priorityText: {
    color: '#fff',
    fontSize: 12,
    fontWeight: '600',
  },
  ticketTitle: {
    fontSize: 18,
    fontWeight: '600',
    color: '#000',
    marginBottom: 12,
  },
  ticketInfo: {
    marginBottom: 12,
    paddingTop: 12,
    borderTopWidth: 1,
    borderTopColor: '#e0e0e0',
  },
  infoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 8,
    gap: 8,
  },
  infoText: {
    fontSize: 14,
    color: '#666',
  },
  ticketActions: {
    marginTop: 12,
    paddingTop: 12,
    borderTopWidth: 1,
    borderTopColor: '#e0e0e0',
  },
  actionButton: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    paddingVertical: 12,
    paddingHorizontal: 16,
    borderRadius: 8,
    marginBottom: 8,
    gap: 8,
  },
  acceptButton: {
    backgroundColor: '#2c5364',
  },
  finalizeButton: {
    backgroundColor: '#198754',
  },
  buttonIcon: {
    marginRight: 4,
  },
  actionButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: '600',
  },
  statusContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    paddingVertical: 12,
    paddingHorizontal: 16,
    backgroundColor: '#6c757d',
    borderRadius: 8,
    gap: 8,
  },
  statusText: {
    color: '#fff',
    fontSize: 14,
    fontWeight: '500',
  },
  emptyText: {
    color: '#f8f9fa',
    fontSize: 20,
    fontWeight: '600',
    textAlign: 'center',
    marginTop: 16,
  },
  emptySubtext: {
    color: '#adb5bd',
    fontSize: 14,
    textAlign: 'center',
    marginTop: 8,
  },
});

export default TecnicoScreen;


