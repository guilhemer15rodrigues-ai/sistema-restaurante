@extends('layouts.app')
@section('page-title', 'Checkout do Caixa')
@section('breadcrumb', 'Pagamentos de mesas fechadas')

@section('styles')
<style>
.checkout-page {
    display: grid;
    gap: 16px;
}

.checkout-top {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 16px;
    align-items: end;
    padding: 18px;
    border: 1px solid var(--border);
    border-radius: 16px;
    background: var(--card-bg);
}

.checkout-kicker {
    color: var(--accent);
    font-size: 12px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: .8px;
}

.checkout-title {
    margin-top: 5px;
    color: #fff;
    font-size: 28px;
    font-weight: 900;
    line-height: 1.1;
}

.checkout-subtitle {
    margin-top: 6px;
    color: var(--muted);
    font-size: 13px;
}

.checkout-count {
    min-width: 150px;
    text-align: right;
}

.checkout-count span {
    display: block;
    color: var(--muted);
    font-size: 11px;
    text-transform: uppercase;
    font-weight: 900;
}

.checkout-count strong {
    display: block;
    color: var(--accent);
    font-family: monospace;
    font-size: 34px;
    line-height: 1;
}

.checkout-grid {
    display: grid;
    grid-template-columns: 270px minmax(0, 1fr);
    gap: 16px;
    align-items: start;
}

.queue-panel {
    position: sticky;
    top: 82px;
    border: 1px solid var(--border);
    border-radius: 16px;
    background: var(--card-bg);
    padding: 12px;
}

.queue-title {
    color: #fff;
    font-weight: 900;
    margin-bottom: 10px;
}

.queue-list {
    display: grid;
    gap: 8px;
}

.queue-item {
    display: grid;
    grid-template-columns: 42px minmax(0, 1fr);
    gap: 10px;
    align-items: center;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--bg2);
    padding: 10px;
    color: inherit;
    text-decoration: none;
}

.queue-num {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    background: rgba(249,115,22,.14);
    color: var(--accent);
    font-weight: 900;
}

.queue-item strong {
    display: block;
    color: #fff;
    font-size: 13px;
}

.queue-item span {
    display: block;
    color: var(--muted);
    font-size: 11px;
    margin-top: 2px;
}

.checkout-stack {
    display: grid;
    gap: 16px;
}

.checkout-card {
    border: 1px solid var(--border);
    border-radius: 18px;
    background: var(--card-bg);
    overflow: hidden;
}

.ticket-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 16px;
    align-items: center;
    padding: 18px;
    background:
        linear-gradient(135deg, rgba(249,115,22,.16), transparent 58%),
        var(--bg2);
    border-bottom: 1px solid var(--border);
}

.ticket-table {
    color: #fff;
    font-size: 30px;
    font-weight: 900;
    line-height: 1;
}

.ticket-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px 12px;
    margin-top: 9px;
    color: var(--muted);
    font-size: 12px;
}

.ticket-meta strong {
    color: var(--cream);
}

.ticket-total {
    text-align: right;
}

.ticket-total span {
    display: block;
    color: var(--muted);
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
}

.ticket-total strong {
    display: block;
    color: var(--accent);
    font-family: monospace;
    font-size: 38px;
    line-height: 1.05;
}

.checkout-body {
    display: grid;
    grid-template-columns: minmax(240px, 330px) minmax(0, 1fr);
    gap: 16px;
    padding: 16px;
}

.receipt-panel,
.method-panel,
.flow-panel,
.finance-strip {
    border: 1px solid var(--border);
    border-radius: 14px;
    background: var(--bg2);
}

.receipt-panel {
    padding: 14px;
}

.block-title {
    color: #fff;
    font-size: 12px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: .6px;
    margin-bottom: 12px;
}

.receipt-line {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    padding: 9px 0;
    border-bottom: 1px solid rgba(255,255,255,.05);
    color: var(--muted);
    font-size: 13px;
}

.receipt-line:last-child {
    border-bottom: 0;
}

.receipt-line strong {
    color: var(--cream);
    font-family: monospace;
}

.receipt-line.total {
    margin-top: 6px;
    padding-top: 13px;
    border-top: 1px solid rgba(249,115,22,.28);
}

.receipt-line.total strong {
    color: var(--accent);
    font-size: 20px;
}

.checkout-main {
    display: grid;
    gap: 12px;
}

.method-panel {
    padding: 12px;
}

.method-bar {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 8px;
}

.pay-method {
    min-height: 50px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--bg);
    color: var(--cream);
    font-weight: 900;
    cursor: pointer;
    transition: .18s ease;
}

.pay-method:hover,
.pay-method.active {
    border-color: var(--accent);
    background: rgba(249,115,22,.15);
    transform: translateY(-1px);
}

.service-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    border: 1px solid var(--border);
    border-radius: 14px;
    background: var(--bg2);
    padding: 12px;
    cursor: pointer;
}

.service-row input {
    display: none;
}

.service-row strong {
    color: #fff;
}

.service-row small {
    display: block;
    margin-top: 2px;
    color: var(--muted);
}

.service-toggle {
    width: 58px;
    height: 30px;
    border-radius: 999px;
    background: #374151;
    position: relative;
    flex: 0 0 auto;
    transition: .18s ease;
}

.service-toggle::after {
    content: "";
    position: absolute;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    top: 4px;
    left: 4px;
    background: white;
    transition: .18s ease;
}

.service-row input:checked + .service-toggle {
    background: var(--accent);
}

.service-row input:checked + .service-toggle::after {
    transform: translateX(28px);
}

.flow-panel {
    display: none;
    padding: 14px;
}

.flow-panel.active {
    display: block;
}

.pix-layout {
    display: grid;
    grid-template-columns: minmax(170px, 220px) minmax(240px, 1fr);
    gap: 12px;
    align-items: stretch;
}

.pix-box,
.pix-confirm {
    min-width: 0;
    border: 1px solid rgba(59,130,246,.22);
    border-radius: 12px;
    background: rgba(59,130,246,.06);
    padding: 12px;
}

.pix-confirm .receipt-line {
    align-items: flex-start;
    flex-wrap: wrap;
}

.pix-confirm .receipt-line strong {
    overflow-wrap: anywhere;
}

.pix-qr {
    display: block;
    width: 160px;
    max-width: 100%;
    aspect-ratio: 1;
    height: auto;
    margin: 0 auto 10px;
    padding: 8px;
    border-radius: 12px;
    background: white;
}

.pix-code {
    color: var(--muted);
    font-size: 10px;
    line-height: 1.4;
    max-height: 60px;
    overflow: auto;
    word-break: break-all;
}

.copy-pix,
.money-chip {
    min-height: 38px;
    border: 1px solid var(--border);
    border-radius: 10px;
    background: var(--bg);
    color: var(--cream);
    font-weight: 800;
    cursor: pointer;
    padding: 0 12px;
}

.pay-input,
.pay-select {
    width: 100%;
    min-height: 46px;
    border: 1px solid var(--input-border);
    border-radius: 12px;
    background: var(--input-bg);
    color: var(--cream);
    padding: 0 12px;
    font-size: 15px;
}

.flow-note {
    color: var(--muted);
    font-size: 13px;
    margin-top: 10px;
    overflow-wrap: anywhere;
}

.money-grid {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 10px;
    align-items: end;
}

.money-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 10px;
}

.finance-strip {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
    padding: 10px;
}

.finance-box {
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--bg);
    padding: 10px;
}

.finance-box span {
    display: block;
    color: var(--muted);
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
}

.finance-box strong {
    display: block;
    color: #fff;
    margin-top: 4px;
    font-family: monospace;
    font-size: 18px;
}

.submit-zone {
    position: sticky;
    bottom: 0;
    z-index: 6;
    padding-top: 8px;
    background: linear-gradient(180deg, transparent, var(--bg) 38%);
}

.checkout-submit {
    width: 100%;
    min-height: 56px;
    border: 0;
    border-radius: 14px;
    background: linear-gradient(135deg, #f97316, #fb923c);
    color: white;
    font-size: 15px;
    font-weight: 900;
    cursor: pointer;
    transition: .18s ease;
}

.checkout-submit:hover {
    filter: brightness(1.05);
    transform: translateY(-1px);
}

.checkout-submit.loading {
    opacity: .7;
    cursor: wait;
}

@media (max-width: 1100px) {
    .checkout-grid,
    .checkout-body,
    .checkout-top,
    .ticket-hero,
    .pix-layout {
        grid-template-columns: 1fr;
    }

    .queue-panel {
        position: static;
    }

    .queue-list {
        grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
    }

    .ticket-total,
    .checkout-count {
        text-align: left;
    }
}

@media (max-width: 1280px) {
    .pix-layout {
        grid-template-columns: 1fr;
    }

    .pix-qr {
        width: min(180px, 100%);
    }
}

@media (max-width: 680px) {
    .checkout-top,
    .ticket-hero,
    .checkout-body {
        padding: 14px;
    }

    .method-bar,
    .finance-strip,
    .money-grid {
        grid-template-columns: 1fr;
    }

    .ticket-total strong {
        font-size: 32px;
    }
}
</style>
@endsection

@section('content')
@php
    $totalPedidosPendentes = $mesas->sum(fn($mesa) => $mesa->orders->count());
@endphp

<div class="checkout-page">
    <section class="checkout-top">
        <div>
            <div class="checkout-kicker">Terminal de pagamento</div>
            <div class="checkout-title">Checkout do caixa</div>
            <div class="checkout-subtitle">Escolha uma conta, confirme o metodo e finalize em poucos toques.</div>
        </div>
        <div class="checkout-count">
            <span>Na fila</span>
            <strong>{{ $totalPedidosPendentes }}</strong>
        </div>
    </section>

    @if($mesas->isEmpty())
        <div class="empty-state">
            <i class="fas fa-cash-register"></i>
            <p>Nenhuma mesa aguardando pagamento</p>
        </div>
    @else
    <div class="checkout-grid">
        <aside class="queue-panel">
            <div class="queue-title">Fila de contas</div>
            <div class="queue-list">
                @foreach($mesas as $mesa)
                    @foreach($mesa->orders as $p)
                        @php
                            $subtotalFila = (float) $p->total;
                            $totalPagoFila = (float) $p->payments->where('status', 'confirmado')->sum('valor_final');
                            $taxaFila = (float) $p->payments->where('status', 'confirmado')->sum('taxa');
                            $saldoFila = max(0, $subtotalFila + $taxaFila - $totalPagoFila);
                        @endphp
                        <a href="#pay-{{ $p->id }}" class="queue-item">
                            <div class="queue-num">{{ $mesa->numero }}</div>
                            <div>
                                <strong>Pedido #{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</strong>
                                <span>R$ {{ number_format($saldoFila > 0 ? $saldoFila : $subtotalFila, 2, ',', '.') }}</span>
                            </div>
                        </a>
                    @endforeach
                @endforeach
            </div>
        </aside>

        <div class="checkout-stack">
            @foreach($mesas as $mesa)
                @foreach($mesa->orders as $p)
                    @php
                        $subtotal = (float) $p->total;
                        $totalPago = (float) $p->payments->where('status', 'confirmado')->sum('valor_final');
                        $taxaAtual = (float) $p->payments->where('status', 'confirmado')->sum('taxa');
                        $saldo = max(0, $subtotal + $taxaAtual - $totalPago);
                        $totalInicial = $saldo > 0 ? $saldo : $subtotal;
                        $garcom = $p->user?->name ?? 'Nao informado';
                        $abertura = $p->created_at?->format('H:i') ?? '-';
                    @endphp

                    <form id="pay-{{ $p->id }}"
                          method="POST"
                          action="{{ route('caixa.pagamento', $p) }}"
                          class="checkout-card js-checkout-form"
                          data-base-total="{{ number_format($subtotal, 2, '.', '') }}"
                          data-total="{{ number_format($totalInicial, 2, '.', '') }}"
                          data-existing-fee="{{ number_format($taxaAtual, 2, '.', '') }}"
                          data-taxa-aplicada="{{ $taxaAtual > 0 ? 1 : 0 }}"
                          data-pix-payload="{{ e(\App\Support\PixPayload::make((float) $totalInicial, 'PED' . str_pad($p->id,4,'0',STR_PAD_LEFT))) }}">
                        @csrf
                        <input type="hidden" name="metodo" class="js-method-input" value="pix">
                        <input type="hidden" name="valor_pago" class="js-paid-input" value="{{ number_format($totalInicial, 2, '.', '') }}">

                        <div class="ticket-hero">
                            <div>
                                <div class="ticket-table">Mesa {{ $mesa->numero }}</div>
                                <div class="ticket-meta">
                                    <span>Pedido <strong>#{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</strong></span>
                                    <span>Garcom <strong>{{ $garcom }}</strong></span>
                                    <span>Abertura <strong>{{ $abertura }}</strong></span>
                                </div>
                            </div>
                            <div class="ticket-total">
                                <span>Total da conta</span>
                                <strong class="js-grand-total">R$ {{ number_format($totalInicial, 2, ',', '.') }}</strong>
                            </div>
                        </div>

                        <div class="checkout-body">
                            <aside class="receipt-panel">
                                <div class="block-title">Resumo</div>
                                <div class="receipt-line"><span>Subtotal</span><strong>R$ {{ number_format($subtotal, 2, ',', '.') }}</strong></div>
                                <div class="receipt-line"><span>Servico</span><strong class="js-fee-label">R$ {{ number_format($taxaAtual, 2, ',', '.') }}</strong></div>
                                <div class="receipt-line"><span>Desconto</span><strong>R$ 0,00</strong></div>
                                @if($totalPago > 0)
                                <div class="receipt-line"><span>Pago</span><strong style="color:#4ade80">R$ {{ number_format($totalPago, 2, ',', '.') }}</strong></div>
                                @endif
                                <div class="receipt-line total"><span>Total final</span><strong class="js-total-label">R$ {{ number_format($totalInicial, 2, ',', '.') }}</strong></div>
                            </aside>

                            <div class="checkout-main">
                                <section class="method-panel">
                                    <div class="block-title">Pagamento</div>
                                    <div class="method-bar">
                                        <button type="button" class="pay-method active" data-method="pix">PIX</button>
                                        <button type="button" class="pay-method" data-method="cartao_credito">Credito</button>
                                        <button type="button" class="pay-method" data-method="cartao_debito">Debito</button>
                                        <button type="button" class="pay-method" data-method="dinheiro">Dinheiro</button>
                                        <button type="button" class="pay-method" data-method="vale">Vale</button>
                                    </div>
                                </section>

                                <label class="service-row">
                                    <span>
                                        <strong>Adicionar 10% do garcom</strong>
                                        <small>{{ $taxaAtual > 0 ? 'Taxa ja aplicada neste pedido' : 'Opcional no fechamento do caixa' }}</small>
                                    </span>
                                    <input type="checkbox" name="taxa_garcom" value="1" class="js-fee-toggle" {{ $taxaAtual > 0 ? 'checked disabled' : '' }}>
                                    <span class="service-toggle"></span>
                                </label>

                                <section class="flow-panel active" data-flow="pix">
                                    <div class="pix-layout">
                                        <div class="pix-box">
                                            <img class="pix-qr js-pix-qr" alt="QR Code PIX">
                                            <div class="pix-code js-pix-code"></div>
                                            <button type="button" class="copy-pix js-copy-pix" style="width:100%;margin-top:10px">
                                                <i class="fas fa-copy"></i> Copiar chave
                                            </button>
                                        </div>
                                        <div class="pix-confirm">
                                            <div class="block-title">Confirmacao PIX</div>
                                            <div class="receipt-line total"><span>Total</span><strong class="js-pix-total">R$ {{ number_format($totalInicial, 2, ',', '.') }}</strong></div>
                                            <div class="flow-note"><i class="fas fa-clock"></i> Aguardando confirmacao no aplicativo do cliente.</div>
                                        </div>
                                    </div>
                                </section>

                                <section class="flow-panel" data-flow="cartao_credito">
                                    <div class="block-title">Credito</div>
                                    <select name="parcelas" class="pay-select js-installments">
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}">{{ $i }}x</option>
                                        @endfor
                                    </select>
                                    <div class="flow-note js-installment-label">1x de R$ {{ number_format($totalInicial, 2, ',', '.') }} sem juros</div>
                                </section>

                                <section class="flow-panel" data-flow="cartao_debito">
                                    <div class="block-title">Debito</div>
                                    <div class="receipt-line total"><span>Total</span><strong class="js-debit-total">R$ {{ number_format($totalInicial, 2, ',', '.') }}</strong></div>
                                    <div class="flow-note">Aproxime ou insira o cartao na maquininha.</div>
                                </section>

                                <section class="flow-panel" data-flow="dinheiro">
                                    <div class="block-title">Dinheiro</div>
                                    <div class="money-grid">
                                        <div>
                                            <label style="display:block;color:var(--muted);font-size:12px;margin-bottom:8px">Valor recebido</label>
                                            <input type="number" min="0" step="0.01" inputmode="decimal" class="pay-input js-cash-input" value="{{ number_format($totalInicial, 2, '.', '') }}">
                                        </div>
                                        <button type="button" class="money-chip js-exact-money">Valor exato</button>
                                    </div>
                                    <div class="money-chips">
                                        <button type="button" class="money-chip" data-add="10">+10</button>
                                        <button type="button" class="money-chip" data-add="20">+20</button>
                                        <button type="button" class="money-chip" data-add="50">+50</button>
                                        <button type="button" class="money-chip" data-add="100">+100</button>
                                    </div>
                                </section>

                                <section class="flow-panel" data-flow="vale">
                                    <div class="block-title">Vale</div>
                                    <div class="receipt-line total"><span>Total</span><strong class="js-voucher-total">R$ {{ number_format($totalInicial, 2, ',', '.') }}</strong></div>
                                    <div class="flow-note">Confirme o comprovante do vale antes de finalizar.</div>
                                </section>

                                <section class="finance-strip">
                                    <div class="finance-box">
                                        <span>Recebido</span>
                                        <strong class="js-received-label">R$ {{ number_format($totalInicial, 2, ',', '.') }}</strong>
                                    </div>
                                    <div class="finance-box">
                                        <span>Total</span>
                                        <strong class="js-finance-total">R$ {{ number_format($totalInicial, 2, ',', '.') }}</strong>
                                    </div>
                                    <div class="finance-box">
                                        <span>Troco</span>
                                        <strong class="js-change-label">R$ 0,00</strong>
                                    </div>
                                </section>

                                <div class="submit-zone">
                                    <button type="submit" class="checkout-submit">
                                        <i class="fas fa-check-circle"></i> Confirmar pagamento
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                @endforeach
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.js-checkout-form').forEach((form) => {
    const methodInput = form.querySelector('.js-method-input');
    const paidInput = form.querySelector('.js-paid-input');
    const methodButtons = form.querySelectorAll('.pay-method');
    const flows = form.querySelectorAll('.flow-panel');
    const feeToggle = form.querySelector('.js-fee-toggle');
    const installments = form.querySelector('.js-installments');
    const cashInput = form.querySelector('.js-cash-input');
    const exactMoney = form.querySelector('.js-exact-money');
    const pixQr = form.querySelector('.js-pix-qr');
    const pixCode = form.querySelector('.js-pix-code');
    const copyPix = form.querySelector('.js-copy-pix');
    const submit = form.querySelector('.checkout-submit');
    const baseTotal = Number(form.dataset.baseTotal || 0);
    const currentTotal = Number(form.dataset.total || baseTotal);
    const existingFee = Number(form.dataset.existingFee || 0);
    const alreadyTaxed = form.dataset.taxaAplicada === '1';
    const originalPixPayload = form.dataset.pixPayload || '';

    const money = (value) => Number(value || 0).toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });

    const total = () => {
        const extraFee = feeToggle?.checked && !alreadyTaxed ? baseTotal * 0.10 : 0;
        return Number((currentTotal + extraFee).toFixed(2));
    };

    const write = (selector, value) => {
        const el = form.querySelector(selector);
        if (el) el.textContent = money(value);
    };

    const updatePix = () => {
        const payload = feeToggle?.checked && !alreadyTaxed ? '' : originalPixPayload;
        pixCode.textContent = payload || 'PIX no valor atualizado sera confirmado pelo caixa.';
        pixQr.style.display = payload ? 'block' : 'none';
        pixQr.src = payload ? `https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=${encodeURIComponent(payload)}` : '';
    };

    const updateInstallments = () => {
        const qty = Number(installments?.value || 1);
        const label = form.querySelector('.js-installment-label');
        if (label) label.textContent = `${qty}x de ${money(total() / qty)} sem juros`;
    };

    const updateMoney = () => {
        const method = methodInput.value;
        const totalValue = total();
        const received = method === 'dinheiro' ? Number(cashInput?.value || 0) : totalValue;
        const change = method === 'dinheiro' ? Math.max(0, received - totalValue) : 0;
        const paid = method === 'dinheiro' ? Math.min(received, totalValue) : totalValue;
        const extraFee = feeToggle?.checked && !alreadyTaxed ? baseTotal * 0.10 : 0;

        paidInput.value = paid.toFixed(2);
        write('.js-grand-total', totalValue);
        write('.js-total-label', totalValue);
        write('.js-pix-total', totalValue);
        write('.js-debit-total', totalValue);
        write('.js-voucher-total', totalValue);
        write('.js-finance-total', totalValue);
        write('.js-received-label', received);
        write('.js-change-label', change);
        write('.js-fee-label', existingFee + extraFee);

        updateInstallments();
        updatePix();
    };

    const setMethod = (method) => {
        methodInput.value = method;
        methodButtons.forEach((button) => button.classList.toggle('active', button.dataset.method === method));
        flows.forEach((flow) => flow.classList.toggle('active', flow.dataset.flow === method));
        updateMoney();
    };

    methodButtons.forEach((button) => {
        button.addEventListener('click', () => setMethod(button.dataset.method));
    });

    feeToggle?.addEventListener('change', () => {
        if (cashInput) cashInput.value = total().toFixed(2);
        updateMoney();
    });

    installments?.addEventListener('change', updateInstallments);
    cashInput?.addEventListener('input', updateMoney);

    exactMoney?.addEventListener('click', () => {
        cashInput.value = total().toFixed(2);
        updateMoney();
    });

    form.querySelectorAll('.money-chip[data-add]').forEach((button) => {
        button.addEventListener('click', () => {
            cashInput.value = (Number(cashInput.value || 0) + Number(button.dataset.add || 0)).toFixed(2);
            updateMoney();
        });
    });

    copyPix?.addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(pixCode.textContent || '');
            copyPix.textContent = 'Copiado';
            setTimeout(() => copyPix.innerHTML = '<i class="fas fa-copy"></i> Copiar chave', 1400);
        } catch (error) {
            alert('Nao foi possivel copiar a chave PIX.');
        }
    });

    form.addEventListener('submit', () => {
        submit.classList.add('loading');
        submit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';
    });

    setMethod('pix');
});
</script>
@endsection
