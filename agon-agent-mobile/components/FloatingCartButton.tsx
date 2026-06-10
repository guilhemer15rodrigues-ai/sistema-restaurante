import React from 'react';
import { Pressable, StyleSheet, Text, View } from 'react-native';
import Ionicons from '@expo/vector-icons/Ionicons';
import { theme } from '../lib/theme';
import { formatMoney } from '../lib/data';

export default function FloatingCartButton({
  count,
  total,
  onPress,
}: {
  count: number;
  total: number;
  onPress: () => void;
}) {
  if (count <= 0) return null;

  return (
    <Pressable onPress={onPress} style={({ pressed }) => [styles.button, pressed && styles.pressed]}>
      <View style={styles.iconWrap}>
        <Ionicons name="cart" size={21} color={theme.colors.white} />
        <View style={styles.badge}>
          <Text style={styles.badgeText}>{count}</Text>
        </View>
      </View>
      <Text style={styles.total}>{formatMoney(total)}</Text>
    </Pressable>
  );
}

const styles = StyleSheet.create({
  button: {
    position: 'absolute',
    right: 18,
    bottom: 24,
    minHeight: 54,
    borderRadius: 18,
    backgroundColor: theme.colors.primary,
    paddingLeft: 14,
    paddingRight: 16,
    flexDirection: 'row',
    alignItems: 'center',
    gap: 10,
    ...theme.shadow,
  },
  pressed: { opacity: 0.9, transform: [{ scale: 0.97 }] },
  iconWrap: { width: 30, height: 30, alignItems: 'center', justifyContent: 'center' },
  badge: {
    position: 'absolute',
    top: -8,
    right: -8,
    minWidth: 22,
    height: 22,
    borderRadius: 11,
    backgroundColor: theme.colors.white,
    alignItems: 'center',
    justifyContent: 'center',
    paddingHorizontal: 5,
  },
  badgeText: { color: theme.colors.primary, fontSize: 11, fontWeight: '900' },
  total: { color: theme.colors.white, fontSize: 14, fontWeight: '900' },
});
