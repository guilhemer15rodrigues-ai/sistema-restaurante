import React, { createContext, useContext, useState, useCallback } from 'react';
import { TableInfo, KitchenTicket, OrderLine, MenuItem, TableStatus } from './types';
import { INITIAL_TABLES, INITIAL_TICKETS } from './data';

interface Store {
  tables: TableInfo[];
  tickets: KitchenTicket[];
  carts: Record<number, OrderLine[]>;
  setTableStatus: (id: number, status: TableStatus, guests?: number) => void;
  addToCart: (table: number, item: MenuItem) => void;
  removeFromCart: (table: number, itemId: string) => void;
  cartCount: (table: number) => number;
  cartTotal: (table: number) => number;
  sendOrder: (table: number) => void;
  advanceTicket: (id: string) => void;
}

const Ctx = createContext<Store | null>(null);

export function StoreProvider({ children }: { children: React.ReactNode }) {
  const [tables, setTables] = useState<TableInfo[]>(INITIAL_TABLES);
  const [tickets, setTickets] = useState<KitchenTicket[]>(INITIAL_TICKETS);
  const [carts, setCarts] = useState<Record<number, OrderLine[]>>({});

  const setTableStatus = useCallback((id: number, status: TableStatus, guests?: number) => {
    setTables((prev) => prev.map((t) => t.id === id ? {
      ...t, status,
      guests: guests ?? t.guests,
      startedAt: status === 'free' ? undefined : (t.startedAt ?? Date.now()),
      server: status === 'free' ? undefined : (t.server ?? 'Você'),
    } : t));
  }, []);

  const addToCart = useCallback((table: number, item: MenuItem) => {
    setCarts((prev) => {
      const cur = prev[table] ? [...prev[table]] : [];
      const idx = cur.findIndex((l) => l.item.id === item.id);
      if (idx >= 0) cur[idx] = { ...cur[idx], qty: cur[idx].qty + 1 };
      else cur.push({ item, qty: 1 });
      return { ...prev, [table]: cur };
    });
  }, []);

  const removeFromCart = useCallback((table: number, itemId: string) => {
    setCarts((prev) => {
      const cur = prev[table] ? [...prev[table]] : [];
      const idx = cur.findIndex((l) => l.item.id === itemId);
      if (idx < 0) return prev;
      if (cur[idx].qty > 1) cur[idx] = { ...cur[idx], qty: cur[idx].qty - 1 };
      else cur.splice(idx, 1);
      return { ...prev, [table]: cur };
    });
  }, []);

  const cartCount = useCallback((table: number) => (carts[table] || []).reduce((s, l) => s + l.qty, 0), [carts]);
  const cartTotal = useCallback((table: number) => (carts[table] || []).reduce((s, l) => s + l.qty * l.item.price, 0), [carts]);

  const sendOrder = useCallback((table: number) => {
    setCarts((prev) => {
      const lines = prev[table] || [];
      if (lines.length === 0) return prev;
      setTickets((tk) => [{
        id: 'tk' + Date.now(), table, lines, status: 'pending', placedAt: Date.now(),
      }, ...tk]);
      setTables((tb) => tb.map((t) => t.id === table ? { ...t, status: 'ordered' } : t));
      const next = { ...prev };
      delete next[table];
      return next;
    });
  }, []);

  const advanceTicket = useCallback((id: string) => {
    setTickets((prev) => prev.map((t) => {
      if (t.id !== id) return t;
      const order: KitchenTicket['status'][] = ['pending', 'cooking', 'ready'];
      const i = order.indexOf(t.status);
      return { ...t, status: order[Math.min(i + 1, 2)] };
    }));
  }, []);

  return (
    <Ctx.Provider value={{ tables, tickets, carts, setTableStatus, addToCart, removeFromCart, cartCount, cartTotal, sendOrder, advanceTicket }}>
      {children}
    </Ctx.Provider>
  );
}

export function useStore() {
  const c = useContext(Ctx);
  if (!c) throw new Error('useStore must be inside StoreProvider');
  return c;
}
