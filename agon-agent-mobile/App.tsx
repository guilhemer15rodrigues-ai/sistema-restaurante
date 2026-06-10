import 'react-native-gesture-handler';
import { StatusBar } from 'expo-status-bar';
import { useFonts } from 'expo-font';
import Ionicons from '@expo/vector-icons/Ionicons';
import { NavigationContainer, DefaultTheme } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { GestureHandlerRootView } from 'react-native-gesture-handler';
import { SafeAreaProvider } from 'react-native-safe-area-context';
import { View, Text, StyleSheet } from 'react-native';

import { theme } from './lib/theme';
import { StoreProvider, useStore } from './lib/store';
import TablesScreen from './screens/TablesScreen';
import TableDetailScreen from './screens/TableDetailScreen';
import MenuScreen from './screens/MenuScreen';
import KitchenScreen from './screens/KitchenScreen';
import OrdersScreen from './screens/OrdersScreen';
import NotificationsScreen from './screens/NotificationsScreen';

const Stack = createNativeStackNavigator();
const Tab = createBottomTabNavigator();

function TablesStack() {
  return (
    <Stack.Navigator screenOptions={{ headerShown: false, contentStyle: { backgroundColor: theme.colors.bg } }}>
      <Stack.Screen name="TablesHome" component={TablesScreen} />
      <Stack.Screen name="TableDetail" component={TableDetailScreen} />
      <Stack.Screen name="Menu" component={MenuScreen} />
    </Stack.Navigator>
  );
}

function KitchenTabBadge() {
  const { tickets } = useStore();
  const pending = tickets.filter((t) => t.status !== 'ready').length;
  if (pending === 0) return null;
  return (
    <View style={styles.tabBadge}><Text style={styles.tabBadgeTxt}>{pending}</Text></View>
  );
}

function Tabs() {
  return (
    <Tab.Navigator
      screenOptions={({ route }) => ({
        headerShown: false,
        tabBarShowLabel: true,
        tabBarActiveTintColor: theme.colors.primary,
        tabBarInactiveTintColor: theme.colors.textFaint,
        tabBarStyle: {
          backgroundColor: theme.colors.surface,
          borderTopColor: theme.colors.border + 'AA',
          borderTopWidth: 1,
          height: 78,
          paddingTop: 7,
          paddingBottom: 17,
          position: 'absolute',
        },
        tabBarItemStyle: { borderRadius: 16, marginHorizontal: 4, marginVertical: 6 },
        tabBarLabelStyle: { fontSize: 11, fontWeight: '800' },
        tabBarIcon: ({ color, focused }) => {
          const icons: Record<string, string> = {
            Mesas: focused ? 'grid' : 'grid-outline',
            Pedidos: focused ? 'receipt' : 'receipt-outline',
            Cozinha: focused ? 'flame' : 'flame-outline',
            Notificacoes: focused ? 'notifications' : 'notifications-outline',
          };

          if (route.name === 'Cozinha') {
            return (
              <View>
                <Ionicons name={icons[route.name] as any} size={24} color={color} />
                <KitchenTabBadge />
              </View>
            );
          }

          return <Ionicons name={icons[route.name] as any} size={24} color={color} />;
        },
      })}
    >
      <Tab.Screen name="Mesas" component={TablesStack} />
      <Tab.Screen name="Pedidos" component={OrdersScreen} />
      <Tab.Screen name="Cozinha" component={KitchenScreen} />
      <Tab.Screen name="Notificacoes" component={NotificationsScreen} options={{ title: 'Notificações' }} />
    </Tab.Navigator>
  );
}

const navTheme = {
  ...DefaultTheme,
  colors: { ...DefaultTheme.colors, background: theme.colors.bg },
};

export default function App() {
  const [fontsLoaded] = useFonts({ ...Ionicons.font });
  if (!fontsLoaded) return null;

  return (
    <GestureHandlerRootView style={{ flex: 1 }}>
      <SafeAreaProvider>
        <StoreProvider>
          <NavigationContainer theme={navTheme}>
            <StatusBar style="light" />
            <Tabs />
          </NavigationContainer>
        </StoreProvider>
      </SafeAreaProvider>
    </GestureHandlerRootView>
  );
}

const styles = StyleSheet.create({
  tabBadge: {
    position: 'absolute',
    top: -5,
    right: -10,
    backgroundColor: theme.colors.red,
    minWidth: 17,
    height: 17,
    borderRadius: 9,
    alignItems: 'center',
    justifyContent: 'center',
    paddingHorizontal: 4,
  },
  tabBadgeTxt: { color: theme.colors.white, fontSize: 10, fontWeight: '800' },
});
