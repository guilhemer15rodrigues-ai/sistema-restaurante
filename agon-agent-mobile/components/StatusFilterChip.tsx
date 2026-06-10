import React from 'react';
import { Pressable, StyleSheet, Text } from 'react-native';
import { theme } from '../lib/theme';

export default function StatusFilterChip({
  label,
  active,
  color = theme.colors.primary,
  onPress,
}: {
  label: string;
  active?: boolean;
  color?: string;
  onPress: () => void;
}) {
  return (
    <Pressable
      onPress={onPress}
      style={({ pressed }) => [
        styles.chip,
        active && { backgroundColor: color, borderColor: color },
        pressed && { transform: [{ scale: 0.97 }] },
      ]}
    >
      <Text style={[styles.text, active && styles.textActive]}>{label}</Text>
    </Pressable>
  );
}

const styles = StyleSheet.create({
  chip: {
    height: 34,
    paddingHorizontal: 14,
    borderRadius: 999,
    borderWidth: 1,
    borderColor: theme.colors.border,
    backgroundColor: theme.colors.surface,
    alignItems: 'center',
    justifyContent: 'center',
  },
  text: { color: theme.colors.textDim, fontSize: 12.5, fontWeight: '800' },
  textActive: { color: theme.colors.white },
});
