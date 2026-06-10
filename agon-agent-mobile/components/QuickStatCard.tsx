import React from 'react';
import { Pressable, StyleSheet, Text, View } from 'react-native';
import Ionicons from '@expo/vector-icons/Ionicons';
import { theme } from '../lib/theme';

export default function QuickStatCard({
  icon,
  label,
  value,
  color,
  active,
  onPress,
}: {
  icon: string;
  label: string;
  value: string | number;
  color: string;
  active?: boolean;
  onPress?: () => void;
}) {
  return (
    <Pressable
      onPress={onPress}
      style={({ pressed }) => [
        styles.card,
        { borderLeftColor: color },
        active && { borderColor: color + '88', backgroundColor: color + '14' },
        pressed && styles.pressed,
      ]}
    >
      <View>
        <Text style={styles.value}>{value}</Text>
        <Text style={styles.label}>{label}</Text>
      </View>
      <View style={[styles.icon, { backgroundColor: color + '1F' }]}>
        <Ionicons name={icon as any} size={18} color={color} />
      </View>
    </Pressable>
  );
}

const styles = StyleSheet.create({
  card: {
    width: '48%',
    flexGrow: 1,
    minHeight: 76,
    borderRadius: theme.radius.md,
    borderWidth: 1,
    borderLeftWidth: 4,
    borderColor: theme.colors.border,
    backgroundColor: theme.colors.surface,
    padding: 12,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    ...theme.shadow,
    shadowOpacity: 0.12,
    elevation: 3,
  },
  pressed: { opacity: 0.86, transform: [{ scale: 0.98 }] },
  value: { color: theme.colors.text, fontSize: 22, fontWeight: '900', lineHeight: 24 },
  label: { color: theme.colors.textDim, fontSize: 11, fontWeight: '800', marginTop: 5 },
  icon: { width: 36, height: 36, borderRadius: 12, alignItems: 'center', justifyContent: 'center' },
});
