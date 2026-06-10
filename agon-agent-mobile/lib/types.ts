export type TableStatus = 'free' | 'seated' | 'ordered' | 'served' | 'bill';

export interface TableInfo {
  id: number;
  seats: number;
  status: TableStatus;
  guests: number;
  startedAt?: number;
  server?: string;
  zone: 'Salão' | 'Varanda' | 'VIP';
}

export interface MenuItem {
  id: string;
  name: string;
  desc: string;
  price: number;
  category: string;
  emoji: string;
  popular?: boolean;
}

export interface OrderLine {
  item: MenuItem;
  qty: number;
  notes?: string;
}

export interface KitchenTicket {
  id: string;
  table: number;
  lines: OrderLine[];
  status: 'pending' | 'cooking' | 'ready';
  placedAt: number;
}
