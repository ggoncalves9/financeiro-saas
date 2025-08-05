@extends('layouts.uno-app')

@section('title', 'Detalhes da Meta')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Detalhes da Meta</h1>
                <div class="btn-group">
                    <a href="{{ url('/goals') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar
                    </a>
                    <a href="{{ url('/goals/123/edit') }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Viagem para Europa</h5>
                            <span class="badge bg-success fs-6">Ativa</span>
                        </div>
                        <div class="card-body">
                            <!-- Imagem motivacional -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="text-center">
                                        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                                             alt="Europa" class="img-fluid rounded" style="max-height: 300px; object-fit: cover; width: 100%;">
                                    </div>
                                </div>
                            </div>

                            <!-- Barra de progresso principal -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h4 class="mb-0">Progresso da Meta</h4>
                                        <h4 class="text-primary mb-0">56.67%</h4>
                                    </div>
                                    <div class="progress mb-3" style="height: 30px;">
                                        <div class="progress-bar bg-gradient progress-bar-striped progress-bar-animated" 
                                             role="progressbar" style="width: 56.67%" aria-valuenow="56.67" aria-valuemin="0" aria-valuemax="100">
                                            R$ 8.500,00 de R$ 15.000,00
                                        </div>
                                    </div>
                                    
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <h5 class="text-success">R$ 8.500,00</h5>
                                            <small class="text-muted">Valor Atual</small>
                                        </div>
                                        <div class="col-md-3">
                                            <h5 class="text-primary">R$ 15.000,00</h5>
                                            <small class="text-muted">Meta</small>
                                        </div>
                                        <div class="col-md-3">
                                            <h5 class="text-warning">R$ 6.500,00</h5>
                                            <small class="text-muted">Restante</small>
                                        </div>
                                        <div class="col-md-3">
                                            <h5 class="text-info">318 dias</h5>
                                            <small class="text-muted">Para o prazo</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Informações Gerais</h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="text-muted">Categoria:</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <i class="fas fa-plane me-1"></i>Viagem
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Data Alvo:</td>
                                            <td class="fw-bold">15/12/2024</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Prioridade:</td>
                                            <td>
                                                <span class="badge bg-warning">Média</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Criada em:</td>
                                            <td>15/11/2023</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Tags:</td>
                                            <td>
                                                <span class="badge bg-secondary me-1">viagem</span>
                                                <span class="badge bg-secondary me-1">europa</span>
                                                <span class="badge bg-secondary me-1">férias</span>
                                                <span class="badge bg-secondary me-1">2024</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6 class="text-muted">Contribuição Automática</h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="text-muted">Status:</td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Ativa
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Valor:</td>
                                            <td class="fw-bold text-success">R$ 500,00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Frequência:</td>
                                            <td>Mensal - Todo dia 5</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Conta:</td>
                                            <td>Banco do Brasil - CC 12345-6</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Próxima:</td>
                                            <td class="text-info">05/02/2024</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-muted">Descrição</h6>
                                    <p class="text-dark">
                                        Planejamento para viagem de 15 dias pela Europa visitando Paris, Roma, Barcelona e Amsterdam. 
                                        Incluindo passagens aéreas, hospedagem em hotéis 3-4 estrelas, alimentação, passeios turísticos, 
                                        compras e uma reserva para emergências. O objetivo é ter uma experiência completa e confortável 
                                        pelos principais pontos turísticos europeus.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-success" onclick="addContribution()">
                                    <i class="fas fa-plus me-2"></i>Adicionar Aporte
                                </button>
                                
                                <button type="button" class="btn btn-info" onclick="generateReport()">
                                    <i class="fas fa-chart-line me-2"></i>Relatório
                                </button>
                                
                                <button type="button" class="btn btn-warning" onclick="pauseGoal()">
                                    <i class="fas fa-pause me-2"></i>Pausar
                                </button>
                                
                                <button type="button" class="btn btn-danger" onclick="deleteGoal()">
                                    <i class="fas fa-trash me-2"></i>Excluir
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Histórico de Aportes -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Histórico de Aportes</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Valor</th>
                                            <th>Tipo</th>
                                            <th>Conta</th>
                                            <th>Observações</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>05/01/2024</td>
                                            <td class="text-success fw-bold">R$ 500,00</td>
                                            <td><span class="badge bg-primary">Automático</span></td>
                                            <td>BB - CC 12345-6</td>
                                            <td>Aporte mensal automático</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" onclick="editContribution('2024-01')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>20/12/2023</td>
                                            <td class="text-success fw-bold">R$ 1.000,00</td>
                                            <td><span class="badge bg-success">Manual</span></td>
                                            <td>BB - CC 12345-6</td>
                                            <td>Aporte extra - 13º salário</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" onclick="editContribution('2023-12-extra')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>05/12/2023</td>
                                            <td class="text-success fw-bold">R$ 500,00</td>
                                            <td><span class="badge bg-primary">Automático</span></td>
                                            <td>BB - CC 12345-6</td>
                                            <td>Aporte mensal automático</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" onclick="editContribution('2023-12')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>15/11/2023</td>
                                            <td class="text-success fw-bold">R$ 6.500,00</td>
                                            <td><span class="badge bg-info">Inicial</span></td>
                                            <td>BB - CC 12345-6</td>
                                            <td>Aporte inicial da meta</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" onclick="editContribution('2023-11-initial')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
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
                            <h5 class="card-title mb-0">Projeção da Meta</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <h6 class="text-muted">Status da Projeção</h6>
                                <div class="alert alert-success" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Meta será atingida!
                                </div>
                            </div>
                            
                            <div class="small">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Valor necessário/mês:</span>
                                    <span class="text-info fw-bold">R$ 650,00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Aporte atual/mês:</span>
                                    <span class="text-success fw-bold">R$ 500,00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Projeção final:</span>
                                    <span class="text-primary fw-bold">R$ 16.500,00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Sobra prevista:</span>
                                    <span class="text-success fw-bold">R$ 1.500,00</span>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Conclusão estimada:</span>
                                    <span class="fw-bold">01/11/2024</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Antecipação:</span>
                                    <span class="text-success fw-bold">44 dias</span>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <button class="btn btn-outline-primary btn-sm w-100" onclick="showDetailedProjection()">
                                Ver Projeção Detalhada
                            </button>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Gráfico de Evolução</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="goalChart" width="400" height="300"></canvas>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Comparação com Outras Metas</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">Reserva de Emergência</small>
                                <div>
                                    <span class="badge bg-success">85%</span>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">Viagem para Europa</small>
                                <div>
                                    <span class="badge bg-primary">57%</span>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">Carro Novo</small>
                                <div>
                                    <span class="badge bg-warning">23%</span>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="text-center">
                                <small class="text-muted">Posição no ranking:</small>
                                <div class="h5 text-primary">2º lugar</div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Ações Rápidas</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-success btn-sm" onclick="quickContribution(100)">
                                    <i class="fas fa-plus me-2"></i>+ R$ 100,00
                                </button>
                                
                                <button class="btn btn-success btn-sm" onclick="quickContribution(500)">
                                    <i class="fas fa-plus me-2"></i>+ R$ 500,00
                                </button>
                                
                                <button class="btn btn-info btn-sm" onclick="adjustTarget()">
                                    <i class="fas fa-target me-2"></i>Ajustar Meta
                                </button>
                                
                                <button class="btn btn-warning btn-sm" onclick="pauseContributions()">
                                    <i class="fas fa-pause me-2"></i>Pausar Aportes
                                </button>
                                
                                <button class="btn btn-outline-primary btn-sm" onclick="shareGoal()">
                                    <i class="fas fa-share me-2"></i>Compartilhar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para adicionar aporte -->
<div class="modal fade" id="contributionModal" tabindex="-1" aria-labelledby="contributionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contributionModalLabel">Adicionar Aporte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="contributionForm">
                    <div class="mb-3">
                        <label for="contribution_amount" class="form-label">Valor do Aporte</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" class="form-control" id="contribution_amount" step="0.01" min="0" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="contribution_date" class="form-label">Data do Aporte</label>
                        <input type="date" class="form-control" id="contribution_date" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="contribution_account" class="form-label">Conta de Origem</label>
                        <select class="form-select" id="contribution_account" required>
                            <option value="">Selecione uma conta</option>
                            <option value="1">Banco do Brasil - CC 12345-6</option>
                            <option value="2">Itaú - CC 98765-4</option>
                            <option value="3">Nubank - Cartão</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="contribution_notes" class="form-label">Observações</label>
                        <textarea class="form-control" id="contribution_notes" rows="2" placeholder="Observações sobre o aporte"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="saveContribution()">Adicionar Aporte</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de evolução da meta
const ctx = document.getElementById('goalChart').getContext('2d');
const goalChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Nov/23', 'Dez/23', 'Jan/24', 'Fev/24', 'Mar/24', 'Abr/24', 'Mai/24'],
        datasets: [{
            label: 'Valor Atual',
            data: [6500, 7000, 7500, 8500, 9000, 9500, 10000],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }, {
            label: 'Projeção',
            data: [6500, 7000, 7500, 8500, 9000, 9500, 10000],
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            borderDash: [5, 5],
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'R$ ' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'bottom'
            }
        }
    }
});

// Função para adicionar aporte
function addContribution() {
    document.getElementById('contribution_date').value = new Date().toISOString().split('T')[0];
    const modal = new bootstrap.Modal(document.getElementById('contributionModal'));
    modal.show();
}

// Função para salvar aporte
function saveContribution() {
    const amount = document.getElementById('contribution_amount').value;
    const date = document.getElementById('contribution_date').value;
    const account = document.getElementById('contribution_account').value;
    const notes = document.getElementById('contribution_notes').value;
    
    if (amount && date && account) {
        alert(`Aporte de R$ ${parseFloat(amount).toFixed(2)} adicionado com sucesso!`);
        location.reload();
    } else {
        alert('Por favor, preencha todos os campos obrigatórios.');
    }
}

// Função para aporte rápido
function quickContribution(amount) {
    if (confirm(`Deseja adicionar um aporte de R$ ${amount.toFixed(2)}?`)) {
        alert(`Aporte de R$ ${amount.toFixed(2)} adicionado com sucesso!`);
        location.reload();
    }
}

// Função para pausar meta
function pauseGoal() {
    if (confirm('Deseja pausar esta meta? As contribuições automáticas serão interrompidas.')) {
        alert('Meta pausada com sucesso!');
        location.reload();
    }
}

// Função para excluir meta
function deleteGoal() {
    if (confirm('Tem certeza que deseja excluir esta meta? Esta ação não pode ser desfeita.')) {
        alert('Meta excluída com sucesso!');
        window.location.href = '/goals';
    }
}

// Função para gerar relatório
function generateReport() {
    alert('Gerando relatório da meta...');
    window.open('/goals/123/report', '_blank');
}

// Função para editar contribuição
function editContribution(id) {
    alert(`Editando contribuição ${id}...`);
}

// Função para mostrar projeção detalhada
function showDetailedProjection() {
    alert('Exibindo projeção detalhada com cenários otimista, realista e pessimista...');
}

// Função para ajustar meta
function adjustTarget() {
    const newTarget = prompt('Digite o novo valor da meta:', '15000');
    if (newTarget && !isNaN(newTarget)) {
        alert(`Meta ajustada para R$ ${parseFloat(newTarget).toFixed(2)}!`);
        location.reload();
    }
}

// Função para pausar contribuições
function pauseContributions() {
    if (confirm('Deseja pausar as contribuições automáticas?')) {
        alert('Contribuições automáticas pausadas!');
    }
}

// Função para compartilhar meta
function shareGoal() {
    const url = window.location.href;
    if (navigator.share) {
        navigator.share({
            title: 'Meta - Viagem para Europa',
            url: url
        });
    } else {
        navigator.clipboard.writeText(url);
        alert('Link copiado para a área de transferência!');
    }
}
</script>

<style>
.table-borderless td {
    border: none;
    padding: 0.25rem 0.5rem;
}

.progress-bar {
    background: linear-gradient(45deg, #28a745, #20c997);
}

#goalChart {
    max-height: 250px;
}
</style>
@endsection
