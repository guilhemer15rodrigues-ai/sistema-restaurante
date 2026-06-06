@extends('layouts.app')
@section('page-title', 'Pagamento')
@section('breadcrumb', 'Caixa - contas aguardando pagamento')

@section('styles')
<style>
.pay-desk {
    display: grid;
    gap: 18px;
}

.pay-ticket {
    border: 1px solid var(--border);
    border-radius: 16px;
    background: var(--card-bg);
    overflow: hidden;
}

.pay-head {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 18px;
    align-items: center;
    padding: 18px;
    border-bottom: 1px solid var(--border);
    background: linear-gradient(135deg, rgba(249,115,22,.12), rgba(250,178,105,.04));
}

.pay-title {
    color: #fff;
    font-size: 26px;
    font-weight: 900;
    line-height: 1;
}

.pay-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px 14px;
    margin-top: 8px;
    color: var(--muted);
    font-size: 12px;
}

.pay-meta strong {
    color: var(--cream);
}

.pay-total-box {
    text-align: right;
    min-width: 190px;
}

.pay-total-box span {
    display: block;
    color: var(--muted);
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: .5px;
}

.pay-total-box strong {
    color: var(--accent);
    font-family: monospace;
    font-size: 34px;
    line-height: 1.1;
}

.pay-body {
    display: grid;
    grid-template-columns: minmax(260px, 340px) minmax(0, 1fr);
    gap: 16px;
    padding: 18px;
}

.pay-summary,
.pay-panel,
.pay-finance {
    border: 1px solid var(--border);
    border-radius: 14px;
    background: var(--bg2);
    padding: 14px;
}

.pay-section-title {
    color: #fff;
    font-size: 13px;
    font-weight: 900;
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.pay-line {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    padding: 9px 0;
    border-bottom: 1px solid rgba(255,255,255,.05);
    color: var(--muted);
    font-size: 13px;
}

.pay-line:last-child {
    border-bottom: 0;
}

.pay-line strong {
    color: var(--cream);
    font-family: monospace;
}

.pay-line.final strong {
    color: var(--accent);
    font-size: 18px;
}

.pay-main {
    display: grid;
    gap: 14px;
}

.method-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 8px;
}

.method-btn {
    min-height: 48px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--bg);
    color: var(--cream);
    font-weight: 900;
    cursor: pointer;
    transition: .18s ease;
}

.method-btn:hover,
.method-btn.active {
    border-color: var(--accent);
    background: rgba(249,115,22,.14);
    transform: translateY(-1px);
}

.switch-line {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    border: 1px solid var(--border);
    border-radius: 14px;
    background: var(--bg2);
    padding: 12px 14px;
    cursor: pointer;
}

.switch-line input {
    display: none;
}

.pay-switch {
    width: 56px;
    height: 30px;
    border-radius: 999px;
    background: #374151;
    position: relative;
    flex: 0 0 auto;
    transition: .18s;
}

.pay-switch::after {
    content: "";
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #fff;
    position: absolute;
    top: 4px;
    left: 4px;
    transition: .18s;
}

.switch-line input:checked + .pay-switch {
    background: var(--accent);
}

.switch-line input:checked + .pay-switch::after {
    transform: translateX(26px);
}

.pay-flow {
    display: none;
    border: 1px solid var(--border);
    border-radius: 14px;
    background: var(--bg2);
    padding: 14px;
}

.pay-flow.active {
    display: block;
}

.pix-grid {
    display: grid;
    grid-template-columns: minmax(180px, 260px) minmax(0, 1fr);
    gap: 14px;
    align-items: stretch;
}

.pix-left,
.pix-right {
    border: 1px solid rgba(59,130,246,.22);
    border-radius: 12px;
    background: rgba(59,130,246,.06);
    padding: 12px;
}

.pix-qr {
    display: block;
    width: 170px;
    max-width: 100%;
    aspect-ratio: 1;
    height: auto;
    margin: 0 auto 10px;
    padding: 8px;
    border-radius: 12px;
    background: #fff;
}

.pix-code {
    max-height: 64px;
    overflow: auto;
    word-break: break-all;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.45;
}

.copy-btn,
.cash-chip {
    border: 1px solid var(--border);
    border-radius: 10px;
    background: var(--bg);
    color: var(--cream);
    min-height: 38px;
    padding: 0 12px;
    cursor: pointer;
    font-weight: 800;
}

.pix-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 10px;
    padding: 8px 10px;
    border-radius: 999px;
    background: rgba(234,179,8,.12);
    color: #fde047;
    font-size: 12px;
    font-weight: 900;
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

.installment-info,
.cash-change {
    margin-top: 10px;
    color: var(--muted);
    font-size: 13px;
}

.cash-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 10px;
}

.pay-finance {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
}

.finance-cell {
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--bg);
    padding: 10px;
}

.finance-cell span {
    display: block;
    color: var(--muted);
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
}

.finance-cell strong {
    display: block;
    color: #fff;
    margin-top: 4px;
    font-family: monospace;
    font-size: 18px;
}

.pay-actions {
    position: sticky;
    bottom: 0;
    z-index: 5;
    padding-top: 6px;
    background: linear-gradient(180deg, transparent, var(--bg) 35%);
}

.pay-submit {
    width: 100%;
    min-height: 54px;
    border: 0;
    border-radius: 14px;
    background: linear-gradient(135deg, #f97316, #fb923c);
    color: white;
    font-size: 15px;
    font-weight: 900;
    cursor: pointer;
    transition: .18s ease;
}

.pay-submit:hover {
    filter: brightness(1.05);
    transform: translateY(-1px);
}

.pay-submit.is-loading {
    opacity: .72;
    cursor: wait;
}

.empty-payment {
    grid-column: 1 / -1;
}

@media (max-width: 980px) {
    .pay-body,
    .pay-head,
    .pix-grid {
        grid-template-columns: 1fr;
    }

    .pay-total-box {
        text-align: left;
    }

    .method-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 640px) {
    .pay-ticket {
        border-radius: 12px;
    }

    .pay-head,
    .pay-body {
        padding: 14px;
    }

    .pay-total-box strong {
        font-size: 30px;
    }

    .pay-finance {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('content')
<div class="pay-desk">
    @forelse($mesas as $mesa)
        @foreach($mesa->orders as $p)
            @php
                $subtotal = (float) $p->total;
                $totalPago = (float) $p->payments()->where('status', 'confirmado')->sum('valor_final');
                $taxaAtual = (float) $p->payments()->where('status', 'confirmado')->sum('taxa');
                $saldo = max(0, $subtotal + $taxaAtual - $totalPago);
                $totalInicial = $saldo > 0 ? $saldo : $subtotal;
                $garcom = $p->user?->name ?? 'Nao informado';
                $abertura = $p->created_at?->format('H:i') ?? '-';
            @endphp

            <form method="POST"
                  action="{{ route('caixa.pagamento', $p) }}"
                  class="pagamento-form pay-ticket"
                  data-base-total="{{ number_format($subtotal, 2, '.', '') }}"
                  data-total="{{ number_format($totalInicial, 2, '.', '') }}"
                  data-existing-fee="{{ number_format($taxaAtual, 2, '.', '') }}"
                  data-taxa-aplicada="{{ $taxaAtual > 0 ? 1 : 0 }}"
                  data-pedido="{{ str_pad($p->id,4,'0',STR_PAD_LEFT) }}"
                  data-pix-payload="{{ e(\App\Support\PixPayload::make((float) $totalInicial, 'PED' . str_pad($p->id,4,'0',STR_PAD_LEFT))) }}">
                @csrf

                <input type="hidden" name="metodo" class="js-metodo-pagamento" value="pix">
                <input type="hidden" name="valor_pago" class="js-valor-pago" value="{{ number_format($totalInicial, 2, '.', '') }}">

                <div class="pay-head">
                    <div>
                        <div class="pay-title">Mesa {{ $mesa->numero }}</div>
                        <div class="pay-meta">
                            <span>Pedido <strong>#{{ str_pad($p->id,4,'0',STR_PAD_LEFT) }}</strong></span>
                            <span>Garcom <strong>{{ $garcom }}</strong></span>
                            <span>Abertura <strong>{{ $abertura }}</strong></span>
                        </div>
                    </div>
                    <div class="pay-total-box">
                        <span>Total da conta</span>
                        <strong class="js-total-final">R$ {{ number_format($totalInicial,2,',','.') }}</strong>
                    </div>
                </div>

                <div class="pay-body">
                    <aside class="pay-summary">
                        <div class="pay-section-title">Resumo da conta</div>
                        <div class="pay-line"><span>Subtotal</span><strong class="js-subtotal">R$ {{ number_format($subtotal,2,',','.') }}</strong></div>
                        <div class="pay-line"><span>Taxa de servico</span><strong class="js-service-fee">R$ {{ number_format($taxaAtual,2,',','.') }}</strong></div>
                        <div class="pay-line"><span>Desconto</span><strong>R$ 0,00</strong></div>
                        @if($totalPago > 0)
                        <div class="pay-line"><span>Pago</span><strong>R$ {{ number_format($totalPago,2,',','.') }}</strong></div>
                        @endif
                        <div class="pay-line final"><span>Total final</span><strong class="js-summary-total">R$ {{ number_format($totalInicial,2,',','.') }}</strong></div>
                    </aside>

                    <div class="pay-main">
                        <section class="pay-panel">
                            <div class="pay-section-title">Forma de pagamento</div>
                            <div class="method-grid">
                                <button type="button" class="method-btn active" data-method="pix">PIX</button>
                                <button type="button" class="method-btn" data-method="cartao_credito">Credito</button>
                                <button type="button" class="method-btn" data-method="cartao_debito">Debito</button>
                                <button type="button" class="method-btn" data-method="dinheiro">Dinheiro</button>
                                <button type="button" class="method-btn" data-method="vale">Vale</button>
                            </div>
                        </section>

                        <label class="switch-line">
                            <span>
                                <strong style="display:block;color:#fff">Adicionar 10% do Garcom</strong>
                                <small style="color:var(--muted)">Atualiza o total automaticamente</small>
                            </span>
                            <input type="checkbox" name="taxa_garcom" value="1" class="js-service-toggle" {{ $taxaAtual > 0 ? 'checked disabled' : '' }}>
                            <span class="pay-switch"></span>
                        </label>

                        <section class="pay-flow js-flow active" data-flow="pix">
                            <div class="pix-grid">
                                <div class="pix-left">
                                    <img class="pix-qr js-pix-qr" alt="QR Code PIX">
                                    <div class="pix-code js-pix-code"></div>
                                    <button type="button" class="copy-btn js-copy-pix" style="margin-top:10px;width:100%">
                                        <i class="fas fa-copy"></i> Copiar Chave
                                    </button>
                                </div>
                                <div class="pix-right">
                                    <div class="pay-section-title">PIX</div>
                                    <div class="pay-line final"><span>Total</span><strong class="js-pix-total">R$ {{ number_format($totalInicial,2,',','.') }}</strong></div>
                                    <div class="pix-status"><i class="fas fa-clock"></i> Aguardando pagamento</div>
                                </div>
                            </div>
                        </section>

                        <section class="pay-flow js-flow" data-flow="cartao_credito">
                            <div class="pay-section-title">Credito</div>
                            <label style="display:block;color:var(--muted);font-size:12px;margin-bottom:8px">Parcelamento</label>
                            <select name="parcelas" class="pay-select js-parcelas">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">{{ $i }}x</option>
                                @endfor
                            </select>
                            <div class="installment-info js-parcela-info">1x de R$ {{ number_format($totalInicial,2,',','.') }} sem juros</div>
                        </section>

                        <section class="pay-flow js-flow" data-flow="cartao_debito">
                            <div class="pay-section-title">Debito</div>
                            <div class="pay-line final"><span>Total no debito</span><strong class="js-debit-total">R$ {{ number_format($totalInicial,2,',','.') }}</strong></div>
                        </section>

                        <section class="pay-flow js-flow" data-flow="dinheiro">
                            <div class="pay-section-title">Dinheiro</div>
                            <label style="display:block;color:var(--muted);font-size:12px;margin-bottom:8px">Valor recebido</label>
                            <input type="number" min="0" step="0.01" inputmode="decimal" class="pay-input js-cash-received" value="{{ number_format($totalInicial,2,'.','') }}">
                            <div class="cash-chips">
                                <button type="button" class="cash-chip" data-add="10">+10</button>
                                <button type="button" class="cash-chip" data-add="20">+20</button>
                                <button type="button" class="cash-chip" data-add="50">+50</button>
                                <button type="button" class="cash-chip" data-exact="1">Valor Exato</button>
                            </div>
                            <div class="cash-change">Troco: <strong class="js-change">R$ 0,00</strong></div>
                        </section>

                        <section class="pay-flow js-flow" data-flow="vale">
                            <div class="pay-section-title">Vale</div>
                            <div class="pay-line final"><span>Total no vale</span><strong class="js-voucher-total">R$ {{ number_format($totalInicial,2,',','.') }}</strong></div>
                        </section>

                        <section class="pay-finance">
                            <div class="finance-cell">
                                <span>Recebido</span>
                                <strong class="js-received">R$ {{ number_format($totalInicial,2,',','.') }}</strong>
                            </div>
                            <div class="finance-cell">
                                <span>Total</span>
                                <strong class="js-finance-total">R$ {{ number_format($totalInicial,2,',','.') }}</strong>
                            </div>
                            <div class="finance-cell">
                                <span>Troco</span>
                                <strong class="js-finance-change">R$ 0,00</strong>
                            </div>
                        </section>

                        <div class="pay-actions">
                            <button type="submit" class="pay-submit">
                                <i class="fas fa-check-circle"></i> Confirmar Pagamento
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @endforeach
    @empty
        <div class="empty-state empty-payment">
            <i class="fas fa-cash-register"></i>
            <p>Nenhuma mesa aguardando pagamento</p>
        </div>
    @endforelse
</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.pagamento-form').forEach((form) => {
    const metodoInput = form.querySelector('.js-metodo-pagamento');
    const valueInput = form.querySelector('.js-valor-pago');
    const methodButtons = form.querySelectorAll('.method-btn');
    const flows = form.querySelectorAll('.js-flow');
    const serviceToggle = form.querySelector('.js-service-toggle');
    const parcelas = form.querySelector('.js-parcelas');
    const cashInput = form.querySelector('.js-cash-received');
    const pixQr = form.querySelector('.js-pix-qr');
    const pixCode = form.querySelector('.js-pix-code');
    const copyPix = form.querySelector('.js-copy-pix');
    const submit = form.querySelector('.pay-submit');
    const baseTotal = Number(form.dataset.baseTotal || 0);
    const currentTotal = Number(form.dataset.total || baseTotal);
    const existingFee = Number(form.dataset.existingFee || 0);
    const alreadyTaxed = form.dataset.taxaAplicada === '1';
    const originalPixPayload = form.dataset.pixPayload || '';

    const money = (value) => Number(value || 0).toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });

    const getTotal = () => {
        const service = serviceToggle?.checked && !alreadyTaxed ? baseTotal * 0.10 : 0;
        return Number((currentTotal + service).toFixed(2));
    };

    const setText = (selector, value) => {
        const el = form.querySelector(selector);
        if (el) el.textContent = money(value);
    };

    const updatePix = () => {
        const payload = serviceToggle?.checked && !alreadyTaxed ? '' : originalPixPayload;
        pixCode.textContent = payload || 'PIX sera confirmado pelo caixa no valor atualizado.';
        pixQr.style.display = payload ? 'block' : 'none';
        pixQr.src = payload ? `https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=${encodeURIComponent(payload)}` : '';
    };

    const updateParcelas = () => {
        const total = getTotal();
        const qtd = Number(parcelas?.value || 1);
        const info = form.querySelector('.js-parcela-info');
        if (info) info.textContent = `${qtd}x de ${money(total / qtd)} sem juros`;
    };

    const updateFinance = () => {
        const total = getTotal();
        const method = metodoInput.value;
        const received = method === 'dinheiro' ? Number(cashInput?.value || 0) : total;
        const change = method === 'dinheiro' ? Math.max(0, received - total) : 0;
        const paidValue = method === 'dinheiro' ? Math.min(received, total) : total;

        valueInput.value = paidValue.toFixed(2);
        setText('.js-total-final', total);
        setText('.js-summary-total', total);
        setText('.js-pix-total', total);
        setText('.js-debit-total', total);
        setText('.js-voucher-total', total);
        setText('.js-finance-total', total);
        setText('.js-received', received);
        setText('.js-change', change);
        setText('.js-finance-change', change);

        const service = serviceToggle?.checked && !alreadyTaxed ? baseTotal * 0.10 : 0;
        setText('.js-service-fee', existingFee + service);

        updateParcelas();
        updatePix();
    };

    const setMethod = (method) => {
        metodoInput.value = method;
        methodButtons.forEach((btn) => btn.classList.toggle('active', btn.dataset.method === method));
        flows.forEach((flow) => flow.classList.toggle('active', flow.dataset.flow === method));
        updateFinance();
    };

    methodButtons.forEach((button) => {
        button.addEventListener('click', () => setMethod(button.dataset.method));
    });

    serviceToggle?.addEventListener('change', () => {
        if (cashInput) cashInput.value = getTotal().toFixed(2);
        updateFinance();
    });

    parcelas?.addEventListener('change', updateParcelas);
    cashInput?.addEventListener('input', updateFinance);

    form.querySelectorAll('.cash-chip').forEach((button) => {
        button.addEventListener('click', () => {
            const total = getTotal();
            if (button.dataset.exact) {
                cashInput.value = total.toFixed(2);
            } else {
                cashInput.value = (Number(cashInput.value || 0) + Number(button.dataset.add || 0)).toFixed(2);
            }
            updateFinance();
        });
    });

    copyPix?.addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(pixCode.textContent || '');
            copyPix.textContent = 'Copiado';
            setTimeout(() => copyPix.innerHTML = '<i class="fas fa-copy"></i> Copiar Chave', 1500);
        } catch (error) {
            alert('Nao foi possivel copiar a chave PIX.');
        }
    });

    form.addEventListener('submit', () => {
        submit.classList.add('is-loading');
        submit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';
    });

    setMethod('pix');
});
</script>
@endsection
