import React from 'react';
import { Pressable, StyleSheet, Text, View } from 'react-native';
import Ionicons from '@expo/vector-icons/Ionicons';
import { theme } from '../lib/theme';
import { STATUS_META, elapsed } from '../lib/data';
import { TableInfo } from '../lib/types';
import Badge from './Badge';

export default function TableStatusCard({
  table,
  cartItems,
  ready,
  onPress,
}: {
  table: TableInfo;
  cartItems: number;
  ready?: boolean;
  onPress: () => void;
}) {
  const meta = ready ? { label: 'Pronto', color: theme.colors.green } : STATUS_META[table.status];
  const free = table.status === 'free';
  const primaryAction = free ? 'Abrir' : table.status === 'bill' ? 'Conta' : table.status === 'ordered' || ready ? 'Ver' : '+ Pedido';

  return (
    <Pressable
      onPress={onPress}
      style={({ pressed }) => [
        styles.card,
        { borderLeftColor: meta.color },
        pressed && styles.pressed,
      ]}
    >
      <View style={styles.top}>
        <View style={[styles.iconBox, { backgroundColor: meta.color + '1A' }]}>
          <Ionicons name="grid" size={15} color={meta.color} />
          <Text style={[styles.number, { color: meta.color }]}>{table.id}</Text>
        </View>
        <Badge label={meta.label} color={meta.color} compact />
      </View>

      <View style={styles.metaGrid}>
        <Info icon="people-outline" value={free ? `${table.seats} lug.` : `${table.guests}/${table.seats}`} />
        <Info icon="time-outline" value={elapsed(table.startedAt)} />
        <Info icon="pin-outline" value={table.zone} />
      </View>

      <View style={styles.bottom}>
        {cartItems > 0 ? (
          <View style={styles.cartPill}>
            <Ionicons name="cart" size={12} color={theme.colors.bg} />
            <Text style={styles.cartText}>{cartItems}</Text>
          </View>
        ) : (
          <Text style={styles.server} numberOfLines={1}>{table.server || 'Livre'}</Text>
        )}
        <View style={[styles.action, { backgroundColor: meta.color + '22' }]}>
          <Text style={[styles.actionText, { color: meta.color }]}>{primaryAction}</Text>
        </View>
      </View>
    </Pressable>
  );
}

function Info({ icon, value }: { icon: string; value: string }) {
  return (
    <View style={styles.info}>
      <Ionicons name={icon as any} size={12} color={theme.colors.textFaint} />
      <Text style={styles.infoText} numberOfLines={1}>{value}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  card: { flex: 1, minHeight: 154, backgroundColor: theme.colors.surface, borderRadius: theme.radius.lg, padding: 12, borderWidth: 1, borderLeftWidth: 4, borderColor: theme.colors.border, ...theme.shadow, shadowOpacity: 0.11, elevation: 3 },
  pressed: { opacity: 0.86, transform: [{ scale: 0.97 }] },
  top: { flexDirection: 'row', justifyContent: 'space-between', gap: 8, alignItems: 'flex-start' },
  iconBox: { width: 44, height: 44, borderRadius: 14, alignItems: 'center', justifyContent: 'center' },
  number: { fontSize: 19, fontWeight: '900', lineHeight: 21 },
  metaGrid: { marginTop: 12, gap: 7 },
  info: { flexDirection: 'row', alignItems: 'center', gap: 5 },
  infoText: { color: theme.colors.textDim, fontSize: 11.5, fontWeight: '700', flex: 1 },
  bottom: { marginTop: 12, paddingTop: 10, borderTopWidth: 1, borderTopColor: theme.colors.border, flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between', gap: 8 },
  cartPill: { flexDirection: 'row', alignItems: 'center', gap: 3, backgroundColor: theme.colors.accent, paddingHorizontal: 8, height: 24, borderRadius: 12 },
  cartText: { color: theme.colors.bg, fontSize: 11, fontWeight: '900' },
  server: { color: theme.colors.textFaint, fontSize: 11, fontWeight: '700', flex: 1 },
  action: { height: 28, paddingHorizontal: 10, borderRadius: 999, alignItems: 'center', justifyContent: 'center' },
  actionText: { fontSize: 11, fontWeight: '900' },
});
