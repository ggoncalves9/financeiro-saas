@extends('layouts.uno-app')

@section('title', 'Editar Meta')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Editar Meta</h1>
                <a href="{{ url('/goals') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Dados da Meta</h5>
                        </div>
                        <div class="card-body">
                            <form id="goalForm" method="POST" action="#" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">
                                                Título da Meta <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="title" name="title" 
                                                   value="Viagem para Europa" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category" class="form-label">
                                                Categoria <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="category" name="category" required>
                                                <option value="">Selecione uma categoria</option>
                                                <option value="travel" selected>Viagem</option>
                                                <option value="emergency">Reserva de Emergência</option>
                                                <option value="education">Educação</option>
                                                <option value="health">Saúde</option>
                                                <option value="house">Casa</option>
                                                <option value="car">Veículo</option>
                                                <option value="investment">Investimento</option>
                                                <option value="retirement">Aposentadoria</option>
                                                <option value="other">Outros</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="target_amount" class="form-label">
                                                Valor da Meta <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" id="target_amount" name="target_amount" 
                                                       step="0.01" min="0" value="15000.00" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="current_amount" class="form-label">Valor Atual</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" id="current_amount" name="current_amount" 
                                                       step="0.01" min="0" value="8500.00" readonly>
                                            </div>
                                            <div class="form-text">Atualizado automaticamente pelos aportes</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="target_date" class="form-label">
                                                Data Alvo <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" class="form-control" id="target_date" name="target_date" 
                                                   value="2024-12-15" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select" id="status" name="status">
                                                <option value="active" selected>Ativa</option>
                                                <option value="paused">Pausada</option>
                                                <option value="completed">Concluída</option>
                                                <option value="cancelled">Cancelada</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="priority" class="form-label">Prioridade</label>
                                            <select class="form-select" id="priority" name="priority">
                                                <option value="low">Baixa</option>
                                                <option value="medium" selected>Média</option>
                                                <option value="high">Alta</option>
                                                <option value="urgent">Urgente</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="auto_contribution" name="auto_contribution" checked>
                                        <label class="form-check-label" for="auto_contribution">
                                            Contribuição automática
                                        </label>
                                    </div>
                                </div>

                                <div id="auto_contribution_options" class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title">Configurações de Contribuição Automática</h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="contribution_amount" class="form-label">Valor da Contribuição</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">R$</span>
                                                        <input type="number" class="form-control" id="contribution_amount" 
                                                               name="contribution_amount" step="0.01" min="0" value="500.00">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="contribution_frequency" class="form-label">Frequência</label>
                                                    <select class="form-select" id="contribution_frequency" name="contribution_frequency">
                                                        <option value="monthly" selected>Mensal</option>
                                                        <option value="weekly">Semanal</option>
                                                        <option value="biweekly">Quinzenal</option>
                                                        <option value="quarterly">Trimestral</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="contribution_day" class="form-label">Dia da Contribuição</label>
                                                    <select class="form-select" id="contribution_day" name="contribution_day">
                                                        <option value="1">Dia 1</option>
                                                        <option value="5" selected>Dia 5</option>
                                                        <option value="10">Dia 10</option>
                                                        <option value="15">Dia 15</option>
                                                        <option value="20">Dia 20</option>
                                                        <option value="25">Dia 25</option>
                                                        <option value="30">Último dia do mês</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="contribution_account" class="form-label">Conta de Origem</label>
                                                    <select class="form-select" id="contribution_account" name="contribution_account">
                                                        <option value="">Selecione uma conta</option>
                                                        <option value="1" selected>Banco do Brasil - CC 12345-6</option>
                                                        <option value="2">Itaú - CC 98765-4</option>
                                                        <option value="3">Nubank - Cartão</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="next_contribution" class="form-label">Próxima Contribuição</label>
                                                    <input type="date" class="form-control" id="next_contribution" 
                                                           name="next_contribution" value="2024-02-05">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tags" class="form-label">Tags</label>
                                            <input type="text" class="form-control" id="tags" name="tags" 
                                                   value="viagem, europa, férias, 2024"
                                                   placeholder="Separadas por vírgula">
                                            <div class="form-text">Use tags para organizar e filtrar suas metas</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Imagem Motivacional</label>
                                            <input type="file" class="form-control" id="image" name="image" 
                                                   accept=".jpg,.jpeg,.png">
                                            <div class="form-text">
                                                Imagem atual: <a href="#" class="text-primary">europa_viagem.jpg</a>
                                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeImage()">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Descrição</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" 
                                              placeholder="Descreva sua meta em detalhes">Planejamento para viagem de 15 dias pela Europa visitando Paris, Roma, Barcelona e Amsterdam. Incluindo passagens, hospedagem, alimentação e passeios.</textarea>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Atualizar Meta
                                    </button>
                                    
                                    <button type="button" class="btn btn-success" onclick="addContribution()">
                                        <i class="fas fa-plus me-2"></i>Adicionar Aporte
                                    </button>
                                    
                                    <button type="button" class="btn btn-info" onclick="calculateProjection()">
                                        <i class="fas fa-calculator me-2"></i>Projeção
                                    </button>
                                    
                                    <button type="button" class="btn btn-warning" onclick="pauseGoal()">
                                        <i class="fas fa-pause me-2"></i>Pausar
                                    </button>
                                    
                                    <button type="button" class="btn btn-danger" onclick="deleteGoal()">
                                        <i class="fas fa-trash me-2"></i>Excluir
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Progresso da Meta</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: 56.67%" aria-valuenow="56.67" aria-valuemin="0" aria-valuemax="100">
                                        56.67%
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h6 class="text-success">R$ 8.500,00</h6>
                                        <small class="text-muted">Alcançado</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-primary">R$ 6.500,00</h6>
                                    <small class="text-muted">Restante</small>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="small">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Meta:</span>
                                    <span class="fw-bold">R$ 15.000,00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Data Alvo:</span>
                                    <span>15/12/2024</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Faltam:</span>
                                    <span class="text-warning">318 dias</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Necessário/mês:</span>
                                    <span class="text-info">R$ 650,00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Histórico de Aportes</h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">+ R$ 500,00</h6>
                                        <p class="timeline-text">Aporte automático mensal</p>
                                        <small class="text-muted">05/01/2024</small>
                                    </div>
                                </div>
                                
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">+ R$ 1.000,00</h6>
                                        <p class="timeline-text">Aporte extra - 13º salário</p>
                                        <small class="text-muted">20/12/2023</small>
                                    </div>
                                </div>
                                
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">+ R$ 500,00</h6>
                                        <p class="timeline-text">Aporte automático mensal</p>
                                        <small class="text-muted">05/12/2023</small>
                                    </div>
                                </div>
                                
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Meta criada</h6>
                                        <p class="timeline-text">R$ 6.500,00 aporte inicial</p>
                                        <small class="text-muted">15/11/2023</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Calculadora Rápida</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label class="form-label">Tempo restante:</label>
                                <div class="text-warning fw-bold">10 meses e 11 dias</div>
                            </div>
                            
                            <div class="mb-2">
                                <label class="form-label">Necessário por mês:</label>
                                <div class="text-info fw-bold">R$ 650,00</div>
                            </div>
                            
                            <div class="mb-2">
                                <label class="form-label">Com aporte atual:</label>
                                <div class="text-success fw-bold">Meta será atingida!</div>
                            </div>
                            
                            <div class="mt-3">
                                <button class="btn btn-outline-primary btn-sm w-100" onclick="showDetailedCalculation()">
                                    Ver Detalhes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Controle da exibição das opções de contribuição automática
document.getElementById('auto_contribution').addEventListener('change', function() {
    const autoContributionOptions = document.getElementById('auto_contribution_options');
    if (this.checked) {
        autoContributionOptions.style.display = 'block';
    } else {
        autoContributionOptions.style.display = 'none';
    }
});

// Cálculo automático da projeção
function calculateProjection() {
    const targetAmount = parseFloat(document.getElementById('target_amount').value);
    const currentAmount = parseFloat(document.getElementById('current_amount').value);
    const contributionAmount = parseFloat(document.getElementById('contribution_amount').value);
    const targetDate = new Date(document.getElementById('target_date').value);
    const today = new Date();
    
    const monthsRemaining = Math.ceil((targetDate - today) / (1000 * 60 * 60 * 24 * 30));
    const remainingAmount = targetAmount - currentAmount;
    const projectedTotal = currentAmount + (contributionAmount * monthsRemaining);
    
    let message = `Projeção para ${targetDate.toLocaleDateString()}:\n\n`;
    message += `Valor atual: R$ ${currentAmount.toFixed(2)}\n`;
    message += `Valor alvo: R$ ${targetAmount.toFixed(2)}\n`;
    message += `Meses restantes: ${monthsRemaining}\n`;
    message += `Com aporte mensal de R$ ${contributionAmount.toFixed(2)}\n`;
    message += `Total projetado: R$ ${projectedTotal.toFixed(2)}\n\n`;
    
    if (projectedTotal >= targetAmount) {
        message += `✅ Meta será atingida!\nSobra prevista: R$ ${(projectedTotal - targetAmount).toFixed(2)}`;
    } else {
        const deficit = targetAmount - projectedTotal;
        const neededMonthly = deficit / monthsRemaining;
        message += `⚠️ Valor insuficiente!\nFalta: R$ ${deficit.toFixed(2)}\nAumente o aporte em: R$ ${neededMonthly.toFixed(2)}/mês`;
    }
    
    alert(message);
}

// Função para adicionar aporte
function addContribution() {
    const amount = prompt('Digite o valor do aporte:');
    if (amount && !isNaN(amount) && parseFloat(amount) > 0) {
        const currentAmount = parseFloat(document.getElementById('current_amount').value);
        const newAmount = currentAmount + parseFloat(amount);
        document.getElementById('current_amount').value = newAmount.toFixed(2);
        alert(`Aporte de R$ ${parseFloat(amount).toFixed(2)} adicionado com sucesso!`);
    }
}

// Função para pausar meta
function pauseGoal() {
    if (confirm('Deseja pausar esta meta? As contribuições automáticas serão interrompidas.')) {
        document.getElementById('status').value = 'paused';
        alert('Meta pausada com sucesso!');
    }
}

// Função para excluir meta
function deleteGoal() {
    if (confirm('Tem certeza que deseja excluir esta meta? Esta ação não pode ser desfeita.')) {
        alert('Meta excluída com sucesso!');
        window.location.href = '/goals';
    }
}

// Função para remover imagem
function removeImage() {
    if (confirm('Deseja remover a imagem atual?')) {
        alert('Imagem removida!');
    }
}

// Função para mostrar cálculo detalhado
function showDetailedCalculation() {
    calculateProjection();
}

// Submissão do formulário
document.getElementById('goalForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validação básica
    const requiredFields = ['title', 'category', 'target_amount', 'target_date'];
    let isValid = true;
    
    requiredFields.forEach(function(fieldName) {
        const field = document.getElementById(fieldName);
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (isValid) {
        alert('Meta atualizada com sucesso!');
        // Aqui faria a submissão real
        // this.submit();
    } else {
        alert('Por favor, preencha todos os campos obrigatórios.');
    }
});

// Formatação de moeda
document.getElementById('target_amount').addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');
    value = (value / 100).toFixed(2);
    this.value = value;
});

document.getElementById('contribution_amount').addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');
    value = (value / 100).toFixed(2);
    this.value = value;
});

// Atualização automática dos cálculos quando campos relevantes mudam
['target_amount', 'current_amount', 'contribution_amount', 'target_date'].forEach(function(fieldId) {
    document.getElementById(fieldId).addEventListener('change', function() {
        // Aqui pode implementar atualização automática dos cálculos
    });
});
</script>

<style>
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
</style>
@endsection
