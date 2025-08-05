@extends('layouts.app')

@section('title', 'Minha Assinatura')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Minha Assinatura</h1>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Plano Atual</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="text-primary">Plano Gratuito</h4>
                                    <p class="text-muted">Você está usando o plano gratuito com funcionalidades básicas.</p>
                                    
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i>Até 50 transações por mês</li>
                                        <li><i class="fas fa-check text-success me-2"></i>3 contas bancárias</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Relatórios básicos</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Suporte por email</li>
                                    </ul>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="bg-light p-3 rounded">
                                        <h6>Uso no mês atual:</h6>
                                        <div class="progress mb-2">
                                            <div class="progress-bar" role="progressbar" style="width: 68%" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small class="text-muted">34 de 50 transações utilizadas</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Histórico de Pagamentos</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Plano</th>
                                            <th>Valor</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                Nenhum pagamento realizado ainda
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Fazer Upgrade</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6 class="text-primary">Plano Premium</h6>
                                <h4 class="text-dark">R$ 29,90<small class="text-muted">/mês</small></h4>
                                <ul class="list-unstyled small">
                                    <li><i class="fas fa-check text-success me-1"></i>Transações ilimitadas</li>
                                    <li><i class="fas fa-check text-success me-1"></i>Contas bancárias ilimitadas</li>
                                    <li><i class="fas fa-check text-success me-1"></i>Todos os relatórios</li>
                                    <li><i class="fas fa-check text-success me-1"></i>Importação de OFX</li>
                                    <li><i class="fas fa-check text-success me-1"></i>Metas avançadas</li>
                                </ul>
                                <button class="btn btn-primary w-100" onclick="upgradeToпремium()">
                                    Assinar Premium
                                </button>
                            </div>

                            <hr>

                            <div>
                                <h6 class="text-warning">Plano Enterprise</h6>
                                <h4 class="text-dark">R$ 99,90<small class="text-muted">/mês</small></h4>
                                <ul class="list-unstyled small">
                                    <li><i class="fas fa-check text-success me-1"></i>Tudo do Premium</li>
                                    <li><i class="fas fa-check text-success me-1"></i>Gestão de equipe</li>
                                    <li><i class="fas fa-check text-success me-1"></i>Relatórios empresariais</li>
                                    <li><i class="fas fa-check text-success me-1"></i>API personalizada</li>
                                    <li><i class="fas fa-check text-success me-1"></i>Suporte prioritário</li>
                                </ul>
                                <button class="btn btn-warning w-100" onclick="upgradeToEnterprise()">
                                    Assinar Enterprise
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Métodos de Pagamento</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">
                                <i class="fas fa-credit-card me-2"></i>
                                Cartão de crédito
                            </p>
                            <p class="text-muted small">
                                <i class="fas fa-barcode me-2"></i>
                                PIX
                            </p>
                            <p class="text-muted small">
                                <i class="fas fa-university me-2"></i>
                                Boleto bancário
                            </p>
                            
                            <hr>
                            
                            <div class="text-center">
                                <img src="https://js.stripe.com/v3/fingerprinted/img/payment-methods/card_mastercard-4d8844094b4aa5e648bb.svg" alt="Mastercard" height="20" class="me-2">
                                <img src="https://js.stripe.com/v3/fingerprinted/img/payment-methods/card_visa-5d55be35e4c8c18bb3ce.svg" alt="Visa" height="20" class="me-2">
                                <img src="https://logoeps.com/wp-content/uploads/2013/02/pix-vector-logo.png" alt="PIX" height="20">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Upgrade -->
<div class="modal fade" id="upgradeModal" tabindex="-1" aria-labelledby="upgradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="upgradeModalLabel">Fazer Upgrade</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="stripe-elements">
                    <!-- Stripe Elements será inserido aqui -->
                    <div class="mb-3">
                        <label for="cardholder-name" class="form-label">Nome no cartão</label>
                        <input type="text" id="cardholder-name" class="form-control" placeholder="Nome completo">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Dados do cartão</label>
                        <div id="card-element" class="form-control" style="height: 40px; padding: 10px;">
                            <!-- Stripe Elements será inserido aqui -->
                        </div>
                        <div id="card-errors" role="alert" class="text-danger mt-2"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="submit-payment" class="btn btn-primary">
                    <span id="button-text">Confirmar Pagamento</span>
                    <div id="spinner" class="spinner-border spinner-border-sm d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
// Configuração do Stripe (chave pública)
const stripe = Stripe('pk_test_51234567890abcdef'); // Substituir pela chave real
const elements = stripe.elements();

// Estilo para o Stripe Elements
const cardStyle = {
    style: {
        base: {
            color: '#424770',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#9e2146',
            iconColor: '#9e2146'
        }
    }
};

const cardElement = elements.create('card', cardStyle);
let selectedPlan = '';

function upgradeTopreium() {
    selectedPlan = 'premium';
    showUpgradeModal();
}

function upgradeToEnterprise() {
    selectedPlan = 'enterprise';
    showUpgradeModal();
}

function showUpgradeModal() {
    const modal = new bootstrap.Modal(document.getElementById('upgradeModal'));
    modal.show();
    
    // Montar o Stripe Elements após o modal abrir
    setTimeout(() => {
        cardElement.mount('#card-element');
    }, 300);
}

// Gerenciar erros do cartão
cardElement.on('change', function(event) {
    const displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

// Submeter pagamento
document.getElementById('submit-payment').addEventListener('click', async function() {
    const cardholderName = document.getElementById('cardholder-name');
    const submitButton = document.getElementById('submit-payment');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    
    submitButton.disabled = true;
    buttonText.classList.add('d-none');
    spinner.classList.remove('d-none');
    
    try {
        const {token, error} = await stripe.createToken(cardElement, {
            name: cardholderName.value,
        });
        
        if (error) {
            document.getElementById('card-errors').textContent = error.message;
        } else {
            // Enviar token para o servidor
            await submitToServer(token, selectedPlan);
        }
    } catch (err) {
        console.error('Erro no pagamento:', err);
        alert('Erro ao processar pagamento. Tente novamente.');
    } finally {
        submitButton.disabled = false;
        buttonText.classList.remove('d-none');
        spinner.classList.add('d-none');
    }
});

async function submitToServer(token, plan) {
    try {
        const response = await fetch('{{ route("subscription.upgrade") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                token: token.id,
                plan: plan
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Upgrade realizado com sucesso!');
            location.reload();
        } else {
            alert('Erro ao processar upgrade: ' + result.message);
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao comunicar com o servidor.');
    }
}
</script>
@endsection
