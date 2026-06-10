import React, { useMemo, useState } from 'react';
import { Alert, FlatList, Pressable, ScrollView, StyleSheet, Text, TextInput, View } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import Ionicons from '@expo/vector-icons/Ionicons';
import { theme } from '../lib/theme';
import { useStore } from '../lib/store';
import { MENU, CATEGORIES, formatMoney } from '../lib/data';
import { MenuItem } from '../lib/types';
import StatusFilterChip from '../components/StatusFilterChip';
import FloatingCartButton from '../components/FloatingCartButton';
import CartDrawer from '../components/CartDrawer';

export default function MenuScreen({ route, navigation }: any) {
  const table: number | undefined = route.params?.table;
  const { addToCart, removeFromCart, carts, cartCount, cartTotal, sendOrder } = useStore();
  const [cat, setCat] = useState('Destaques');
  const [query, setQuery] = useState('');
  const [drawerOpen, setDrawerOpen] = useState(false);
  const cart = table ? (carts[table] || []) : [];

  const items = useMemo(() => {
    let list = MENU;
    if (query.trim()) {
      const q = query.toLowerCase();
      list = list.filter((m) => m.name.toLowerCase().includes(q) || m.desc.toLowerCase().includes(q));
    } else if (cat === 'Destaques') {
      list = list.filter((m) => m.popular);
    } else {
      list = list.filter((m) => m.category === cat);
    }
    return list;
  }, [cat, query]);

  const qtyOf = (id: string) => cart.find((line) => line.item.id === id)?.qty || 0;

  const confirmSend = () => {
    if (!table) return;
    Alert.alert('Enviar para cozinha', 'Deseja enviar este pedido para a cozinha?', [
      { text: 'Cancelar', style: 'cancel' },
      {
        text: 'Enviar',
        onPress: () => {
          sendOrder(table);
          setDrawerOpen(false);
          navigation.goBack();
        },
      },
    ]);
  };

  const renderItem = ({ item }: { item: MenuItem }) => {
    const qty = qtyOf(item.id);
    return (
      <View style={styles.CardProdutoPedido}>
        <View style={styles.cardEmojiWrap}>
          <Text style={styles.cardEmoji}>{item.emoji}</Text>
        </View>
        <View style={{ flex: 1, minWidth: 0 }}>
          <View style={styles.cardNameRow}>
            <Text style={styles.cardName} numberOfLines={1}>{item.name}</Text>
            {item.popular && <Ionicons name="star" size={13} color={theme.colors.accent} />}
          </View>
          <Text style={styles.cardDesc} numberOfLines={1}>{item.desc}</Text>
          <Text style={styles.cardPrice}>{formatMoney(item.price)}</Text>
        </View>
        {table ? (
          qty > 0 ? (
            <View style={styles.qtyBox}>
              <Pressable style={styles.qtyBtn} onPress={() => removeFromCart(table, item.id)}>
                <Ionicons name="remove" size={16} color={theme.colors.text} />
              </Pressable>
              <Text style={styles.qtyTxt}>{qty}</Text>
              <Pressable style={styles.qtyBtnPlus} onPress={() => addToCart(table, item)}>
                <Ionicons name="add" size={16} color={theme.colors.white} />
              </Pressable>
            </View>
          ) : (
            <Pressable style={styles.addBtn} onPress={() => addToCart(table, item)}>
              <Ionicons name="add" size={22} color={theme.colors.white} />
            </Pressable>
          )
        ) : (
          <View style={styles.priceTag}><Text style={styles.priceTagTxt}>{formatMoney(item.price)}</Text></View>
        )}
      </View>
    );
  };

  return (
    <SafeAreaView style={styles.safe} edges={['top']}>
      <View style={styles.header}>
        {table && (
          <Pressable style={styles.backBtn} onPress={() => navigation.goBack()}>
            <Ionicons name="chevron-back" size={24} color={theme.colors.text} />
          </Pressable>
        )}
        <View style={{ flex: 1 }}>
          <Text style={styles.eyebrow}>{table ? `Pedido - Mesa ${table}` : 'Cardápio'}</Text>
          <Text style={styles.title}>Pedido rápido</Text>
        </View>
      </View>

      <View style={styles.searchWrap}>
        <Ionicons name="search" size={18} color={theme.colors.textFaint} />
        <TextInput
          style={styles.search}
          placeholder="Buscar produto"
          placeholderTextColor={theme.colors.textFaint}
          value={query}
          onChangeText={setQuery}
          returnKeyType="search"
        />
        {query.length > 0 && (
          <Pressable onPress={() => setQuery('')}>
            <Ionicons name="close-circle" size={18} color={theme.colors.textFaint} />
          </Pressable>
        )}
      </View>

      {!query && (
        <ScrollView horizontal showsHorizontalScrollIndicator={false} style={styles.catScroll} contentContainerStyle={styles.ListaCategoriasPedido}>
          {CATEGORIES.map((c) => (
            <StatusFilterChip key={c} label={c} active={cat === c} onPress={() => setCat(c)} />
          ))}
        </ScrollView>
      )}

      <FlatList
        data={items}
        keyExtractor={(i) => i.id}
        renderItem={renderItem}
        contentContainerStyle={{ padding: 18, paddingBottom: table ? 124 : 30, gap: 10 }}
        showsVerticalScrollIndicator={false}
        ListEmptyComponent={<Text style={styles.noResult}>Nenhum item encontrado.</Text>}
      />

      {table && (
        <>
          <FloatingCartButton count={cartCount(table)} total={cartTotal(table)} onPress={() => setDrawerOpen(true)} />
          <CartDrawer
            visible={drawerOpen}
            table={table}
            lines={cart}
            total={cartTotal(table)}
            onClose={() => setDrawerOpen(false)}
            onAdd={(line) => addToCart(table, line.item)}
            onRemove={(line) => removeFromCart(table, line.item.id)}
            onSend={confirmSend}
          />
        </>
      )}
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: theme.colors.bg },
  header: { flexDirection: 'row', alignItems: 'center', gap: 10, paddingHorizontal: 16, paddingTop: 8, paddingBottom: 6 },
  backBtn: { width: 40, height: 40, borderRadius: 13, backgroundColor: theme.colors.surface, borderWidth: 1, borderColor: theme.colors.border, alignItems: 'center', justifyContent: 'center' },
  eyebrow: { color: theme.colors.primary, fontSize: 13, fontWeight: '800' },
  title: { color: theme.colors.text, fontSize: 28, fontWeight: '900', letterSpacing: -0.5 },
  searchWrap: { flexDirection: 'row', alignItems: 'center', gap: 9, marginHorizontal: 18, marginTop: 12, backgroundColor: theme.colors.surface, borderRadius: 16, paddingHorizontal: 14, height: 48, borderWidth: 1, borderColor: theme.colors.border },
  search: { flex: 1, color: theme.colors.text, fontSize: 15, fontWeight: '600' },
  catScroll: { marginTop: 14, maxHeight: 38 },
  ListaCategoriasPedido: { paddingHorizontal: 18, gap: 8 },
  CardProdutoPedido: { flexDirection: 'row', alignItems: 'center', gap: 12, backgroundColor: theme.colors.surface, borderRadius: theme.radius.lg, padding: 12, borderWidth: 1, borderColor: theme.colors.border, ...theme.shadow, shadowOpacity: 0.1, elevation: 2 },
  cardEmojiWrap: { width: 50, height: 50, borderRadius: 15, backgroundColor: theme.colors.surface2, alignItems: 'center', justifyContent: 'center' },
  cardEmoji: { fontSize: 27 },
  cardNameRow: { flexDirection: 'row', alignItems: 'center', gap: 5 },
  cardName: { color: theme.colors.text, fontSize: 15.5, fontWeight: '800', flexShrink: 1 },
  cardDesc: { color: theme.colors.textDim, fontSize: 12.5, marginTop: 2, fontWeight: '600' },
  cardPrice: { color: theme.colors.accent, fontSize: 14, fontWeight: '900', marginTop: 5 },
  addBtn: { width: 42, height: 42, borderRadius: 14, backgroundColor: theme.colors.primary, alignItems: 'center', justifyContent: 'center' },
  qtyBox: { flexDirection: 'row', alignItems: 'center', gap: 4, backgroundColor: theme.colors.surface2, borderRadius: 13, padding: 4 },
  qtyBtn: { width: 30, height: 30, borderRadius: 10, backgroundColor: theme.colors.card, alignItems: 'center', justifyContent: 'center' },
  qtyBtnPlus: { width: 30, height: 30, borderRadius: 10, backgroundColor: theme.colors.primary, alignItems: 'center', justifyContent: 'center' },
  qtyTxt: { color: theme.colors.text, fontWeight: '900', minWidth: 20, textAlign: 'center' },
  priceTag: { backgroundColor: theme.colors.surface2, paddingHorizontal: 12, paddingVertical: 8, borderRadius: 12 },
  priceTagTxt: { color: theme.colors.text, fontWeight: '800', fontSize: 13 },
  noResult: { color: theme.colors.textDim, textAlign: 'center', marginTop: 40, fontSize: 14 },
});
