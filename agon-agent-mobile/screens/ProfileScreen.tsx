import React from 'react';
import { View, Text, StyleSheet, ScrollView, Pressable } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import Ionicons from '@expo/vector-icons/Ionicons';
import { theme } from '../lib/theme';
import { useStore } from '../lib/store';
import { formatMoney } from '../lib/data';

export default function ProfileScreen() {
  const { tables, tickets } = useStore();
  const myTables = tables.filter((t) => t.server === 'Você' && t.status !== 'free').length;
  const served = 18;
  const sales = 1284.5;
  const tips = 187.3;

  const rows = [
    { icon: 'notifications-outline', label: 'Notificações', color: theme.colors.blue, value: '3' },
    { icon: 'card-outline', label: 'Pagamentos & Gorjetas', color: theme.colors.green },
    { icon: 'time-outline', label: 'Histórico de turnos', color: theme.colors.accent },
    { icon: 'people-outline', label: 'Equipe', color: theme.colors.purple },
    { icon: 'settings-outline', label: 'Configurações', color: theme.colors.textDim },
    { icon: 'help-circle-outline', label: 'Ajuda & Suporte', color: theme.colors.primary },
  ];

  return (
    <SafeAreaView style={styles.safe} edges={['top']}>
      <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={{ paddingBottom: 30 }}>
        <Text style={styles.title}>Perfil</Text>

        <View style={styles.profileCard}>
          <View style={styles.avatar}><Text style={styles.avatarTxt}>LM</Text></View>
          <Text style={styles.name}>Lucas Martins</Text>
          <Text style={styles.role}>Garçom · Salão Principal</Text>
          <View style={styles.ratingRow}>
            {[1,2,3,4,5].map((i) => (
              <Ionicons key={i} name={i <= 4 ? 'star' : 'star-half'} size={15} color={theme.colors.accent} />
            ))}
            <Text style={styles.ratingTxt}>4.8</Text>
          </View>
        </View>

        <View style={styles.statsGrid}>
          <Metric icon="restaurant" color={theme.colors.primary} value={String(myTables)} label="Mesas ativas" />
          <Metric icon="checkmark-done" color={theme.colors.green} value={String(served)} label="Atendidas hoje" />
          <Metric icon="cash" color={theme.colors.blue} value={formatMoney(sales)} label="Vendas" />
          <Metric icon="sparkles" color={theme.colors.accent} value={formatMoney(tips)} label="Gorjetas" />
        </View>

        <View style={styles.goalCard}>
          <View style={styles.goalHead}>
            <Text style={styles.goalTitle}>Meta do turno</Text>
            <Text style={styles.goalPct}>72%</Text>
          </View>
          <View style={styles.goalBar}>
            <View style={[styles.goalFill, { width: '72%' }]} />
          </View>
          <Text style={styles.goalSub}>{formatMoney(sales)} de {formatMoney(1800)}</Text>
        </View>

        <View style={styles.menu}>
          {rows.map((r, i) => (
            <Pressable key={r.label} style={[styles.row, i < rows.length - 1 && styles.rowBorder]}>
              <View style={[styles.rowIcon, { backgroundColor: r.color + '1F' }]}>
                <Ionicons name={r.icon as any} size={19} color={r.color} />
              </View>
              <Text style={styles.rowLabel}>{r.label}</Text>
              {r.value && <View style={styles.rowBadge}><Text style={styles.rowBadgeTxt}>{r.value}</Text></View>}
              <Ionicons name="chevron-forward" size={18} color={theme.colors.textFaint} />
            </Pressable>
          ))}
        </View>

        <Pressable style={styles.logout}>
          <Ionicons name="log-out-outline" size={19} color={theme.colors.red} />
          <Text style={styles.logoutTxt}>Encerrar turno</Text>
        </Pressable>
      </ScrollView>
    </SafeAreaView>
  );
}

function Metric({ icon, color, value, label }: any) {
  return (
    <View style={styles.metric}>
      <View style={[styles.metricIcon, { backgroundColor: color + '1F' }]}>
        <Ionicons name={icon} size={18} color={color} />
      </View>
      <Text style={styles.metricValue}>{value}</Text>
      <Text style={styles.metricLabel}>{label}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  safe: { flex: 1, backgroundColor: theme.colors.bg },
  title: { color: theme.colors.text, fontSize: 30, fontWeight: '800', letterSpacing: -0.5, paddingHorizontal: 20, paddingTop: 8 },
  profileCard: { alignItems: 'center', marginHorizontal: 20, marginTop: 16, backgroundColor: theme.colors.surface, borderRadius: theme.radius.lg, padding: 22, borderWidth: 1, borderColor: theme.colors.border },
  avatar: { width: 74, height: 74, borderRadius: 37, backgroundColor: theme.colors.primary, alignItems: 'center', justifyContent: 'center' },
  avatarTxt: { color: theme.colors.white, fontSize: 26, fontWeight: '800' },
  name: { color: theme.colors.text, fontSize: 20, fontWeight: '800', marginTop: 12 },
  role: { color: theme.colors.textDim, fontSize: 13, fontWeight: '600', marginTop: 3 },
  ratingRow: { flexDirection: 'row', alignItems: 'center', gap: 2, marginTop: 9 },
  ratingTxt: { color: theme.colors.text, fontWeight: '800', fontSize: 13, marginLeft: 6 },
  statsGrid: { flexDirection: 'row', flexWrap: 'wrap', paddingHorizontal: 20, marginTop: 14, gap: 12 },
  metric: { width: '47%', flexGrow: 1, backgroundColor: theme.colors.surface, borderRadius: theme.radius.md, padding: 15, borderWidth: 1, borderColor: theme.colors.border },
  metricIcon: { width: 36, height: 36, borderRadius: 11, alignItems: 'center', justifyContent: 'center', marginBottom: 10 },
  metricValue: { color: theme.colors.text, fontSize: 19, fontWeight: '800' },
  metricLabel: { color: theme.colors.textFaint, fontSize: 12, fontWeight: '600', marginTop: 2 },
  goalCard: { marginHorizontal: 20, marginTop: 14, backgroundColor: theme.colors.surface, borderRadius: theme.radius.md, padding: 16, borderWidth: 1, borderColor: theme.colors.border },
  goalHead: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' },
  goalTitle: { color: theme.colors.text, fontWeight: '700', fontSize: 15 },
  goalPct: { color: theme.colors.green, fontWeight: '800', fontSize: 15 },
  goalBar: { height: 9, backgroundColor: theme.colors.surface2, borderRadius: 6, marginTop: 12, overflow: 'hidden' },
  goalFill: { height: '100%', backgroundColor: theme.colors.green, borderRadius: 6 },
  goalSub: { color: theme.colors.textDim, fontSize: 12.5, fontWeight: '600', marginTop: 9 },
  menu: { marginHorizontal: 20, marginTop: 14, backgroundColor: theme.colors.surface, borderRadius: theme.radius.md, borderWidth: 1, borderColor: theme.colors.border, overflow: 'hidden' },
  row: { flexDirection: 'row', alignItems: 'center', gap: 13, paddingHorizontal: 15, paddingVertical: 14 },
  rowBorder: { borderBottomWidth: 1, borderBottomColor: theme.colors.border },
  rowIcon: { width: 38, height: 38, borderRadius: 11, alignItems: 'center', justifyContent: 'center' },
  rowLabel: { flex: 1, color: theme.colors.text, fontSize: 15, fontWeight: '600' },
  rowBadge: { backgroundColor: theme.colors.red, minWidth: 22, height: 22, borderRadius: 11, alignItems: 'center', justifyContent: 'center', paddingHorizontal: 6, marginRight: 4 },
  rowBadgeTxt: { color: theme.colors.white, fontWeight: '800', fontSize: 12 },
  logout: { flexDirection: 'row', alignItems: 'center', justifyContent: 'center', gap: 8, marginHorizontal: 20, marginTop: 18, paddingVertical: 15, borderRadius: theme.radius.md, backgroundColor: theme.colors.red + '15', borderWidth: 1, borderColor: theme.colors.red + '33' },
  logoutTxt: { color: theme.colors.red, fontWeight: '800', fontSize: 15 },
});
