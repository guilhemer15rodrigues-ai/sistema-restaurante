import React, { useMemo, useState } from 'react';
import { FlatList, Pressable, ScrollView, StyleSheet, Text, View } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import Ionicons from '@expo/vector-icons/Ionicons';
import { theme } from '../lib/theme';
import { useStore } from '../lib/store';
import { elapsed } from '../lib/data';
import { KitchenTicket } from '../lib/types';
import Badge from '../components/Badge';
import StatusFilterChip from '../components/StatusFilterChip';

const TS: Record<KitchenTicket['status'], { label: string; color: string; icon: string; next: string }> = {
  pending: { label: 'Aguardando', color: theme.colors.accent, icon: 'hourglass-outline', next: 'Iniciar' },
  cooking: { label: 'Preparando', color: theme.colors.blue, icon: 'flame-outline', next: 'Pronto' },
  ready: { label: 'Pronto', color: theme.colors.green, icon: 'checkmark-circle', next: 'Entregue' },
};

const FILTERS: ('all' | KitchenTicket['status'])[] = ['all', 'pending', 'cooking', 'ready'];

export default function KitchenScreen() {
  const { tickets, advanceTicket } = useStore();
  const [filter, setFilter] = useState<'all' | KitchenTicket['status']>('all');
  const filtered = useMemo(() => filter === 'all' ? tickets : tickets.filter((ticket) => ticket.status === filter), [filter, tickets]);

  const render = ({ item }: { item: KitchenTicket }) => {
    const meta = TS[item.status];
    const count = item.lines.reduce((s, l) => s + l.qty, 0);
    return (
      <View style={[styles.ticket, { borderLeftColor: meta.color }]}>
        <View style={styles.ticketHead}>
          <View style={styles.tableTag}>
            <View style={[styles.tableIcon, { backgroundColor: meta.color + '1F' }]}>
              <Ionicons name="restaurant" size={14} color={meta.color} />
            </View>
            <View>
              <Text style={styles.tableTagTxt}>Mesa {item.table}</Text>
              <Text style={styles.ticketTime}>{elapsed(item.placedAt)} · {count} itens</Text>
            </View>
          </View>
          <Badge label={meta.label} color={meta.color} compact />
        </View>

        <View style={styles.lines}>
          {item.lines.map((line) => (
            <View key={line.item.id} style={styles.line}>
              <Text style={styles.lineQty}>{line.qty}x</Text>
              <Text style={styles.lineEmoji}>{line.item.emoji}</Text>
              <View style={{ flex: 1 }}>
                <Text style={styles.lineName}>{line.item.name}</Text>
                {line.notes && <Text style={styles.lineNote}>{line.notes}</Text>}
              </View>
            </View>
          ))}
        </View>

        <View style={styles.ticketFoot}>
          <View style={styles.footMeta}>
            <Ionicons name={meta.icon as any} size={14} color={meta.color} />
            <Text style={[styles.footMetaTxt, { color: meta.color }]}>{meta.label}</Text>
          </View>
          {item.status !== 'ready' ? (
            <Pressable style={[styles.advBtn, { backgroundColor: meta.color }]} onPress={() => advanceTicket(item.id)}>
              <Text style={styles.advTxt}>{meta.next}</Text>
              <Ionicons name="arrow-forward" size={14} color={theme.colors.bg} />
            </Pressable>
          ) : (
            <View style={styles.readyBox}>
              <Ionicons name="checkmark-done" size={15} color={theme.colors.green} />
              <Text style={styles.readyTxt}>Pronto para servir</Text>
            </View>
          )}
        </View>
      </View>
    );
  };

  return (
    <SafeAreaView style={styles.safe} edges={['top']}>
      <View style={styles.header}>
        <View>
          <Text style={styles.eyebrow}>Status da cozinha</Text>
          <Text style={styles.title}>Cozinha</Text>
        </View>
        <View style={styles.kdsPill}>
          <Ionicons name="flame" size={15} color={theme.colors.primary} />
          <Text style={styles.kdsTxt}>{tickets.length}</Text>
        </View>
      </View>

      <ScrollView horizontal showsHorizontalScrollIndicator={false} style={styles.filterScroll} contentContainerStyle={styles.filterContent}>
        {FILTERS.map((f) => (
          <StatusFilterChip
            key={f}
            label={f === 'all' ? 'Todos' : TS[f].label}
            color={f === 'all' ? theme.colors.primary : TS[f].color}
            active={filter === f}
            onPress={() => setFilter(f)}
          />
        ))}
      </ScrollView>

      <FlatList
        data={filtered}
        keyExtractor={(t) => t.id}
        renderItem={render}
        contentContainerStyle={{ padding: 18, paddingBottom: 110, gap: 14 }}
        showsVerticalScrollIndicator={false}
        ListEmptyComponent={<Text style={styles.empty}>Sem comandas neste filtro.</Text>}
      />
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: theme.colors.bg },
  header: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', paddingHorizontal: 20, paddingTop: 8, paddingBottom: 8 },
  eyebrow: { color: theme.colors.textDim, fontSize: 13, fontWeight: '700' },
  title: { color: theme.colors.text, fontSize: 30, fontWeight: '900', letterSpacing: -0.5 },
  kdsPill: { flexDirection: 'row', alignItems: 'center', gap: 6, backgroundColor: theme.colors.primary + '1F', paddingHorizontal: 12, paddingVertical: 8, borderRadius: 14 },
  kdsTxt: { color: theme.colors.primary, fontWeight: '900', fontSize: 13 },
  filterScroll: { marginTop: 8, maxHeight: 38 },
  filterContent: { paddingHorizontal: 18, gap: 8 },
  ticket: { backgroundColor: theme.colors.surface, borderRadius: theme.radius.lg, padding: 15, borderWidth: 1, borderColor: theme.colors.border, borderLeftWidth: 4, gap: 13, ...theme.shadow, shadowOpacity: 0.12, elevation: 3 },
  ticketHead: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'flex-start', gap: 10 },
  tableTag: { flexDirection: 'row', alignItems: 'center', gap: 9 },
  tableIcon: { width: 38, height: 38, borderRadius: 13, alignItems: 'center', justifyContent: 'center' },
  tableTagTxt: { color: theme.colors.text, fontWeight: '900', fontSize: 17 },
  ticketTime: { color: theme.colors.textDim, fontSize: 12, fontWeight: '700', marginTop: 2 },
  lines: { gap: 9 },
  line: { flexDirection: 'row', alignItems: 'center', gap: 9 },
  lineQty: { color: theme.colors.primary, fontWeight: '900', fontSize: 14.5, minWidth: 28 },
  lineEmoji: { fontSize: 20 },
  lineName: { color: theme.colors.text, fontSize: 14.5, fontWeight: '700' },
  lineNote: { color: theme.colors.accent, fontSize: 12, fontWeight: '700', marginTop: 1 },
  ticketFoot: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', borderTopWidth: 1, borderTopColor: theme.colors.border, paddingTop: 12 },
  footMeta: { flexDirection: 'row', alignItems: 'center', gap: 5 },
  footMetaTxt: { fontSize: 12.5, fontWeight: '800' },
  advBtn: { flexDirection: 'row', alignItems: 'center', gap: 6, paddingHorizontal: 14, paddingVertical: 9, borderRadius: 12 },
  advTxt: { color: theme.colors.bg, fontWeight: '900', fontSize: 13 },
  readyBox: { flexDirection: 'row', alignItems: 'center', gap: 5 },
  readyTxt: { color: theme.colors.green, fontWeight: '800', fontSize: 13 },
  empty: { color: theme.colors.textDim, textAlign: 'center', marginTop: 60, fontSize: 15, fontWeight: '700' },
});
