import React, { useMemo, useState } from 'react';
import { View, Text, StyleSheet, FlatList, ScrollView, Pressable } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import Ionicons from '@expo/vector-icons/Ionicons';
import { theme } from '../lib/theme';
import { useStore } from '../lib/store';
import { TableInfo, TableStatus } from '../lib/types';
import QuickStatCard from '../components/QuickStatCard';
import StatusFilterChip from '../components/StatusFilterChip';
import TableStatusCard from '../components/TableStatusCard';

const STATUS_FILTERS: { key: 'all' | TableStatus | 'ready'; label: string; color: string }[] = [
  { key: 'all', label: 'Todas', color: theme.colors.primary },
  { key: 'free', label: 'Livres', color: theme.colors.green },
  { key: 'seated', label: 'Ocupadas', color: theme.colors.blue },
  { key: 'ordered', label: 'Pedido em preparo', color: theme.colors.accent },
  { key: 'served', label: 'Servidas', color: theme.colors.purple },
  { key: 'bill', label: 'Aguardando conta', color: theme.colors.primary },
  { key: 'ready', label: 'Pedido pronto', color: theme.colors.green },
];

const ZONES = [
  { value: 'Todas', label: 'Todas' },
  { value: 'SalÃ£o', label: 'Salão' },
  { value: 'Varanda', label: 'Varanda' },
  { value: 'VIP', label: 'VIP' },
] as const;

export default function TablesScreen({ navigation }: any) {
  const { tables, cartCount, tickets } = useStore();
  const [status, setStatus] = useState<'all' | TableStatus | 'ready'>('all');
  const [zone, setZone] = useState<string>('Todas');
  const readyTables = useMemo(() => new Set(tickets.filter((ticket) => ticket.status === 'ready').map((ticket) => ticket.table)), [tickets]);

  const filtered = useMemo(
    () => tables.filter((table) => {
      const zoneOk = zone === 'Todas' || table.zone === zone;
      const statusOk = status === 'all'
        || (status === 'ready' ? readyTables.has(table.id) : table.status === status);
      return zoneOk && statusOk;
    }),
    [tables, zone, status, readyTables]
  );

  const stats = useMemo(() => ({
    free: tables.filter((t) => t.status === 'free').length,
    occupied: tables.filter((t) => ['seated', 'ordered', 'served'].includes(t.status)).length,
    ordered: tables.filter((t) => t.status === 'ordered').length,
    bills: tables.filter((t) => t.status === 'bill').length,
  }), [tables]);

  const renderTable = ({ item }: { item: TableInfo }) => (
    <TableStatusCard
      table={item}
      cartItems={cartCount(item.id)}
      ready={readyTables.has(item.id)}
      onPress={() => navigation.navigate('TableDetail', { id: item.id })}
    />
  );

  return (
    <SafeAreaView style={styles.safe} edges={['top']}>
      <View style={styles.header}>
        <View>
          <Text style={styles.hi}>Boa noite, Lucas</Text>
          <Text style={styles.title}>Mesas e atendimentos</Text>
        </View>
        <View style={styles.headActions}>
          <View style={styles.shiftPill}>
            <View style={styles.liveDot} />
            <Text style={styles.shiftTxt}>Turno ativo</Text>
          </View>
          <Pressable style={styles.notifyBtn}>
            <Ionicons name="notifications-outline" size={20} color={theme.colors.text} />
          </Pressable>
        </View>
      </View>

      <View style={styles.statsGrid}>
        <QuickStatCard icon="checkmark-circle" color={theme.colors.green} value={stats.free} label="Livres" active={status === 'free'} onPress={() => setStatus(status === 'free' ? 'all' : 'free')} />
        <QuickStatCard icon="people" color={theme.colors.blue} value={stats.occupied} label="Ocupadas" active={status === 'seated'} onPress={() => setStatus(status === 'seated' ? 'all' : 'seated')} />
        <QuickStatCard icon="flame" color={theme.colors.accent} value={stats.ordered} label="Em preparo" active={status === 'ordered'} onPress={() => setStatus(status === 'ordered' ? 'all' : 'ordered')} />
        <QuickStatCard icon="receipt" color={theme.colors.primary} value={stats.bills} label="Aguardando conta" active={status === 'bill'} onPress={() => setStatus(status === 'bill' ? 'all' : 'bill')} />
      </View>

      <ScrollView horizontal showsHorizontalScrollIndicator={false} style={styles.filterScroll} contentContainerStyle={styles.filterContent}>
        {STATUS_FILTERS.map((filter) => (
          <StatusFilterChip key={filter.key} label={filter.label} color={filter.color} active={status === filter.key} onPress={() => setStatus(filter.key)} />
        ))}
      </ScrollView>

      <ScrollView horizontal showsHorizontalScrollIndicator={false} style={styles.zoneScroll} contentContainerStyle={styles.zoneContent}>
        {ZONES.map((z) => (
          <Pressable key={z.value} onPress={() => setZone(z.value)} style={[styles.zoneChip, zone === z.value && styles.zoneChipActive]}>
            <Text style={[styles.zoneChipTxt, zone === z.value && styles.zoneChipTxtActive]}>{z.label}</Text>
          </Pressable>
        ))}
      </ScrollView>

      <FlatList
        data={filtered}
        keyExtractor={(t) => String(t.id)}
        numColumns={2}
        renderItem={renderTable}
        columnWrapperStyle={styles.col}
        contentContainerStyle={styles.grid}
        showsVerticalScrollIndicator={false}
      />
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: theme.colors.bg },
  header: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'flex-start', paddingHorizontal: 20, paddingTop: 8, paddingBottom: 8, gap: 12 },
  hi: { color: theme.colors.textDim, fontSize: 13, fontWeight: '700' },
  title: { color: theme.colors.text, fontSize: 24, fontWeight: '900', letterSpacing: -0.4, marginTop: 2 },
  headActions: { alignItems: 'flex-end', gap: 8 },
  shiftPill: { flexDirection: 'row', alignItems: 'center', backgroundColor: theme.colors.green + '1F', paddingHorizontal: 10, paddingVertical: 7, borderRadius: 20 },
  liveDot: { width: 7, height: 7, borderRadius: 4, backgroundColor: theme.colors.green, marginRight: 6 },
  shiftTxt: { color: theme.colors.green, fontWeight: '800', fontSize: 11 },
  notifyBtn: { width: 38, height: 38, borderRadius: 13, backgroundColor: theme.colors.surface, borderWidth: 1, borderColor: theme.colors.border, alignItems: 'center', justifyContent: 'center' },
  statsGrid: { flexDirection: 'row', flexWrap: 'wrap', paddingHorizontal: 18, gap: 8, marginTop: 8 },
  filterScroll: { marginTop: 16, maxHeight: 38 },
  filterContent: { paddingHorizontal: 18, gap: 8 },
  zoneScroll: { marginTop: 10, maxHeight: 35 },
  zoneContent: { paddingHorizontal: 18, gap: 8 },
  zoneChip: { height: 32, paddingHorizontal: 13, borderRadius: 999, backgroundColor: theme.colors.surface, borderWidth: 1, borderColor: theme.colors.border, alignItems: 'center', justifyContent: 'center' },
  zoneChipActive: { backgroundColor: theme.colors.surface2, borderColor: theme.colors.primary + '88' },
  zoneChipTxt: { color: theme.colors.textDim, fontWeight: '800', fontSize: 12 },
  zoneChipTxtActive: { color: theme.colors.text },
  grid: { padding: 18, paddingBottom: 112 },
  col: { gap: 12, marginBottom: 12 },
});
