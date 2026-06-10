import React, { useMemo, useState } from 'react';
import { Alert, Pressable, ScrollView, StyleSheet, Text, View } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import Ionicons from '@expo/vector-icons/Ionicons';
import { theme } from '../lib/theme';
import { useStore } from '../lib/store';
import { STATUS_META, elapsed, formatMoney } from '../lib/data';
import Badge from '../components/Badge';
import CartDrawer from '../components/CartDrawer';

export default function TableDetailScreen({ route, navigation }: any) {
  const { id } = route.params;
  const { tables, carts, tickets, cartTotal, removeFromCart, addToCart, sendOrder, setTableStatus } = useStore();
  const [drawerOpen, setDrawerOpen] = useState(false);
  const table = tables.find((t) => t.id === id)!;
  const cart = carts[id] || [];
  const tableTickets = tickets.filter((ticket) => ticket.table === id);
  const meta = STATUS_META[table.status];
  const total = cartTotal(id);

  const actions = useMemo(() => {
    const list = [];
    if (table.status === 'free') list.push({ label: 'Abrir mesa', icon: 'person-add-outline', onPress: () => setTableStatus(id, 'seated', table.seats) });
    if (table.status !== 'free' && table.status !== 'bill') list.push({ label: 'Adicionar pedido', icon: 'add-circle-outline', onPress: () => navigation.navigate('Menu', { table: id }) });
    if (cart.length > 0) list.push({ label: 'Enviar para cozinha', icon: 'send-outline', onPress: () => setDrawerOpen(true) });
    if (table.status !== 'free' && table.status !== 'bill') list.push({ label: 'Solicitar conta', icon: 'receipt-outline', onPress: () => setTableStatus(id, 'bill') });
    if (table.status === 'bill' || table.status === 'served') list.push({ label: 'Liberar mesa', icon: 'checkmark-circle-outline', onPress: () => setTableStatus(id, 'free', 0) });
    return list;
  }, [cart.length, id, navigation, setTableStatus, table.seats, table.status]);

  const confirmSend = () => {
    Alert.alert('Enviar para cozinha', 'Deseja enviar este pedido para a cozinha?', [
      { text: 'Cancelar', style: 'cancel' },
      { text: 'Enviar', onPress: () => { sendOrder(id); setDrawerOpen(false); } },
    ]);
  };

  return (
    <SafeAreaView style={styles.safe} edges={['top']}>
      <View style={styles.header}>
        <Pressable style={styles.backBtn} onPress={() => navigation.goBack()}>
          <Ionicons name="chevron-back" size={24} color={theme.colors.text} />
        </Pressable>
        <Text style={styles.headerTitle}>Mesa {table.id}</Text>
        <View style={{ width: 40 }} />
      </View>

      <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={{ paddingBottom: 124 }}>
        <View style={styles.hero}>
          <View style={[styles.heroNum, { backgroundColor: meta.color + '1A' }]}>
            <Text style={[styles.heroNumTxt, { color: meta.color }]}>{table.id}</Text>
          </View>
          <View style={{ flex: 1, gap: 7 }}>
            <Badge label={meta.label} color={meta.color} />
            <View style={styles.heroMeta}>
              <Info icon="people-outline" text={`${table.guests || table.seats} pessoas`} />
              <Info icon="time-outline" text={elapsed(table.startedAt)} />
              <Info icon="pin-outline" text={table.zone} />
            </View>
            <Text style={styles.heroServer}>Atendente: {table.server || 'Você'}</Text>
          </View>
        </View>

        {table.status === 'bill' && (
          <View style={styles.notice}>
            <Ionicons name="cash-outline" size={18} color={theme.colors.primary} />
            <Text style={styles.noticeText}>Conta solicitada. O fechamento será feito pelo caixa.</Text>
          </View>
        )}

        <View style={styles.actionsGrid}>
          {actions.map((action) => (
            <Pressable key={action.label} onPress={action.onPress} style={({ pressed }) => [styles.actionBtn, pressed && { opacity: 0.86 }]}>
              <Ionicons name={action.icon as any} size={18} color={theme.colors.text} />
              <Text style={styles.actionTxt}>{action.label}</Text>
            </Pressable>
          ))}
        </View>

        <View style={styles.sectionHead}>
          <Text style={styles.sectionTitle}>Pedidos em andamento</Text>
          <Text style={styles.sectionCount}>{tableTickets.length}</Text>
        </View>
        <View style={styles.ticketList}>
          {tableTickets.length === 0 ? (
            <View style={styles.emptySmall}>
              <Text style={styles.emptyTxt}>Nenhum pedido enviado ainda.</Text>
            </View>
          ) : tableTickets.map((ticket) => (
            <View key={ticket.id} style={styles.ticketCard}>
              <View style={styles.ticketTop}>
                <Text style={styles.ticketTitle}>Pedido {ticket.id}</Text>
                <Badge
                  compact
                  label={ticket.status === 'ready' ? 'Pronto' : ticket.status === 'cooking' ? 'Em preparo' : 'Enviado'}
                  color={ticket.status === 'ready' ? theme.colors.green : ticket.status === 'cooking' ? theme.colors.blue : theme.colors.accent}
                />
              </View>
              {ticket.lines.slice(0, 3).map((line) => (
                <Text key={line.item.id} style={styles.ticketLine}>{line.qty}x {line.item.name}</Text>
              ))}
            </View>
          ))}
        </View>

        <View style={styles.sectionHead}>
          <Text style={styles.sectionTitle}>Carrinho atual</Text>
          <Pressable style={styles.addItems} onPress={() => navigation.navigate('Menu', { table: id })}>
            <Ionicons name="add" size={16} color={theme.colors.primary} />
            <Text style={styles.addItemsTxt}>Adicionar</Text>
          </Pressable>
        </View>

        {cart.length === 0 ? (
          <View style={styles.empty}>
            <Ionicons name="reader-outline" size={38} color={theme.colors.textFaint} />
            <Text style={styles.emptyTxt}>Nenhum item no carrinho</Text>
            <Pressable style={styles.emptyBtn} onPress={() => navigation.navigate('Menu', { table: id })}>
              <Text style={styles.emptyBtnTxt}>Abrir cardápio</Text>
            </Pressable>
          </View>
        ) : (
          <View style={styles.cartList}>
            {cart.map((line) => (
              <View key={line.item.id} style={styles.cartLine}>
                <Text style={styles.lineEmoji}>{line.item.emoji}</Text>
                <View style={{ flex: 1 }}>
                  <Text style={styles.lineName}>{line.item.name}</Text>
                  <Text style={styles.linePrice}>{line.qty}x {formatMoney(line.item.price)}</Text>
                </View>
                <Text style={styles.lineTotal}>{formatMoney(line.qty * line.item.price)}</Text>
              </View>
            ))}
          </View>
        )}
      </ScrollView>

      {cart.length > 0 && (
        <View style={styles.footer}>
          <View>
            <Text style={styles.footerLabel}>Total parcial</Text>
            <Text style={styles.footerTotal}>{formatMoney(total)}</Text>
          </View>
          <Pressable style={styles.sendBtn} onPress={() => setDrawerOpen(true)}>
            <Ionicons name="cart" size={17} color={theme.colors.white} />
            <Text style={styles.sendTxt}>Carrinho</Text>
          </Pressable>
        </View>
      )}

      <CartDrawer
        visible={drawerOpen}
        table={id}
        lines={cart}
        total={total}
        onClose={() => setDrawerOpen(false)}
        onAdd={(line) => addToCart(id, line.item)}
        onRemove={(line) => removeFromCart(id, line.item.id)}
        onSend={confirmSend}
      />
    </SafeAreaView>
  );
}

function Info({ icon, text }: { icon: string; text: string }) {
  return (
    <View style={styles.info}>
      <Ionicons name={icon as any} size={13} color={theme.colors.textDim} />
      <Text style={styles.infoText}>{text}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: theme.colors.bg },
  header: { flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between', paddingHorizontal: 14, paddingVertical: 8 },
  backBtn: { width: 40, height: 40, borderRadius: 13, backgroundColor: theme.colors.surface, borderWidth: 1, borderColor: theme.colors.border, alignItems: 'center', justifyContent: 'center' },
  headerTitle: { color: theme.colors.text, fontSize: 18, fontWeight: '900' },
  hero: { flexDirection: 'row', gap: 14, marginHorizontal: 18, marginTop: 8, backgroundColor: theme.colors.surface, borderRadius: theme.radius.lg, padding: 16, borderWidth: 1, borderColor: theme.colors.border, ...theme.shadow, shadowOpacity: 0.12, elevation: 3 },
  heroNum: { width: 66, height: 66, borderRadius: 20, alignItems: 'center', justifyContent: 'center' },
  heroNumTxt: { fontSize: 32, fontWeight: '900' },
  heroMeta: { flexDirection: 'row', flexWrap: 'wrap', gap: 8 },
  info: { flexDirection: 'row', alignItems: 'center', gap: 4 },
  infoText: { color: theme.colors.textDim, fontSize: 12.5, fontWeight: '700' },
  heroServer: { color: theme.colors.textFaint, fontSize: 12, fontWeight: '700' },
  notice: { flexDirection: 'row', alignItems: 'center', gap: 9, marginHorizontal: 18, marginTop: 12, backgroundColor: theme.colors.primary + '16', borderWidth: 1, borderColor: theme.colors.primary + '44', borderRadius: theme.radius.md, padding: 12 },
  noticeText: { color: theme.colors.text, fontWeight: '700', flex: 1, fontSize: 13 },
  actionsGrid: { flexDirection: 'row', flexWrap: 'wrap', gap: 9, paddingHorizontal: 18, marginTop: 16 },
  actionBtn: { flexDirection: 'row', alignItems: 'center', gap: 7, backgroundColor: theme.colors.surface, borderWidth: 1, borderColor: theme.colors.border, paddingHorizontal: 13, paddingVertical: 11, borderRadius: 14 },
  actionTxt: { color: theme.colors.text, fontWeight: '800', fontSize: 13 },
  sectionHead: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', paddingHorizontal: 18, marginTop: 24, marginBottom: 10 },
  sectionTitle: { color: theme.colors.text, fontSize: 18, fontWeight: '900' },
  sectionCount: { color: theme.colors.textDim, fontWeight: '800' },
  ticketList: { marginHorizontal: 18, gap: 10 },
  ticketCard: { backgroundColor: theme.colors.surface, borderRadius: theme.radius.md, padding: 12, borderWidth: 1, borderColor: theme.colors.border },
  ticketTop: { flexDirection: 'row', justifyContent: 'space-between', gap: 10, marginBottom: 8 },
  ticketTitle: { color: theme.colors.text, fontWeight: '900', fontSize: 14 },
  ticketLine: { color: theme.colors.textDim, fontSize: 12.5, fontWeight: '700', marginTop: 3 },
  addItems: { flexDirection: 'row', alignItems: 'center', gap: 3 },
  addItemsTxt: { color: theme.colors.primary, fontWeight: '800', fontSize: 14 },
  emptySmall: { backgroundColor: theme.colors.surface, borderWidth: 1, borderColor: theme.colors.border, borderRadius: theme.radius.md, padding: 14 },
  empty: { alignItems: 'center', gap: 12, marginTop: 4, marginHorizontal: 18, backgroundColor: theme.colors.surface, borderRadius: theme.radius.lg, paddingVertical: 30, borderWidth: 1, borderColor: theme.colors.border, borderStyle: 'dashed' },
  emptyTxt: { color: theme.colors.textDim, fontSize: 14, fontWeight: '700' },
  emptyBtn: { backgroundColor: theme.colors.primary, paddingHorizontal: 20, paddingVertical: 10, borderRadius: 12 },
  emptyBtnTxt: { color: theme.colors.white, fontWeight: '800' },
  cartList: { marginHorizontal: 18, gap: 10 },
  cartLine: { flexDirection: 'row', alignItems: 'center', gap: 12, backgroundColor: theme.colors.surface, borderRadius: theme.radius.md, padding: 12, borderWidth: 1, borderColor: theme.colors.border },
  lineEmoji: { fontSize: 25 },
  lineName: { color: theme.colors.text, fontSize: 15, fontWeight: '800' },
  linePrice: { color: theme.colors.textDim, fontSize: 12.5, fontWeight: '700', marginTop: 2 },
  lineTotal: { color: theme.colors.accent, fontSize: 13, fontWeight: '900' },
  footer: { position: 'absolute', bottom: 0, left: 0, right: 0, flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between', backgroundColor: theme.colors.surface, paddingHorizontal: 20, paddingTop: 14, paddingBottom: 28, borderTopWidth: 1, borderTopColor: theme.colors.border },
  footerLabel: { color: theme.colors.textDim, fontSize: 12, fontWeight: '700' },
  footerTotal: { color: theme.colors.text, fontSize: 24, fontWeight: '900' },
  sendBtn: { flexDirection: 'row', alignItems: 'center', gap: 8, backgroundColor: theme.colors.primary, paddingHorizontal: 20, height: 48, borderRadius: 16 },
  sendTxt: { color: theme.colors.white, fontWeight: '900', fontSize: 15 },
});
