@extends('layouts.app')
@section('page-title', 'Area do Garcom')
@section('breadcrumb', 'Mesas e pedidos')

@section('styles')
<style>
.garcom-mobile-shell {
    max-width: 520px;
    margin: 0 auto;
    padding-bottom: 106px;
}
.HeaderMobileGarcom {
    position: sticky;
    top: 62px;
    z-index: 20;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin: -10px -4px 12px;
    padding: 12px 4px 13px;
    border-bottom: 1px solid rgba(250,178,105,.1);
    background:
        linear-gradient(180deg, rgba(28,17,8,.98), rgba(18,13,9,.9)),
        var(--bg2);
    backdrop-filter: blur(14px);
}
.HeaderMobileGarcom h2 {
    margin: 0;
    color: #fff;
    font-size: 22px;
    font-weight: 900;
    line-height: 1.05;
}
.HeaderMobileGarcom p {
    margin: 3px 0 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.2;
}
.garcom-head-actions {
    display: flex;
    align-items: center;
    gap: 7px;
}
.garcom-icon-btn {
    width: 40px;
    height: 40px;
    display: inline-grid;
    place-items: center;
    border: 1px solid var(--border);
    border-radius: 10px;
    background: rgba(250,178,105,.06);
    color: var(--cream);
    cursor: pointer;
    box-shadow: 0 10px 28px rgba(0,0,0,.16);
}
.garcom-logout {
    min-height: 40px;
    border: 1px solid rgba(236,45,1,.24);
    border-radius: 10px;
    background: rgba(236,45,1,.09);
    color: #fca5a5;
    padding: 0 10px;
    font-weight: 900;
    cursor: pointer;
}
.garcom-shift {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 7px;
    padding: 5px 9px;
    border: 1px solid rgba(62,95,60,.3);
    border-radius: 999px;
    background: rgba(62,95,60,.16);
    color: #86efac;
    font-size: 11px;
    font-weight: 900;
}
.garcom-section {
    margin-bottom: 14px;
}
.garcom-section-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 9px;
    color: var(--muted);
    font-size: 11px;
    font-weight: 900;
    letter-spacing: 1px;
    text-transform: uppercase;
}
.garcom-summary-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 8px;
}
.CardResumoStatus {
    min-height: 76px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    border: 1px solid var(--border);
    border-left: 4px solid var(--status-color);
    border-radius: 12px;
    background: linear-gradient(180deg, rgba(255,255,255,.035), rgba(255,255,255,.015)), var(--bg2);
    color: inherit;
    padding: 11px;
    cursor: pointer;
    text-align: left;
}
.CardResumoStatus.active {
    border-color: color-mix(in srgb, var(--status-color) 44%, var(--border));
    background: color-mix(in srgb, var(--status-color) 10%, var(--bg2));
}
.CardResumoStatus,
.FiltroStatusMesas button,
.CardMesa,
.mesa-action-btn,
.BottomNavigationGarcom a,
.BottomNavigationGarcom button {
    transition: transform .16s ease, border-color .16s ease, background .16s ease, color .16s ease;
}
.CardResumoStatus:active,
.FiltroStatusMesas button:active,
.mesa-action-btn:active {
    transform: scale(.98);
}
.CardResumoStatus i {
    width: 34px;
    height: 34px;
    display: grid;
    place-items: center;
    border-radius: 9px;
    background: color-mix(in srgb, var(--status-color) 13%, transparent);
    color: var(--status-color);
}
.CardResumoStatus strong {
    display: block;
    color: #fff;
    font-size: 25px;
    line-height: 1;
}
.CardResumoStatus span {
    display: block;
    margin-top: 4px;
    color: var(--muted);
    font-size: 12px;
    font-weight: 800;
}
.FiltroStatusMesas {
    display: flex;
    gap: 7px;
    overflow-x: auto;
    padding-bottom: 3px;
    -webkit-overflow-scrolling: touch;
}
.FiltroStatusMesas button {
    flex: 0 0 auto;
    min-height: 36px;
    border: 1px solid var(--border);
    border-radius: 999px;
    background: var(--bg2);
    color: var(--muted);
    padding: 0 12px;
    font-size: 12px;
    font-weight: 900;
    cursor: pointer;
}
.FiltroStatusMesas button.active {
    border-color: rgba(250,178,105,.42);
    background: rgba(250,178,105,.12);
    color: var(--cream);
}
.garcom-search {
    width: 100%;
    min-height: 39px;
    margin-top: 8px;
    border: 1px solid var(--border);
    border-radius: 10px;
    background: var(--bg2);
    color: #fff;
    padding: 8px 11px;
    outline: none;
    font: 800 13px inherit;
}
.garcom-mesas-grid {
    display: grid;
    gap: 10px;
}
.CardMesa {
    border: 1px solid var(--border);
    border-left: 4px solid var(--status-color);
    border-radius: 12px;
    background: linear-gradient(180deg, rgba(255,255,255,.035), rgba(255,255,255,.012)), var(--bg2);
    padding: 11px;
    box-shadow: 0 12px 30px rgba(0,0,0,.12);
}
.CardMesa.is-hidden {
    display: none;
}
.mesa-card-top {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    align-items: flex-start;
}
.mesa-card-number {
    color: #fff;
    font-size: 25px;
    font-weight: 900;
    line-height: 1;
}
.mesa-card-number small {
    display: block;
    margin-bottom: 3px;
    color: var(--muted);
    font-size: 10px;
    font-weight: 900;
    text-transform: uppercase;
}
.StatusPedidoBadge,
.mesa-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    min-height: 27px;
    border-radius: 999px;
    background: color-mix(in srgb, var(--status-color) 13%, transparent);
    color: #fff;
    padding: 0 9px;
    font-size: 10px;
    font-weight: 900;
    text-transform: uppercase;
    white-space: nowrap;
}
.mesa-status-pill i {
    color: var(--status-color);
}
.mesa-card-info {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 6px;
    margin: 11px 0;
}
.mesa-card-info div {
    min-width: 0;
    border: 1px solid rgba(250,178,105,.08);
    border-radius: 8px;
    background: rgba(255,255,255,.025);
    padding: 7px;
}
.mesa-card-info span {
    display: block;
    color: var(--muted);
    font-size: 10px;
    font-weight: 900;
    text-transform: uppercase;
}
.mesa-card-info strong {
    display: block;
    overflow: hidden;
    margin-top: 2px;
    color: #fff;
    font-size: 13px;
    font-weight: 900;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.mesa-card-actions {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 6px;
}
.mesa-card-actions.one {
    grid-template-columns: 1fr;
}
.mesa-action-btn {
    min-height: 39px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    border: 1px solid var(--border);
    border-radius: 9px;
    background: rgba(255,255,255,.035);
    color: var(--cream);
    padding: 0 8px;
    text-decoration: none;
    font-size: 11px;
    font-weight: 900;
    cursor: pointer;
    text-align: center;
}
.mesa-action-btn.primary {
    border-color: rgba(236,45,1,.34);
    background: var(--red);
    color: #fff;
}
.mesa-action-btn.warning {
    border-color: rgba(250,178,105,.34);
    background: rgba(250,178,105,.12);
    color: var(--gold);
}
.mesa-action-btn.success {
    border-color: rgba(62,95,60,.36);
    background: rgba(62,95,60,.18);
    color: #86efac;
}
.mesa-action-btn[disabled] {
    opacity: .55;
    cursor: not-allowed;
}
.ListaPedidosMesa {
    display: grid;
    gap: 8px;
}
.pedido-mini-card {
    display: grid;
    gap: 7px;
    border: 1px solid var(--border);
    border-radius: 10px;
    background: var(--bg2);
    padding: 10px;
}
.pedido-mini-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}
.pedido-mini-top strong {
    color: var(--accent);
    font-family: monospace;
}
.pedido-mini-line {
    display: flex;
    justify-content: space-between;
    gap: 8px;
    color: var(--muted);
    font-size: 12px;
}
.NotificacaoGarcomItem {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: linear-gradient(180deg, rgba(255,255,255,.035), rgba(255,255,255,.012)), var(--bg2);
    padding: 10px;
}
.NotificacaoGarcomItem i {
    width: 32px;
    height: 32px;
    display: grid;
    place-items: center;
    border-radius: 8px;
    background: rgba(250,178,105,.1);
    color: var(--gold);
    flex: 0 0 auto;
}
.NotificacaoGarcomItem strong {
    display: block;
    color: #fff;
    font-size: 13px;
    line-height: 1.25;
}
.NotificacaoGarcomItem span {
    color: var(--muted);
    font-size: 12px;
}
.ConfirmacaoSolicitarConta {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: none;
    align-items: flex-end;
    background: rgba(0,0,0,.64);
}
.ConfirmacaoSolicitarConta.open {
    display: flex;
}
.confirmacao-card {
    width: 100%;
    max-width: 520px;
    margin: 0 auto;
    border: 1px solid var(--border);
    border-radius: 16px 16px 0 0;
    background: var(--bg2);
    padding: 18px;
}
.confirmacao-card h3 {
    margin: 0 0 6px;
    color: #fff;
    font-size: 19px;
}
.confirmacao-card p {
    margin: 0 0 16px;
    color: var(--muted);
    font-size: 13px;
}
.confirmacao-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 9px;
}
.BotaoAcaoRapida {
    position: fixed;
    left: 50%;
    bottom: 70px;
    z-index: 30;
    width: min(244px, calc(100% - 144px));
    min-height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: 0;
    border-radius: 999px;
    background: var(--red);
    color: #fff;
    box-shadow: 0 16px 38px rgba(0,0,0,.38);
    font-weight: 900;
    text-decoration: none;
    transform: translateX(-50%);
}
.BottomNavigationGarcom {
    position: fixed;
    left: 50%;
    bottom: 0;
    z-index: 31;
    width: min(520px, 100%);
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    border-top: 1px solid var(--border);
    background: rgba(18,13,9,.96);
    backdrop-filter: blur(14px);
    box-shadow: 0 -14px 34px rgba(0,0,0,.28);
}
.BottomNavigationGarcom a,
.BottomNavigationGarcom button {
    min-height: 60px;
    display: grid;
    place-items: center;
    gap: 2px;
    border: 0;
    background: transparent;
    color: var(--muted);
    text-decoration: none;
    font-size: 10px;
    font-weight: 900;
    cursor: pointer;
}
.BottomNavigationGarcom i {
    font-size: 17px;
}
.BottomNavigationGarcom .active {
    color: var(--gold);
}
.BottomNavigationGarcom .active i {
    width: 34px;
    height: 24px;
    display: grid;
    place-items: center;
    border-radius: 999px;
    background: rgba(250,178,105,.12);
}
.garcom-empty {
    border: 1px dashed var(--border);
    border-radius: 10px;
    background: var(--bg2);
    padding: 24px 12px;
    color: var(--muted);
    text-align: center;
}
.ModalNovoPedido,
.CardProdutoPedido,
.CarrinhoPedidoFixo {
    display: none;
}
@media (min-width: 769px) {
    .garcom-mobile-shell {
        max-width: 980px;
    }
    .garcom-mesas-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    }
    .HeaderMobileGarcom {
        top: 62px;
    }
}
@media (max-width: 768px) {
    .content {
        padding: 12px 12px 0;
    }
    .topbar {
        display: none;
    }
    .HeaderMobileGarcom {
        top: 0;
    }
    .mesa-card-actions {
        grid-template-columns: 1fr;
    }
    .mesa-card-info {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
</style>
@endsection

@section('content')
@php
    $statusLabels = [
        'aberto' => 'Aberto',
        'em_preparo' => 'Em preparo',
        'pronto' => 'Pronto',
        'pronto_entrega' => 'Pronto p/ entrega',
        'entregue' => 'Entregue',
        'aguardando_pagamento' => 'Aguardando conta',
        'pago' => 'Pago',
        'cancelado' => 'Cancelado',
    ];
    $statusClasses = [
        'aberto' => 'secondary',
        'em_preparo' => 'warning',
        'pronto' => 'success',
        'pronto_entrega' => 'success',
        'entregue' => 'info',
        'aguardando_pagamento' => 'primary',
        'pago' => 'info',
        'cancelado' => 'danger',
    ];
    $mesaCards = $mesas->map(function ($mesa) {
        $orders = $mesa->orders;
        $temPedido = $orders->isNotEmpty();
        $contaSolicitada = $orders->contains('status', 'aguardando_pagamento');
        $pedidoPronto = $orders->contains('status', 'pronto_entrega');
        $emPreparo = $orders->contains(fn($order) => in_array($order->status, ['aberto', 'em_preparo', 'pronto']));
        $primeiroPedido = $orders->sortBy('created_at')->first();
        $abertura = $primeiroPedido?->created_at;
        $total = $orders->sum('total');
        $itens = $orders->sum(fn($order) => $order->items->sum('quantidade'));
        $responsavel = $mesa->garcom->name ?? $primeiroPedido?->user?->name ?? 'Livre';

        if ($contaSolicitada) {
            $uiStatus = 'aguardando_conta';
            $statusLabel = 'Aguardando conta';
            $statusColor = '#ef4444';
            $statusIcon = 'fa-file-invoice-dollar';
        } elseif ($pedidoPronto) {
            $uiStatus = 'pedido_pronto';
            $statusLabel = 'Pedido pronto';
            $statusColor = '#22c55e';
            $statusIcon = 'fa-bell-concierge';
        } elseif ($emPreparo || $mesa->status === 'ocupada') {
            $uiStatus = 'ocupada';
            $statusLabel = $emPreparo ? 'Pedido em preparo' : 'Ocupada';
            $statusColor = '#f97316';
            $statusIcon = 'fa-fire-burner';
        } elseif ($mesa->status === 'reservada') {
            $uiStatus = 'reservada';
            $statusLabel = 'Reservada';
            $statusColor = '#94a3b8';
            $statusIcon = 'fa-bookmark';
        } else {
            $uiStatus = 'livre';
            $statusLabel = 'Livre';
            $statusColor = '#22c55e';
            $statusIcon = 'fa-check';
        }

        return [
            'mesa' => $mesa,
            'orders' => $orders,
            'temPedido' => $temPedido,
            'contaSolicitada' => $contaSolicitada,
            'pedidoPronto' => $pedidoPronto,
            'emPreparo' => $emPreparo,
            'primeiroPedido' => $primeiroPedido,
            'abertura' => $abertura,
            'total' => $total,
            'itens' => $itens,
            'responsavel' => $responsavel,
            'uiStatus' => $uiStatus,
            'statusLabel' => $statusLabel,
            'statusColor' => $statusColor,
            'statusIcon' => $statusIcon,
        ];
    });
    $livres = $mesaCards->where('uiStatus', 'livre')->count();
    $ocupadas = $mesaCards->where('uiStatus', 'ocupada')->count();
    $reservadas = $mesaCards->where('uiStatus', 'reservada')->count();
    $aguardandoConta = $mesaCards->where('uiStatus', 'aguardando_conta')->count();
    $pedidosProntos = $mesaCards->where('uiStatus', 'pedido_pronto')->count();
@endphp

<div class="garcom-mobile-shell">
    <header class="HeaderMobileGarcom">
        <div>
            <h2>Ola, {{ strtok(Auth::user()?->name ?? 'Garcom', ' ') }}</h2>
            <p>Mesas e atendimentos</p>
            <span class="garcom-shift"><i class="fa-solid fa-circle"></i> Em atendimento</span>
        </div>
        <div class="garcom-head-actions">
            <button type="button" class="garcom-icon-btn" onclick="scrollToSection('garcomNotificacoes')" aria-label="Notificacoes">
                <i class="fa-solid fa-bell"></i>
            </button>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="garcom-logout">Sair</button>
            </form>
        </div>
    </header>

    <section class="garcom-section" aria-label="Resumo rapido">
        <div class="garcom-summary-grid">
            <button type="button" class="CardResumoStatus" style="--status-color:#22c55e" data-filter-button="livre">
                <div><strong>{{ $livres }}</strong><span>Mesas livres</span></div>
                <i class="fa-solid fa-chair"></i>
            </button>
            <button type="button" class="CardResumoStatus" style="--status-color:#f97316" data-filter-button="ocupada">
                <div><strong>{{ $ocupadas }}</strong><span>Ocupadas</span></div>
                <i class="fa-solid fa-users"></i>
            </button>
            <button type="button" class="CardResumoStatus" style="--status-color:#22c55e" data-filter-button="pedido_pronto">
                <div><strong>{{ $pedidosProntos }}</strong><span>Pedidos prontos</span></div>
                <i class="fa-solid fa-bell-concierge"></i>
            </button>
            <button type="button" class="CardResumoStatus" style="--status-color:#ef4444" data-filter-button="aguardando_conta">
                <div><strong>{{ $aguardandoConta }}</strong><span>Aguardando conta</span></div>
                <i class="fa-solid fa-receipt"></i>
            </button>
        </div>
    </section>

    <section class="garcom-section" id="garcomMesas">
        <div class="garcom-section-title">
            <span>Mapa de mesas</span>
            <span>{{ $mesas->count() }} mesas</span>
        </div>
        <div class="FiltroStatusMesas" aria-label="Filtros de mesas">
            <button type="button" class="active" data-filter-button="todas">Todas</button>
            <button type="button" data-filter-button="livre">Livres</button>
            <button type="button" data-filter-button="ocupada">Ocupadas</button>
            <button type="button" data-filter-button="reservada">Reservadas</button>
            <button type="button" data-filter-button="aguardando_conta">Aguardando conta</button>
            <button type="button" data-filter-button="pedido_pronto">Pedido pronto</button>
        </div>
        <input type="search" class="garcom-search" id="garcomMesaSearch" placeholder="Buscar mesa">
    </section>

    <section class="garcom-mesas-grid" aria-label="Lista de mesas">
        @forelse($mesaCards as $card)
        @php
            $mesa = $card['mesa'];
            $orders = $card['orders'];
            $abertaHa = $card['abertura'] ? $card['abertura']->diffForHumans(null, true) : 'Livre';
            $pessoas = $card['temPedido'] ? max(1, min($mesa->assentos, $card['itens'])) : $mesa->assentos;
        @endphp
        <article class="CardMesa"
                 style="--status-color:{{ $card['statusColor'] }}"
                 data-mesa-card
                 data-status="{{ $card['uiStatus'] }}"
                 data-preparo="{{ $card['emPreparo'] ? '1' : '0' }}"
                 data-pronto="{{ $card['pedidoPronto'] ? '1' : '0' }}"
                 data-number="{{ $mesa->numero }}">
            <div class="mesa-card-top">
                <div class="mesa-card-number"><small>Mesa</small>{{ str_pad($mesa->numero, 2, '0', STR_PAD_LEFT) }}</div>
                <span class="mesa-status-pill"><i class="fa-solid {{ $card['statusIcon'] }}"></i>{{ $card['statusLabel'] }}</span>
            </div>

            <div class="mesa-card-info">
                <div><span>Pessoas</span><strong>{{ $pessoas }} {{ $pessoas == 1 ? 'pessoa' : 'pessoas' }}</strong></div>
                <div><span>Aberta ha</span><strong>{{ $abertaHa }}</strong></div>
                <div><span>Total parcial</span><strong>R$ {{ number_format($card['total'], 2, ',', '.') }}</strong></div>
                <div><span>Responsavel</span><strong>{{ $card['responsavel'] }}</strong></div>
            </div>

            @if($card['contaSolicitada'])
            <div class="NotificacaoGarcomItem" style="margin-bottom:10px">
                <i class="fa-solid fa-hourglass-half"></i>
                <div>
                    <strong>Conta solicitada</strong>
                    <span>O caixa ja recebeu esta mesa para pagamento.</span>
                </div>
            </div>
            @elseif($card['uiStatus'] === 'reservada')
            <div class="NotificacaoGarcomItem" style="margin-bottom:10px">
                <i class="fa-solid fa-bookmark"></i>
                <div>
                    <strong>Mesa reservada</strong>
                    <span>Use o painel de mesas para liberar ou manter a reserva.</span>
                </div>
            </div>
            @endif

            @if($card['temPedido'])
            <div class="mesa-card-actions">
                <a href="{{ route('mesas.conta', $mesa) }}" class="mesa-action-btn success">
                    <i class="fa-solid fa-eye"></i> Ver pedido
                </a>
                @if(!$card['contaSolicitada'])
                <a href="{{ route('orders.create', ['table_id' => $mesa->id]) }}" class="mesa-action-btn primary">
                    <i class="fa-solid fa-plus"></i> Pedido
                </a>
                <button type="button"
                        class="mesa-action-btn warning"
                        data-conta-url="{{ route('mesas.fechar-conta', $mesa) }}"
                        data-mesa-numero="{{ $mesa->numero }}"
                        onclick="abrirConfirmacaoConta(this)">
                    <i class="fa-solid fa-receipt"></i> Conta
                </button>
                @else
                <button type="button" class="mesa-action-btn warning" disabled>
                    <i class="fa-solid fa-hourglass-half"></i> Aguardando
                </button>
                @endif
            </div>
            @else
            <div class="mesa-card-actions one">
                <a href="{{ route('orders.create', ['table_id' => $mesa->id]) }}" class="mesa-action-btn primary">
                    <i class="fa-solid fa-plus"></i> Abrir mesa
                </a>
            </div>
            @endif
        </article>
        @empty
        <div class="garcom-empty">Nenhuma mesa cadastrada.</div>
        @endforelse
    </section>

    <section class="garcom-section" id="garcomPedidos" style="margin-top:16px">
        <div class="garcom-section-title">
            <span>Pedidos da mesa</span>
            <a href="{{ route('dashboard.pedidos') }}" style="color:var(--gold);text-decoration:none">Ver todos</a>
        </div>
        <div class="ListaPedidosMesa">
            @forelse($pedidosGarcom->take(6) as $pedido)
            <a href="{{ route('orders.show', $pedido) }}" class="pedido-mini-card" style="text-decoration:none;color:inherit">
                <div class="pedido-mini-top">
                    <strong>#{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</strong>
                    <span class="StatusPedidoBadge badge-{{ $statusClasses[$pedido->status] ?? 'secondary' }}">
                        {{ $statusLabels[$pedido->status] ?? ucfirst(str_replace('_', ' ', $pedido->status)) }}
                    </span>
                </div>
                <div class="pedido-mini-line"><span>Mesa {{ $pedido->table->numero ?? '-' }}</span><span>{{ $pedido->items->count() }} item(ns)</span></div>
                <div class="pedido-mini-line"><span>{{ $pedido->created_at->format('H:i') }}</span><strong>R$ {{ number_format($pedido->total, 2, ',', '.') }}</strong></div>
            </a>
            @empty
            <div class="garcom-empty">Nenhum pedido seu hoje.</div>
            @endforelse
        </div>
    </section>

    <section class="garcom-section" id="garcomNotificacoes">
        <div class="garcom-section-title">
            <span>Notificacoes</span>
            <span>{{ $pedidosProntosPagamento->count() + $aguardandoConta }}</span>
        </div>
        <div class="ListaPedidosMesa" id="garcomNotificationList">
            @forelse($pedidosProntosPagamento->take(5) as $pedido)
            <div class="NotificacaoGarcomItem" data-ready-order="{{ $pedido->id }}">
                <i class="fa-solid fa-bell-concierge"></i>
                <div style="flex:1">
                    <strong>Mesa {{ $pedido->table->numero ?? '-' }}: pedido pronto para entrega</strong>
                    <span>#{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }} · {{ $pedido->items->count() }} item(ns)</span>
                    <form method="POST" action="{{ route('orders.updateStatus', $pedido) }}" class="js-ready-deliver" style="margin-top:8px">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="entregue">
                        <button type="submit" class="mesa-action-btn success" style="width:100%">
                            <i class="fa-solid fa-check"></i> Marcar entregue
                        </button>
                    </form>
                </div>
            </div>
            @empty
                @if($aguardandoConta > 0)
                <div class="NotificacaoGarcomItem">
                    <i class="fa-solid fa-receipt"></i>
                    <div>
                        <strong>{{ $aguardandoConta }} mesa(s) aguardando caixa</strong>
                        <span>Acompanhe o pagamento pela aba de mesas.</span>
                    </div>
                </div>
                @else
                <div class="garcom-empty">Nenhuma notificacao agora.</div>
                @endif
            @endforelse
        </div>
    </section>
</div>

<a href="{{ route('orders.create') }}" class="BotaoAcaoRapida">
    <i class="fa-solid fa-plus"></i> Novo pedido
</a>

<nav class="BottomNavigationGarcom" aria-label="Navegacao inferior do garcom">
    <button type="button" class="active" onclick="scrollToSection('garcomMesas')">
        <i class="fa-solid fa-chair"></i><span>Mesas</span>
    </button>
    <button type="button" onclick="scrollToSection('garcomPedidos')">
        <i class="fa-solid fa-receipt"></i><span>Pedidos</span>
    </button>
    <a href="{{ route('dashboard.cozinha') }}">
        <i class="fa-solid fa-fire-burner"></i><span>Cozinha</span>
    </a>
    <button type="button" onclick="scrollToSection('garcomNotificacoes')">
        <i class="fa-solid fa-bell"></i><span>Notificacoes</span>
    </button>
</nav>

<div class="ConfirmacaoSolicitarConta" id="confirmacaoSolicitarConta" aria-hidden="true">
    <div class="confirmacao-card">
        <h3>Solicitar conta</h3>
        <p id="confirmacaoContaTexto">Deseja solicitar o fechamento da conta?</p>
        <form method="POST" id="confirmacaoContaForm">
            @csrf
            <div class="confirmacao-actions">
                <button type="button" class="mesa-action-btn" onclick="fecharConfirmacaoConta()">Cancelar</button>
                <button type="submit" class="mesa-action-btn warning">Solicitar conta</button>
            </div>
        </form>
    </div>
</div>

<div class="ModalNovoPedido" aria-hidden="true"></div>
<div class="CardProdutoPedido" aria-hidden="true"></div>
<div class="CarrinhoPedidoFixo" aria-hidden="true"></div>
@endsection

@section('scripts')
<script>
const filterButtons = document.querySelectorAll('[data-filter-button]');
const mesaCards = document.querySelectorAll('[data-mesa-card]');
const mesaSearch = document.getElementById('garcomMesaSearch');
const confirmacao = document.getElementById('confirmacaoSolicitarConta');
const confirmacaoTexto = document.getElementById('confirmacaoContaTexto');
const confirmacaoForm = document.getElementById('confirmacaoContaForm');
let activeFilter = 'todas';

function aplicarFiltroGarcom() {
    const query = (mesaSearch?.value || '').trim().toLowerCase();
    mesaCards.forEach((card) => {
        const status = card.dataset.status;
        const preparo = card.dataset.preparo === '1';
        const pronto = card.dataset.pronto === '1';
        const number = String(card.dataset.number || '').toLowerCase();
        const matchesQuery = !query || number.includes(query);
        const matchesFilter = activeFilter === 'todas'
            || status === activeFilter
            || (activeFilter === 'preparo' && preparo)
            || (activeFilter === 'pedido_pronto' && pronto);
        card.classList.toggle('is-hidden', !(matchesQuery && matchesFilter));
    });
    filterButtons.forEach((button) => {
        button.classList.toggle('active', button.dataset.filterButton === activeFilter);
    });
}

filterButtons.forEach((button) => {
    button.addEventListener('click', () => {
        activeFilter = button.dataset.filterButton || 'todas';
        aplicarFiltroGarcom();
        document.getElementById('garcomMesas')?.scrollIntoView({behavior:'smooth', block:'start'});
    });
});

mesaSearch?.addEventListener('input', aplicarFiltroGarcom);

function abrirConfirmacaoConta(button) {
    confirmacaoForm.action = button.dataset.contaUrl;
    confirmacaoTexto.textContent = 'Deseja solicitar o fechamento da conta da Mesa ' + button.dataset.mesaNumero + '? O caixa recebera a solicitacao.';
    confirmacao.classList.add('open');
    confirmacao.setAttribute('aria-hidden', 'false');
}

function fecharConfirmacaoConta() {
    confirmacao.classList.remove('open');
    confirmacao.setAttribute('aria-hidden', 'true');
    confirmacaoForm.removeAttribute('action');
}

confirmacao?.addEventListener('click', (event) => {
    if (event.target === confirmacao) fecharConfirmacaoConta();
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') fecharConfirmacaoConta();
});

function scrollToSection(id) {
    document.getElementById(id)?.scrollIntoView({behavior:'smooth', block:'start'});
    document.querySelectorAll('.BottomNavigationGarcom button').forEach((button) => button.classList.remove('active'));
    const map = {garcomMesas: 0, garcomPedidos: 1, garcomNotificacoes: 3};
    const idx = map[id];
    if (typeof idx !== 'undefined') {
        document.querySelectorAll('.BottomNavigationGarcom button')[idx === 3 ? 2 : idx]?.classList.add('active');
    }
}

document.querySelectorAll('.js-ready-deliver').forEach((form) => {
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const button = form.querySelector('button');
        const original = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Entregando...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {'Accept': 'application/json'},
                body: new FormData(form)
            });
            if (!response.ok) throw new Error('Nao foi possivel marcar como entregue.');
            form.closest('[data-ready-order]')?.remove();
            if (typeof mostrarToast === 'function') {
                mostrarToast({
                    icone: '<i class="fa-solid fa-circle-check"></i>',
                    titulo: 'Pedido entregue',
                    msg: 'Entrega registrada com sucesso.',
                    duracao: 3500
                });
            }
        } catch (error) {
            button.disabled = false;
            button.innerHTML = original;
            if (typeof mostrarToast === 'function') {
                mostrarToast({
                    icone: '<i class="fa-solid fa-triangle-exclamation"></i>',
                    titulo: 'Erro na entrega',
                    msg: error.message,
                    duracao: 5000
                });
            }
        }
    });
});

@if(isset($cozinhaEventCursor))
let garcomSse;
let garcomCursor = {{ (int) $cozinhaEventCursor }};

function conectarEntregasGarcom() {
    if (garcomSse) garcomSse.close();
    garcomSse = new EventSource('{{ route("cozinha.stream") }}?after=' + encodeURIComponent(garcomCursor));

    garcomSse.addEventListener('cozinha_eventos', (event) => {
        const eventos = JSON.parse(event.data || '[]');
        eventos.forEach((evento) => {
            garcomCursor = Math.max(garcomCursor, Number(evento.id || 0));
            if (evento.type !== 'order_ready') return;

            const pedido = evento.payload || {};
            if (typeof mostrarToast === 'function') {
                mostrarToast({
                    icone: '<i class="fa-solid fa-bell-concierge"></i>',
                    titulo: 'Mesa ' + (pedido.mesa || '-') + ' pronta',
                    msg: 'Toque para abrir o pedido #' + (pedido.numero || pedido.id || ''),
                    duracao: 9000,
                    botoes: [{
                        label: 'Abrir',
                        primario: true,
                        acao: function() {
                            if (pedido.id) window.location.href = @json('/pedidos') + '/' + pedido.id;
                        }
                    }]
                });
            }
        });
    });

    garcomSse.onerror = () => {
        garcomSse.close();
        setTimeout(conectarEntregasGarcom, 5000);
    };
}

document.addEventListener('DOMContentLoaded', conectarEntregasGarcom);
@endif
</script>
@endsection
