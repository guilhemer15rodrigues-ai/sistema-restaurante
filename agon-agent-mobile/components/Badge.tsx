import React from 'react';
import { View, Text, StyleSheet } from 'react-native';

export default function Badge({ label, color, compact = false }: { label: string; color: string; compact?: boolean }) {
  return (
    <View style={[styles.wrap, compact && styles.compact, { backgroundColor: color + '22', borderColor: color + '42' }]}>
      <View style={[styles.dot, { backgroundColor: color }]} />
      <Text style={[styles.text, compact && styles.textCompact, { color }]}>{label}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  wrap: { alignSelf: 'flex-start', flexDirection: 'row', alignItems: 'center', paddingHorizontal: 9, paddingVertical: 4, borderRadius: 20, borderWidth: 1 },
  compact: { paddingHorizontal: 8, paddingVertical: 3 },
  dot: { width: 6, height: 6, borderRadius: 3, marginRight: 5 },
  text: { fontSize: 11.5, fontWeight: '700' },
  textCompact: { fontSize: 10.5, fontWeight: '800' },
});
