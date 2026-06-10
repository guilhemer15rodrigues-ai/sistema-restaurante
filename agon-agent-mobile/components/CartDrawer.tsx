import React, { useEffect, useRef } from 'react';
import { Animated, Modal, Pressable, StyleSheet, Text, TextInput, View } from 'react-native';
import Ionicons from '@expo/vector-icons/Ionicons';
import { theme } from '../lib/theme';
import { formatMoney } from '../lib/data';
import { OrderLine } from '../lib/types';

export default function CartDrawer({
  visible,
  table,
  lines,
  total,
  onClose,
  onAdd,
  onRemove,
  onSend,
}: {
  visible: boolean;
  table: number;
  lines: OrderLine[];
  total: number;
  onClose: () => void;
  onAdd: (line: OrderLine) => void;
  onRemove: (line: OrderLine) => void;
  onSend: () => void;
}) {
  const slide = useRef(new Animated.Value(360)).current;

  useEffect(() => {
    Animated.timing(slide, {
      toValue: visible ? 0 : 360,
      duration: 220,
      useNativeDriver: true,
    }).start();
  }, [slide, visible]);

  return (
    <Modal transparent visible={visible} animationType="fade" onRequestClose={onClose}>
      <View style={styles.overlay}>
        <Pressable style={StyleSheet.absoluteFill} onPress={onClose} />
        <Animated.View style={[styles.drawer, { transform: [{ translateX: slide }] }]}>
          <View style={styles.header}>
            <View>
              <Text style={styles.title}>Pedido - Mesa {table}</Text>
              <Text style={styles.sub}>{lines.reduce((sum, line) => sum + line.qty, 0)} itens na comanda</Text>
            </View>
            <Pressable onPress={onClose} style={styles.close}>
              <Ionicons name="close" size={22} color={theme.colors.text} />
            </Pressable>
          </View>

          <View style={styles.list}>
            {lines.length === 0 ? (
              <View style={styles.empty}>
                <Ionicons name="cart-outline" size={38} color={theme.colors.textFaint} />
                <Text style={styles.emptyText}>Carrinho vazio</Text>
              </View>
            ) : (
              lines.map((line) => (
                <View key={line.item.id} style={styles.line}>
                  <View style={styles.lineTop}>
                    <Text style={styles.emoji}>{line.item.emoji}</Text>
                    <View style={{ flex: 1 }}>
                      <Text style={styles.lineName}>{line.item.name}</Text>
                      <Text style={styles.linePrice}>{line.qty}x {formatMoney(line.item.price)} · {formatMoney(line.item.price * line.qty)}</Text>
                    </View>
                    <View style={styles.qtyBox}>
                      <Pressable style={styles.qtyBtn} onPress={() => onRemove(line)}>
                        <Ionicons name="remove" size={15} color={theme.colors.text} />
                      </Pressable>
                      <Text style={styles.qtyText}>{line.qty}</Text>
                      <Pressable style={styles.qtyBtn} onPress={() => onAdd(line)}>
                        <Ionicons name="add" size={15} color={theme.colors.text} />
                      </Pressable>
                    </View>
                  </View>
                  <TextInput
                    style={styles.note}
                    placeholder="Obs. do item"
                    placeholderTextColor={theme.colors.textFaint}
                    defaultValue={line.notes}
                    editable={false}
                  />
                </View>
              ))
            )}
          </View>

          <View style={styles.footer}>
            <View>
              <Text style={styles.totalLabel}>Total do pedido</Text>
              <Text style={styles.total}>{formatMoney(total)}</Text>
            </View>
            <Pressable disabled={lines.length === 0} onPress={onSend} style={[styles.send, lines.length === 0 && styles.sendDisabled]}>
              <Ionicons name="send" size={17} color={theme.colors.white} />
              <Text style={styles.sendText}>Enviar</Text>
            </Pressable>
          </View>
        </Animated.View>
      </View>
    </Modal>
  );
}

const styles = StyleSheet.create({
  overlay: { flex: 1, backgroundColor: 'rgba(0,0,0,.58)', alignItems: 'flex-end' },
  drawer: { width: '88%', maxWidth: 390, height: '100%', backgroundColor: theme.colors.surface, borderLeftWidth: 1, borderLeftColor: theme.colors.border, paddingTop: 52 },
  header: { flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between', paddingHorizontal: 18, paddingBottom: 14, borderBottomWidth: 1, borderBottomColor: theme.colors.border },
  title: { color: theme.colors.text, fontSize: 20, fontWeight: '900' },
  sub: { color: theme.colors.textDim, fontSize: 12, fontWeight: '700', marginTop: 3 },
  close: { width: 38, height: 38, borderRadius: 12, backgroundColor: theme.colors.surface2, alignItems: 'center', justifyContent: 'center' },
  list: { flex: 1, padding: 14, gap: 10 },
  empty: { flex: 1, alignItems: 'center', justifyContent: 'center', gap: 10 },
  emptyText: { color: theme.colors.textDim, fontWeight: '700' },
  line: { borderRadius: theme.radius.md, borderWidth: 1, borderColor: theme.colors.border, backgroundColor: theme.colors.card, padding: 12 },
  lineTop: { flexDirection: 'row', alignItems: 'center', gap: 10 },
  emoji: { fontSize: 24 },
  lineName: { color: theme.colors.text, fontSize: 14.5, fontWeight: '800' },
  linePrice: { color: theme.colors.textDim, fontSize: 12, fontWeight: '700', marginTop: 3 },
  qtyBox: { flexDirection: 'row', alignItems: 'center', borderRadius: 12, backgroundColor: theme.colors.surface2, padding: 4, gap: 3 },
  qtyBtn: { width: 28, height: 28, borderRadius: 9, backgroundColor: theme.colors.surface, alignItems: 'center', justifyContent: 'center' },
  qtyText: { color: theme.colors.text, fontWeight: '900', minWidth: 20, textAlign: 'center' },
  note: { marginTop: 10, height: 36, borderRadius: 10, borderWidth: 1, borderColor: theme.colors.border, backgroundColor: theme.colors.surface2, color: theme.colors.textDim, paddingHorizontal: 10, fontSize: 12, fontWeight: '700' },
  footer: { borderTopWidth: 1, borderTopColor: theme.colors.border, padding: 16, paddingBottom: 26, flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between' },
  totalLabel: { color: theme.colors.textDim, fontSize: 12, fontWeight: '700' },
  total: { color: theme.colors.text, fontSize: 23, fontWeight: '900', marginTop: 2 },
  send: { flexDirection: 'row', alignItems: 'center', gap: 8, backgroundColor: theme.colors.primary, paddingHorizontal: 18, height: 48, borderRadius: 15 },
  sendDisabled: { opacity: 0.45 },
  sendText: { color: theme.colors.white, fontWeight: '900', fontSize: 14 },
});
