import React from 'react';
import { FlatList, Pressable, StyleSheet, Text, View } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import Ionicons from '@expo/vector-icons/Ionicons';
import { theme } from '../lib/theme';
import { useStore } from '../lib/store';
import { elapsed, formatMoney } from '../lib/data';
import Badge from '../components/Badge';

const META = {
  pending: { label: 'Enviado', color: theme.colors.accent },
  cooking: { label: 'Em preparo', color: theme.colors.blue },
  ready: { label: 'Pronto', color: theme.colors.green },
};

export default function OrdersScreen() {
  const { tickets, tables } = useStore();

  return (
    <SafeAreaView style={styles.safe} edges={['top']}>
      <View style={styles.header}>
        <View>
          <Text style={styles.eyebrow}>Atendimento</Text>
          <Text style={styles.title}>Pedidos</Text>
        </View>
        <View style={styles.pill}>
          <Ionicons name="receipt" size={15} color={theme.colors.primary} />
          <Text style={styles.pillText}>{tickets.length}</Text>
        </View>
      </View>

      <FlatList
        data={tickets}
        keyExtractor={(item) => item.id}
        contentContainerStyle={{ padding: 18, paddingBottom: 110, gap: 12 }}
        ListEmptyComponent={<Text style={styles.empty}>Nenhum pedido em andamento.</Text>}
        renderItem={({ item }) => {
          const meta = META[item.status];
          const total = item.lines.reduce((sum, line) => sum + line.qty * line.item.price, 0);
          const table = tables.find((t) => t.id === item.table);
          return (
            <Pressable style={({ pressed }) => [styles.card, pressed && { opacity: 0.88 }]}>
              <View style={styles.cardTop}>
                <View>
                  <Text style={styles.cardTitle}>Mesa {item.table}</Text>
                  <Text style={styles.cardSub}>{table?.server || 'Você'} · {elapsed(item.placedAt)}</Text>
                </View>
                <Badge label={meta.label} color={meta.color} compact />
              </View>
              <View style={styles.lines}>
                {item.lines.slice(0, 3).map((line) => (
                  <Text key={line.item.id} style={styles.line}>{line.qty}x {line.item.name}</Text>
                ))}
                {item.lines.length > 3 && <Text style={styles.line}>+ {item.lines.length - 3} item(ns)</Text>}
              </View>
              <View style={styles.totalRow}>
                <Text style={styles.totalLabel}>Total parcial</Text>
                <Text style={styles.total}>{formatMoney(total)}</Text>
              </View>
            </Pressable>
          );
        }}
      />
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: theme.colors.bg },
  header: { flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between', paddingHorizontal: 20, paddingTop: 8, paddingBottom: 8 },
  eyebrow: { color: theme.colors.textDim, fontSize: 13, fontWeight: '700' },
  title: { color: theme.colors.text, fontSize: 30, fontWeight: '900', letterSpacing: -0.5 },
  pill: { flexDirection: 'row', alignItems: 'center', gap: 6, backgroundColor: theme.colors.primary + '1F', borderRadius: 14, paddingHorizontal: 12, paddingVertical: 8 },
  pillText: { color: theme.colors.primary, fontWeight: '900' },
  card: { backgroundColor: theme.colors.surface, borderRadius: theme.radius.lg, padding: 15, borderWidth: 1, borderColor: theme.colors.border, ...theme.shadow, shadowOpacity: 0.12, elevation: 3 },
  cardTop: { flexDirection: 'row', justifyContent: 'space-between', gap: 10 },
  cardTitle: { color: theme.colors.text, fontSize: 18, fontWeight: '900' },
  cardSub: { color: theme.colors.textDim, fontSize: 12.5, fontWeight: '700', marginTop: 3 },
  lines: { marginTop: 12, gap: 5 },
  line: { color: theme.colors.textDim, fontSize: 13, fontWeight: '700' },
  totalRow: { flexDirection: 'row', justifyContent: 'space-between', borderTopWidth: 1, borderTopColor: theme.colors.border, marginTop: 12, paddingTop: 12 },
  totalLabel: { color: theme.colors.textFaint, fontSize: 12, fontWeight: '700' },
  total: { color: theme.colors.accent, fontSize: 16, fontWeight: '900' },
  empty: { color: theme.colors.textDim, textAlign: 'center', marginTop: 70, fontWeight: '700' },
});
