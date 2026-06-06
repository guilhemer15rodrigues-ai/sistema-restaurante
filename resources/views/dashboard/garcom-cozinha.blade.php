@extends('layouts.app')
@section('page-title', 'Cozinha - Garcom')
@section('breadcrumb', 'Pedidos prontos para entrega')

@section('styles')
<style>
.ready-delivery-hub {
    border-color: rgba(34,197,94,.28) !important;
}

.ready-hub-button {
    width: 100%;
    min-height: 68px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    border: 1px solid rgba(34,197,94,.25);
    border-radius: 14px;
    background: rgba(34,197,94,.08);
    color: var(--cream);
    padding: 14px;
    text-align: left;
}

.ready-hub-button strong {
    display: block;
    color: #fff;
    font-size: 17px;
}

.ready-hub-button span {
    color: var(--muted);
    font-size: 13px;
}

.ready-delivery-list {
    display: grid;
    gap: 10px;
    margin-top: 12px;
}

.ready-delivery-card {
    border: 1px solid var(--border);
    border-left: 4px solid var(--wait-color);
    border-radius: 12px;
    background: var(--bg2);
    padding: 13px;
}

.ready-delivery-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 8px;
}

.ready-delivery-top strong {
    color: #fff;
    font-size: 18px;
}

.ready-wait {
    color: var(--wait-color);
    font-size: 12px;
    font-weight: 900;
    white-space: nowrap;
}

.ready-item-line {
    color: var(--muted);
    font-size: 13px;
    line-height: 1.45;
}

.ready-deliver-form {
    margin-top: 12px;
}

.ready-deliver-button {
    width: 100%;
    min-height: 48px;
    border: 0;
    border-radius: 10px;
    background: var(--green);
    color: #fff;
    font-weight: 900;
    cursor: pointer;
}

.ready-deliver-button:disabled {
    opacity: .55;
    cursor: wait;
}
</style>
@endsection

@section('content')
@php
    $gruposEntrega = $pedidosProntosPagamento->groupBy('table_id');
@endphp

<div class="panel ready-delivery-hub" id="entregas">
    <div class="ready-hub-button">
        <div>
            <strong><i class="fa-solid fa-fire-burner"></i> Prontos para Entrega</strong>
            <span>{{ $pedidosProntosPagamento->count() }} pedido(s) aguardando entrega</span>
        </div>
        <i class="fa-solid fa-bell-concierge"></i>
    </div>

    <div class="ready-delivery-list" id="readyDeliveryList">
        @forelse($gruposEntrega as $mesaId => $pedidosMesa)
        @php
            $primeiro = $pedidosMesa->sortBy(fn($pedido) => $pedido->horario_termino_preparo ?? $pedido->updated_at)->first();
            $referencia = $primeiro?->horario_termino_preparo ?? $primeiro?->updated_at ?? now();
            $espera = max(0, (int) $referencia->diffInMinutes(now()));
            $waitColor = $espera < 2 ? '#22c55e' : ($espera <= 5 ? '#eab308' : '#ef4444');
            $todosItens = $pedidosMesa->flatMap->items;
            $mesaNumero = $primeiro?->table?->numero ?? '-';
        @endphp
        <div class="ready-delivery-card" style="--wait-color:{{ $waitColor }}" data-ready-group>
            <div class="ready-delivery-top">
                <div>
                    <strong>Mesa {{ $mesaNumero }}</strong>
                    <div class="ready-item-line">
                        {{ $pedidosMesa->pluck('id')->map(fn($id) => '#' . str_pad($id, 4, '0', STR_PAD_LEFT))->implode(' - ') }}
                    </div>
                </div>
                <span class="ready-wait">Pronto ha {{ $espera }} min</span>
            </div>

            @foreach($todosItens->take(4) as $item)
            <div class="ready-item-line">{{ $item->quantidade }}x {{ $item->menuItem->nome ?? 'Item' }}</div>
            @endforeach
            @if($todosItens->count() > 4)
            <div class="ready-item-line">+ {{ $todosItens->count() - 4 }} item(ns)</div>
            @endif

            <form method="POST"
                  action="{{ route('orders.updateStatus', $primeiro) }}"
                  class="ready-deliver-form js-ready-deliver"
                  data-order-ids="{{ $pedidosMesa->pluck('id')->implode(',') }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="entregue">
                <button type="submit" class="ready-deliver-button">
                    <i class="fa-solid fa-check"></i> {{ $pedidosMesa->count() > 1 ? 'Entregar Tudo' : 'Entregar' }}
                </button>
            </form>
        </div>
        @empty
        <div class="empty-state" style="padding:28px 12px">
            <i class="fa-solid fa-circle-check"></i>
            <p>Nenhum pedido pronto para entrega</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
async function entregarPedido(url) {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const body = new FormData();
    body.append('_method', 'PATCH');
    body.append('status', 'entregue');

    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body
    });

    if (!response.ok) throw new Error('Falha ao entregar pedido');
    return response.json();
}

document.querySelectorAll('.js-ready-deliver').forEach((form) => {
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const card = form.closest('[data-ready-group]');
        const button = form.querySelector('button');
        const urls = (form.dataset.orderIds || '')
            .split(',')
            .filter(Boolean)
            .map((id) => @json('/pedidos') + '/' + id + '/status');

        button.disabled = true;
        button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Entregando...';

        try {
            for (const url of urls) {
                await entregarPedido(url);
            }
            card?.remove();
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
            button.innerHTML = '<i class="fa-solid fa-check"></i> Entregar';
            if (typeof mostrarToast === 'function') {
                mostrarToast({
                    icone: '<i class="fa-solid fa-triangle-exclamation"></i>',
                    titulo: 'Erro na entrega',
                    msg: 'Nao foi possivel marcar como entregue.',
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
