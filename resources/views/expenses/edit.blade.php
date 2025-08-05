@extends('layouts.uno-app')

@section('title', 'Editar Despesa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Editar Despesa</h1>
                <a href="{{ url('/expenses') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Dados da Despesa</h5>
                        </div>
                        <div class="card-body">
                            <form id="expenseForm" method="POST" action="#" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">
                                                Título <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="title" name="title" 
                                                   value="Pagamento Fornecedor ABC" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category" class="form-label">
                                                Categoria <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="category" name="category" required>
                                                <option value="">Selecione uma categoria</option>
                                                <option value="office_supplies" selected>Material de Escritório</option>
                                                <option value="rent">Aluguel</option>
                                                <option value="utilities">Utilitários</option>
                                                <option value="marketing">Marketing</option>
                                                <option value="travel">Viagem</option>
                                                <option value="meals">Alimentação</option>
                                                <option value="software">Software</option>
                                                <option value="equipment">Equipamentos</option>
                                                <option value="maintenance">Manutenção</option>
                                                <option value="other">Outros</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="amount" class="form-label">
                                                Valor <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" id="amount" name="amount" 
                                                       step="0.01" min="0" value="1250.00" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="due_date" class="form-label">
                                                Data de Vencimento <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" class="form-control" id="due_date" name="due_date" 
                                                   value="2024-02-15" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select" id="status" name="status">
                                                <option value="pending">Pendente</option>
                                                <option value="paid" selected>Paga</option>
                                                <option value="overdue">Vencida</option>
                                                <option value="cancelled">Cancelada</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="supplier" class="form-label">Fornecedor</label>
                                            <input type="text" class="form-control" id="supplier" name="supplier" 
                                                   value="Fornecedor ABC Ltda" 
                                                   placeholder="Nome do fornecedor">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="payment_method" class="form-label">Método de Pagamento</label>
                                            <select class="form-select" id="payment_method" name="payment_method">
                                                <option value="">Selecione o método</option>
                                                <option value="credit_card" selected>Cartão de Crédito</option>
                                                <option value="debit_card">Cartão de Débito</option>
                                                <option value="bank_transfer">Transferência Bancária</option>
                                                <option value="pix">PIX</option>
                                                <option value="cash">Dinheiro</option>
                                                <option value="check">Cheque</option>
                                                <option value="billet">Boleto</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="account_id" class="form-label">Conta</label>
                                            <select class="form-select" id="account_id" name="account_id">
                                                <option value="">Selecione uma conta</option>
                                                <option value="1" selected>Banco do Brasil - CC 12345-6</option>
                                                <option value="2">Itaú - CC 98765-4</option>
                                                <option value="3">Nubank - Cartão</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="paid_date" class="form-label">Data de Pagamento</label>
                                            <input type="date" class="form-control" id="paid_date" name="paid_date"
                                                   value="2024-02-10">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_recurring" name="is_recurring" checked>
                                        <label class="form-check-label" for="is_recurring">
                                            Despesa recorrente
                                        </label>
                                    </div>
                                </div>

                                <div id="recurring_options" class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title">Opções de Recorrência</h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="recurring_frequency" class="form-label">Frequência</label>
                                                    <select class="form-select" id="recurring_frequency" name="recurring_frequency">
                                                        <option value="monthly" selected>Mensal</option>
                                                        <option value="weekly">Semanal</option>
                                                        <option value="quarterly">Trimestral</option>
                                                        <option value="yearly">Anual</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="recurring_until" class="form-label">Repetir até</label>
                                                    <input type="date" class="form-control" id="recurring_until" name="recurring_until"
                                                           value="2024-12-31">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="recurring_count" class="form-label">Número de parcelas</label>
                                                    <input type="number" class="form-control" id="recurring_count" name="recurring_count" 
                                                           min="1" value="12" placeholder="Ex: 12">
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
                                                   value="escritório, material, mensal"
                                                   placeholder="Separadas por vírgula">
                                            <div class="form-text">Use tags para organizar e filtrar suas despesas</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="attachment" class="form-label">Comprovante</label>
                                            <input type="file" class="form-control" id="attachment" name="attachment" 
                                                   accept=".pdf,.jpg,.jpeg,.png">
                                            <div class="form-text">
                                                Arquivo atual: <a href="#" class="text-primary">nota_fiscal_abc.pdf</a>
                                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeAttachment()">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Observações</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" 
                                              placeholder="Observações adicionais sobre a despesa">Material de escritório - compra mensal conforme aprovado na reunião de planejamento.</textarea>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Atualizar Despesa
                                    </button>
                                    
                                    <button type="button" class="btn btn-success" onclick="markAsPaid()">
                                        <i class="fas fa-check me-2"></i>Marcar como Paga
                                    </button>
                                    
                                    <button type="button" class="btn btn-warning" onclick="duplicateExpense()">
                                        <i class="fas fa-copy me-2"></i>Duplicar
                                    </button>
                                    
                                    <button type="button" class="btn btn-danger" onclick="deleteExpense()">
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
                            <h5 class="card-title mb-0">Resumo da Despesa</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h6 class="text-muted">Status</h6>
                                        <span class="badge bg-success">Paga</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-muted">Valor</h6>
                                    <h5 class="text-primary">R$ 1.250,00</h5>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="small">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Vencimento:</span>
                                    <span>15/02/2024</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Pagamento:</span>
                                    <span>10/02/2024</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Categoria:</span>
                                    <span>Material de Escritório</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Fornecedor:</span>
                                    <span>Fornecedor ABC Ltda</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Método:</span>
                                    <span>Cartão de Crédito</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Histórico de Alterações</h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Despesa paga</h6>
                                        <p class="timeline-text">Pagamento realizado via cartão de crédito</p>
                                        <small class="text-muted">10/02/2024 às 14:30</small>
                                    </div>
                                </div>
                                
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Valor atualizado</h6>
                                        <p class="timeline-text">De R$ 1.200,00 para R$ 1.250,00</p>
                                        <small class="text-muted">08/02/2024 às 09:15</small>
                                    </div>
                                </div>
                                
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Despesa criada</h6>
                                        <p class="timeline-text">Despesa cadastrada no sistema</p>
                                        <small class="text-muted">05/02/2024 às 16:45</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Controle da exibição das opções de recorrência
document.getElementById('is_recurring').addEventListener('change', function() {
    const recurringOptions = document.getElementById('recurring_options');
    if (this.checked) {
        recurringOptions.style.display = 'block';
    } else {
        recurringOptions.style.display = 'none';
    }
});

// Formatação de moeda
document.getElementById('amount').addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');
    value = (value / 100).toFixed(2);
    this.value = value;
});

// Função para marcar como paga
function markAsPaid() {
    if (confirm('Deseja marcar esta despesa como paga?')) {
        document.getElementById('status').value = 'paid';
        document.getElementById('paid_date').value = new Date().toISOString().split('T')[0];
        alert('Despesa marcada como paga!');
    }
}

// Função para duplicar despesa
function duplicateExpense() {
    if (confirm('Deseja criar uma nova despesa baseada nesta?')) {
        // Redirecionar para criar nova despesa com dados preenchidos
        window.location.href = '/expenses/create?duplicate=true&source=123';
    }
}

// Função para excluir despesa
function deleteExpense() {
    if (confirm('Tem certeza que deseja excluir esta despesa? Esta ação não pode ser desfeita.')) {
        // Submeter formulário de exclusão
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/expenses/123';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        const tokenField = document.createElement('input');
        tokenField.type = 'hidden';
        tokenField.name = '_token';
        tokenField.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.appendChild(methodField);
        form.appendChild(tokenField);
        document.body.appendChild(form);
        form.submit();
    }
}

// Função para remover anexo
function removeAttachment() {
    if (confirm('Deseja remover o comprovante atual?')) {
        // Implementar remoção do anexo
        alert('Comprovante removido!');
    }
}

// Submissão do formulário
document.getElementById('expenseForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validação básica
    const requiredFields = ['title', 'category', 'amount', 'due_date'];
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
        alert('Despesa atualizada com sucesso!');
        // Aqui faria a submissão real
        // this.submit();
    } else {
        alert('Por favor, preencha todos os campos obrigatórios.');
    }
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
