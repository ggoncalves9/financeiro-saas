@extends('layouts.app')

@section('title', 'Detalhes da Conta')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Detalhes da Conta</h1>
                <div class="btn-group">
                    <a href="{{ url('/accounts') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar
                    </a>
                    <a href="{{ url('/accounts/123/edit') }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Cartão Itaú Mastercard</h5>
                            <span class="badge bg-success fs-6">Ativa</span>
                        </div>
                        <div class="card-body">
                            <!-- Card visual para cartão de crédito -->
                            <div class="row mb-4">
                                <div class="col-md-6 offset-md-3">
                                    <div class="credit-card" style="background: linear-gradient(135deg, #dc3545, #6f42c1); color: white; padding: 30px; border-radius: 15px; position: relative; box-shadow: 0 8px 25px rgba(0,0,0,0.15);">
                                        <div class="d-flex justify-content-between align-items-start mb-4">
                                            <div>
                                                <h6 class="mb-0 opacity-75">ITAÚ</h6>
                                            </div>
                                            <div>
                                                <i class="fab fa-cc-mastercard fa-2x"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <div class="h4 mb-0 letter-spacing">**** **** **** 4657</div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-8">
                                                <small class="text-uppercase opacity-75">Nome do Portador</small>
                                                <div class="fw-bold">JOÃO DA SILVA</div>
                                            </div>
                                            <div class="col-4">
                                                <small class="text-uppercase opacity-75">Válido até</small>
                                                <div class="fw-bold">12/27</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Saldos e limites -->
                            <div class="row mb-4">
                                <div class="col-md-3 text-center">
                                    <div class="card bg-danger text-white">
                                        <div class="card-body">
                                            <h4 class="mb-0">-R$ 2.350,75</h4>
                                            <small>Saldo Atual</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 text-center">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body">
                                            <h4 class="mb-0">R$ 5.000,00</h4>
                                            <small>Limite Total</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 text-center">
                                    <div class="card bg-success text-white">
                                        <div class="card-body">
                                            <h4 class="mb-0">R$ 2.649,25</h4>
                                            <small>Disponível</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 text-center">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body">
                                            <h4 class="mb-0">47%</h4>
                                            <small>Utilizado</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Informações da Conta</h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="text-muted">Tipo:</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <i class="fas fa-credit-card me-1"></i>Cartão de Crédito
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Banco:</td>
                                            <td class="fw-bold">Itaú Unibanco</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Bandeira:</td>
                                            <td>
                                                <i class="fab fa-cc-mastercard text-warning me-2"></i>
                                                Mastercard
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Final:</td>
                                            <td class="fw-bold">4657</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Moeda:</td>
                                            <td>Real Brasileiro (BRL)</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Criada em:</td>
                                            <td>15/03/2023</td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6 class="text-muted">Informações do Cartão</h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="text-muted">Fechamento:</td>
                                            <td>
                                                <span class="badge bg-primary">Todo dia 10</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Vencimento:</td>
                                            <td>
                                                <span class="badge bg-warning">Todo dia 20</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Próximo Fechamento:</td>
                                            <td class="text-info">10/02/2024</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Próximo Vencimento:</td>
                                            <td class="text-warning">20/02/2024</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Anuidade:</td>
                                            <td class="text-success">Isento</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Tags:</td>
                                            <td>
                                                <span class="badge bg-secondary me-1">cartão</span>
                                                <span class="badge bg-secondary me-1">crédito</span>
                                                <span class="badge bg-secondary me-1">itaú</span>
                                                <span class="badge bg-secondary">mastercard</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-muted">Observações</h6>
                                    <p class="text-dark">
                                        Cartão de crédito principal para compras do dia a dia. Limite compartilhado com cartão adicional do cônjuge. 
                                        Possui programa de pontos Itaú Sempre Presente. Configurado para débito automático da fatura na conta corrente.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-success" onclick="payBill()">
                                    <i class="fas fa-credit-card me-2"></i>Pagar Fatura
                                </button>
                                
                                <button type="button" class="btn btn-info" onclick="viewStatement()">
                                    <i class="fas fa-file-alt me-2"></i>Extrato
                                </button>
                                
                                <button type="button" class="btn btn-warning" onclick="requestLimitIncrease()">
                                    <i class="fas fa-arrow-up me-2"></i>Aumentar Limite
                                </button>
                                
                                <button type="button" class="btn btn-secondary" onclick="blockCard()">
                                    <i class="fas fa-lock me-2"></i>Bloquear
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Fatura Atual -->
                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Fatura Atual - Janeiro 2024</h5>
                            <div>
                                <span class="badge bg-warning">Fechamento: 10/01/2024</span>
                                <span class="badge bg-danger ms-2">Vencimento: 20/01/2024</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4 text-center">
                                    <h4 class="text-danger">R$ 2.350,75</h4>
                                    <small class="text-muted">Valor da Fatura</small>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h4 class="text-info">R$ 235,08</h4>
                                    <small class="text-muted">Pagamento Mínimo</small>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h4 class="text-warning">5 dias</h4>
                                    <small class="text-muted">Para Vencimento</small>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Descrição</th>
                                            <th>Categoria</th>
                                            <th>Valor</th>
                                            <th>Parcelas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>05/01/2024</td>
                                            <td>Supermercado ABC</td>
                                            <td><span class="badge bg-success">Alimentação</span></td>
                                            <td class="text-danger">R$ 156,78</td>
                                            <td>À vista</td>
                                        </tr>
                                        <tr>
                                            <td>08/01/2024</td>
                                            <td>Posto Shell</td>
                                            <td><span class="badge bg-warning">Combustível</span></td>
                                            <td class="text-danger">R$ 95,00</td>
                                            <td>À vista</td>
                                        </tr>
                                        <tr>
                                            <td>12/01/2024</td>
                                            <td>Netflix</td>
                                            <td><span class="badge bg-info">Entretenimento</span></td>
                                            <td class="text-danger">R$ 32,90</td>
                                            <td>À vista</td>
                                        </tr>
                                        <tr>
                                            <td>15/01/2024</td>
                                            <td>Loja de Roupas XYZ</td>
                                            <td><span class="badge bg-primary">Vestuário</span></td>
                                            <td class="text-danger">R$ 299,99</td>
                                            <td>3/12</td>
                                        </tr>
                                        <tr>
                                            <td>18/01/2024</td>
                                            <td>Farmácia Popular</td>
                                            <td><span class="badge bg-secondary">Saúde</span></td>
                                            <td class="text-danger">R$ 45,67</td>
                                            <td>À vista</td>
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
                            <h5 class="card-title mb-0">Utilização do Limite</h5>
                        </div>
                        <div class="card-body text-center">
                            <canvas id="limitChart" width="200" height="200"></canvas>
                            
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Utilizado</small>
                                        <div class="h6 text-danger">R$ 2.350,75</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Disponível</small>
                                        <div class="h6 text-success">R$ 2.649,25</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Histórico de Pagamentos</h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Fatura paga</h6>
                                        <p class="timeline-text">R$ 1.856,42 - Débito automático</p>
                                        <small class="text-muted">20/12/2023</small>
                                    </div>
                                </div>
                                
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Fatura paga</h6>
                                        <p class="timeline-text">R$ 2.234,56 - Transferência</p>
                                        <small class="text-muted">20/11/2023</small>
                                    </div>
                                </div>
                                
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-warning"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Pagamento mínimo</h6>
                                        <p class="timeline-text">R$ 198,34 - PIX</p>
                                        <small class="text-muted">20/10/2023</small>
                                    </div>
                                </div>
                                
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Fatura paga</h6>
                                        <p class="timeline-text">R$ 1.567,89 - Débito automático</p>
                                        <small class="text-muted">20/09/2023</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Ações Rápidas</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-success btn-sm" onclick="payFullBill()">
                                    <i class="fas fa-credit-card me-2"></i>Pagar Integral
                                </button>
                                
                                <button class="btn btn-warning btn-sm" onclick="payMinimum()">
                                    <i class="fas fa-hand-holding-usd me-2"></i>Pagar Mínimo
                                </button>
                                
                                <button class="btn btn-info btn-sm" onclick="schedulePayment()">
                                    <i class="fas fa-calendar me-2"></i>Agendar Pagamento
                                </button>
                                
                                <button class="btn btn-primary btn-sm" onclick="addTransaction()">
                                    <i class="fas fa-plus me-2"></i>Nova Compra
                                </button>
                                
                                <button class="btn btn-secondary btn-sm" onclick="viewPoints()">
                                    <i class="fas fa-star me-2"></i>Ver Pontos
                                </button>
                                
                                <button class="btn btn-outline-danger btn-sm" onclick="reportLoss()">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Reportar Perda
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Estatísticas do Mês</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h6 class="text-primary">23</h6>
                                    <small class="text-muted">Transações</small>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-info">R$ 102,20</h6>
                                    <small class="text-muted">Ticket Médio</small>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="small">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Alimentação:</span>
                                    <span class="text-success">R$ 486,34</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Vestuário:</span>
                                    <span class="text-primary">R$ 299,99</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Combustível:</span>
                                    <span class="text-warning">R$ 285,00</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Outros:</span>
                                    <span class="text-secondary">R$ 278,42</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de utilização do limite
const ctx = document.getElementById('limitChart').getContext('2d');
const limitChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Utilizado', 'Disponível'],
        datasets: [{
            data: [2350.75, 2649.25],
            backgroundColor: ['#dc3545', '#28a745'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            }
        },
        cutout: '60%'
    }
});

// Funções para ações rápidas
function payFullBill() {
    if (confirm('Deseja pagar o valor integral da fatura (R$ 2.350,75)?')) {
        alert('Redirecionando para pagamento da fatura integral...');
    }
}

function payMinimum() {
    if (confirm('Deseja pagar apenas o valor mínimo (R$ 235,08)?')) {
        alert('Redirecionando para pagamento mínimo...');
    }
}

function schedulePayment() {
    alert('Abrindo agendamento de pagamento...');
}

function addTransaction() {
    window.location.href = '/transactions/create?account_id=123';
}

function viewPoints() {
    alert('Você possui 15.450 pontos Itaú Sempre Presente!');
}

function reportLoss() {
    if (confirm('Deseja reportar perda ou roubo do cartão?')) {
        alert('Cartão bloqueado! Entre em contato com o banco.');
    }
}

function payBill() {
    alert('Redirecionando para pagamento de fatura...');
}

function viewStatement() {
    window.open('/accounts/123/statement', '_blank');
}

function requestLimitIncrease() {
    const newLimit = prompt('Digite o novo limite desejado:', '7500');
    if (newLimit && !isNaN(newLimit)) {
        alert('Solicitação de aumento enviada para análise!');
    }
}

function blockCard() {
    if (confirm('Deseja bloquear este cartão? Esta ação pode ser revertida.')) {
        alert('Cartão bloqueado com sucesso!');
    }
}
</script>

<style>
.letter-spacing {
    letter-spacing: 3px;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -21px;
    top: 20px;
    height: calc(100% - 10px);
    width: 2px;
    background-color: #dee2e6;
}

.timeline-marker {
    position: absolute;
    left: -26px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-size: 0.9rem;
}

.timeline-text {
    margin-bottom: 5px;
    font-size: 0.85rem;
    color: #6c757d;
}

.table-borderless td {
    border: none;
    padding: 0.25rem 0.5rem;
}

#limitChart {
    max-height: 200px;
}
</style>
@endsection
