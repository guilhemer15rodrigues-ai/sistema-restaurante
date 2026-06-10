import { MenuItem, TableInfo, KitchenTicket } from './types';

export const CATEGORIES = ['Destaques', 'Entradas', 'Principais', 'Pizzas', 'Bebidas', 'Sobremesas'];

export const MENU: MenuItem[] = [
  { id: 'm1', name: 'Bruschetta Trio', desc: 'Tomate, pesto e cogumelos', price: 28, category: 'Entradas', emoji: '🥖', popular: true },
  { id: 'm2', name: 'Carpaccio', desc: 'Filé, parmesão e rúcula', price: 42, category: 'Entradas', emoji: '🥩' },
  { id: 'm3', name: 'Bolinho de Bacalhau', desc: '6 unidades crocantes', price: 36, category: 'Entradas', emoji: '🐟' },
  { id: 'm4', name: 'Risoto de Funghi', desc: 'Arbóreo, mix de cogumelos', price: 64, category: 'Principais', emoji: '🍚', popular: true },
  { id: 'm5', name: 'Filé ao Poivre', desc: 'Molho pimenta, batata rústica', price: 89, category: 'Principais', emoji: '🍽️' },
  { id: 'm6', name: 'Salmão Grelhado', desc: 'Legumes e molho de maracujá', price: 78, category: 'Principais', emoji: '🍤' },
  { id: 'm7', name: 'Margherita', desc: 'Mozzarella de búfala, manjericão', price: 52, category: 'Pizzas', emoji: '🍕', popular: true },
  { id: 'm8', name: 'Pepperoni', desc: 'Pepperoni picante extra', price: 58, category: 'Pizzas', emoji: '🍕' },
  { id: 'm9', name: 'Quatro Queijos', desc: 'Gorgonzola, parmesão, provolone', price: 56, category: 'Pizzas', emoji: '🧀' },
  { id: 'm10', name: 'Chopp Artesanal', desc: 'IPA, 500ml', price: 22, category: 'Bebidas', emoji: '🍺' },
  { id: 'm11', name: 'Vinho Malbec', desc: 'Taça 150ml', price: 34, category: 'Bebidas', emoji: '🍷' },
  { id: 'm12', name: 'Suco Natural', desc: 'Laranja, abacaxi ou limão', price: 14, category: 'Bebidas', emoji: '🧃' },
  { id: 'm13', name: 'Água com Gás', desc: '500ml', price: 8, category: 'Bebidas', emoji: '💧' },
  { id: 'm14', name: 'Petit Gâteau', desc: 'Sorvete de creme', price: 32, category: 'Sobremesas', emoji: '🍫', popular: true },
  { id: 'm15', name: 'Cheesecake', desc: 'Calda de frutas vermelhas', price: 28, category: 'Sobremesas', emoji: '🍰' },
  { id: 'm16', name: 'Tiramisù', desc: 'Clássico italiano', price: 30, category: 'Sobremesas', emoji: '☕' },
];

export const INITIAL_TABLES: TableInfo[] = [
  { id: 1, seats: 2, status: 'free', guests: 0, zone: 'Salão' },
  { id: 2, seats: 4, status: 'ordered', guests: 3, startedAt: Date.now() - 22 * 60000, server: 'Você', zone: 'Salão' },
  { id: 3, seats: 4, status: 'seated', guests: 4, startedAt: Date.now() - 5 * 60000, server: 'Você', zone: 'Salão' },
  { id: 4, seats: 2, status: 'free', guests: 0, zone: 'Salão' },
  { id: 5, seats: 6, status: 'served', guests: 5, startedAt: Date.now() - 48 * 60000, server: 'Bruno', zone: 'Varanda' },
  { id: 6, seats: 4, status: 'bill', guests: 2, startedAt: Date.now() - 71 * 60000, server: 'Você', zone: 'Varanda' },
  { id: 7, seats: 2, status: 'free', guests: 0, zone: 'Varanda' },
  { id: 8, seats: 8, status: 'seated', guests: 7, startedAt: Date.now() - 12 * 60000, server: 'Carla', zone: 'VIP' },
  { id: 9, seats: 4, status: 'free', guests: 0, zone: 'VIP' },
  { id: 10, seats: 2, status: 'ordered', guests: 2, startedAt: Date.now() - 34 * 60000, server: 'Você', zone: 'VIP' },
  { id: 11, seats: 4, status: 'free', guests: 0, zone: 'Salão' },
  { id: 12, seats: 6, status: 'free', guests: 0, zone: 'Varanda' },
];

export const INITIAL_TICKETS: KitchenTicket[] = [
  {
    id: 't1', table: 2, status: 'cooking', placedAt: Date.now() - 14 * 60000,
    lines: [
      { item: MENU[3], qty: 1 },
      { item: MENU[6], qty: 1, notes: 'Sem manjericão' },
      { item: MENU[9], qty: 2 },
    ],
  },
  {
    id: 't2', table: 10, status: 'pending', placedAt: Date.now() - 3 * 60000,
    lines: [
      { item: MENU[0], qty: 1 },
      { item: MENU[10], qty: 2 },
    ],
  },
  {
    id: 't3', table: 5, status: 'ready', placedAt: Date.now() - 25 * 60000,
    lines: [
      { item: MENU[4], qty: 2 },
      { item: MENU[13], qty: 1 },
    ],
  },
];

export const STATUS_META: Record<string, { label: string; color: string }> = {
  free: { label: 'Livre', color: '#39D98A' },
  seated: { label: 'Ocupada', color: '#4D9DFF' },
  ordered: { label: 'Em preparo', color: '#FFC14D' },
  served: { label: 'Servido', color: '#A78BFA' },
  bill: { label: 'Conta', color: '#FF7A45' },
};

export function formatMoney(v: number) {
  return 'R$ ' + v.toFixed(2).replace('.', ',');
}

export function elapsed(ts?: number) {
  if (!ts) return '—';
  const m = Math.floor((Date.now() - ts) / 60000);
  if (m < 1) return 'agora';
  if (m < 60) return m + ' min';
  return Math.floor(m / 60) + 'h ' + (m % 60) + 'm';
}
