import React, { useMemo } from 'react';
import { FlatList, StyleSheet, Text, View } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import Ionicons from '@expo/vector-icons/Ionicons';
import { theme } from '../lib/theme';
import { useStore } from '../lib/store';
import { elapsed } from '../lib/data';

export default function NotificationsScreen() {
  const { tickets, tables } = useStore();

  const notifications = useMemo(() => {
    const fromTickets = tickets.map((ticket) => {
      const color = ticket.status === 'ready' ? theme.colors.green : ticket.status === 'cooking' ? theme.colors.blue : theme.colors.accent;
      const title = ticket.status === 'ready'
        ? `Mesa ${ticket.table}: pedido pronto para entrega.`
        : ticket.status === 'cooking'
          ? `Mesa ${ticket.table}: pedido em preparo.`
          : `Mesa ${ticket.table}: novo pedido enviado.`;
      return { id: ticket.id, title, time: elapsed(ticket.placedAt), color, icon: ticket.status === 'ready' ? 'checkmark-circle' : 'flame' };
    });

    const bills = tables
      .filter((table) => table.status === 'bill')
      .map((table) => ({
        id: 'bill-' + table.id,
        title: `Mesa ${table.id}: aguardando fechamento da conta.`,
        time: elapsed(table.startedAt),
        color: theme.colors.primary,
        icon: 'receipt',
      }));

    return [...fromTickets, ...bills];
  }, [tickets, tables]);

  return (
    <SafeAreaView style={styles.safe} edges={['top']}>
      <View style={styles.header}>
        <Text style={styles.eyebrow}>Avisos úteis</Text>
        <Text style={styles.title}>Notificações</Text>
      </View>
      <FlatList
        data={notifications}
        keyExtractor={(item) => item.id}
        contentContainerStyle={{ padding: 18, paddingBottom: 110, gap: 12 }}
        ListEmptyComponent={<Text style={styles.empty}>Nenhuma notificação agora.</Text>}
        renderItem={({ item }) => (
          <View style={styles.card}>
            <View style={[styles.icon, { backgroundColor: item.color + '20' }]}>
              <Ionicons name={item.icon as any} size={19} color={item.color} />
            </View>
            <View style={{ flex: 1 }}>
              <Text style={styles.cardTitle}>{item.title}</Text>
              <Text style={styles.cardTime}>{item.time}</Text>
            </View>
            <View style={[styles.dot, { backgroundColor: item.color }]} />
          </View>
        )}
      />
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: theme.colors.bg },
  header: { paddingHorizontal: 20, paddingTop: 8, paddingBottom: 8 },
  eyebrow: { color: theme.colors.textDim, fontSize: 13, fontWeight: '700' },
  title: { color: theme.colors.text, fontSize: 30, fontWeight: '900', letterSpacing: -0.5 },
  card: { flexDirection: 'row', alignItems: 'center', gap: 12, backgroundColor: theme.colors.surface, borderRadius: theme.radius.lg, padding: 14, borderWidth: 1, borderColor: theme.colors.border },
  icon: { width: 42, height: 42, borderRadius: 14, alignItems: 'center', justifyContent: 'center' },
  cardTitle: { color: theme.colors.text, fontSize: 14.5, fontWeight: '800', lineHeight: 19 },
  cardTime: { color: theme.colors.textFaint, fontSize: 12, fontWeight: '700', marginTop: 4 },
  dot: { width: 9, height: 9, borderRadius: 5 },
  empty: { color: theme.colors.textDim, textAlign: 'center', marginTop: 70, fontWeight: '700' },
});
