@extends('layouts.app')

@section('title', 'Editar Conta')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Editar Conta</h1>
                <a href="{{ url('/accounts') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Dados da Conta</h5>
                        </div>
                        <div class="card-body">
                            <form id="accountForm" method="POST" action="#" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">
                                                Nome da Conta <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="name" name="name" 
                                                   value="Cartão Itaú Mastercard" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="type" class="form-label">
                                                Tipo de Conta <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="type" name="type" required onchange="toggleAccountFields()">
                                                <option value="">Selecione o tipo</option>
                                                <option value="checking">Conta Corrente</option>
                                                <option value="savings">Conta Poupança</option>
                                                <option value="credit_card" selected>Cartão de Crédito</option>
                                                <option value="investment">Conta Investimento</option>
                                                <option value="debit_card">Cartão de Débito</option>
                                                <option value="digital_wallet">Carteira Digital</option>
                                                <option value="cash">Dinheiro</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="bank" class="form-label">
                                                Banco/Instituição <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="bank" name="bank" required>
                                                <option value="">Selecione o banco</option>
                                                <option value="banco_brasil">Banco do Brasil</option>
                                                <option value="bradesco">Bradesco</option>
                                                <option value="caixa">Caixa Econômica Federal</option>
                                                <option value="itau" selected>Itaú</option>
                                                <option value="santander">Santander</option>
                                                <option value="nubank">Nubank</option>
                                                <option value="inter">Banco Inter</option>
                                                <option value="btg">BTG Pactual</option>
                                                <option value="sicredi">Sicredi</option>
                                                <option value="other">Outro</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="currency" class="form-label">Moeda</label>
                                            <select class="form-select" id="currency" name="currency">
                                                <option value="BRL" selected>Real (BRL)</option>
                                                <option value="USD">Dólar (USD)</option>
                                                <option value="EUR">Euro (EUR)</option>
                                                <option value="GBP">Libra (GBP)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="balance" class="form-label">
                                                Saldo Atual <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" id="balance" name="balance" 
                                                       step="0.01" value="-2350.75" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select" id="status" name="status">
                                                <option value="active" selected>Ativa</option>
                                                <option value="inactive">Inativa</option>
                                                <option value="closed">Encerrada</option>
                                                <option value="blocked">Bloqueada</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="color" class="form-label">Cor de Identificação</label>
                                            <input type="color" class="form-control form-control-color" id="color" name="color" 
                                                   value="#dc3545" title="Escolha uma cor">
                                        </div>
                                    </div>
                                </div>

                                <!-- Campos específicos para Cartão de Crédito -->
                                <div id="credit_card_fields" class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title">Informações do Cartão de Crédito</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="credit_limit" class="form-label">Limite de Crédito</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">R$</span>
                                                        <input type="number" class="form-control" id="credit_limit" name="credit_limit" 
                                                               step="0.01" min="0" value="5000.00">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="available_limit" class="form-label">Limite Disponível</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">R$</span>
                                                        <input type="number" class="form-control" id="available_limit" 
                                                               name="available_limit" step="0.01" value="2649.25" readonly>
                                                    </div>
                                                    <div class="form-text">Calculado automaticamente</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="closing_day" class="form-label">Dia do Fechamento</label>
                                                    <select class="form-select" id="closing_day" name="closing_day">
                                                        <option value="">Selecione</option>
                                                        <option value="1">Dia 1</option>
                                                        <option value="5">Dia 5</option>
                                                        <option value="10" selected>Dia 10</option>
                                                        <option value="15">Dia 15</option>
                                                        <option value="20">Dia 20</option>
                                                        <option value="25">Dia 25</option>
                                                        <option value="30">Dia 30</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="due_day" class="form-label">Dia do Vencimento</label>
                                                    <select class="form-select" id="due_day" name="due_day">
                                                        <option value="">Selecione</option>
                                                        <option value="5">Dia 5</option>
                                                        <option value="10">Dia 10</option>
                                                        <option value="15">Dia 15</option>
                                                        <option value="20" selected>Dia 20</option>
                                                        <option value="25">Dia 25</option>
                                                        <option value="30">Dia 30</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="annual_fee" class="form-label">Anuidade</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">R$</span>
                                                        <input type="number" class="form-control" id="annual_fee" name="annual_fee" 
                                                               step="0.01" min="0" value="0.00">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="card_brand" class="form-label">Bandeira</label>
                                                    <select class="form-select" id="card_brand" name="card_brand">
                                                        <option value="">Selecione</option>
                                                        <option value="visa">Visa</option>
                                                        <option value="mastercard" selected>Mastercard</option>
                                                        <option value="elo">Elo</option>
                                                        <option value="american_express">American Express</option>
                                                        <option value="hipercard">Hipercard</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="last_four_digits" class="form-label">Últimos 4 Dígitos</label>
                                                    <input type="text" class="form-control" id="last_four_digits" 
                                                           name="last_four_digits" maxlength="4" value="4657" 
                                                           placeholder="1234">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campos para Conta Bancária -->
                                <div id="bank_account_fields" class="card bg-light mb-3" style="display: none;">
                                    <div class="card-body">
                                        <h6 class="card-title">Informações da Conta Bancária</h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="agency" class="form-label">Agência</label>
                                                    <input type="text" class="form-control" id="agency" name="agency" 
                                                           placeholder="1234">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="account_number" class="form-label">Número da Conta</label>
                                                    <input type="text" class="form-control" id="account_number" name="account_number" 
                                                           placeholder="12345-6">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="account_digit" class="form-label">Dígito</label>
                                                    <input type="text" class="form-control" id="account_digit" name="account_digit" 
                                                           maxlength="2" placeholder="0">
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
                                                   value="cartão, crédito, itaú, mastercard"
                                                   placeholder="Separadas por vírgula">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check mt-4">
                                                <input class="form-check-input" type="checkbox" id="is_default" name="is_default">
                                                <label class="form-check-label" for="is_default">
                                                    Conta principal
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Observações</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" 
                                              placeholder="Observações sobre a conta">Cartão de crédito principal para compras do dia a dia. Limite compartilhado com cartão adicional do cônjuge.</textarea>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Atualizar Conta
                                    </button>
                                    
                                    <button type="button" class="btn btn-success" onclick="reconcileAccount()">
                                        <i class="fas fa-check-circle me-2"></i>Conciliar
                                    </button>
                                    
                                    <button type="button" class="btn btn-info" onclick="generateStatement()">
                                        <i class="fas fa-file-alt me-2"></i>Extrato
                                    </button>
                                    
                                    <button type="button" class="btn btn-warning" onclick="blockAccount()">
                                        <i class="fas fa-lock me-2"></i>Bloquear
                                    </button>
                                    
                                    <button type="button" class="btn btn-danger" onclick="deleteAccount()">
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
                            <h5 class="card-title mb-0">Resumo da Conta</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="account-icon" style="background-color: #dc3545; width: 60px; height: 60px; border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-credit-card text-white fa-2x"></i>
                                </div>
                                <h6 class="mt-2">Cartão Itaú Mastercard</h6>
                                <span class="badge bg-success">Ativa</span>
                            </div>
                            
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h6 class="text-danger">-R$ 2.350,75</h6>
                                        <small class="text-muted">Saldo Atual</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-primary">R$ 2.649,25</h6>
                                    <small class="text-muted">Limite Disponível</small>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="small">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Limite Total:</span>
                                    <span>R$ 5.000,00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Utilização:</span>
                                    <span class="text-warning">47%</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Fechamento:</span>
                                    <span>Dia 10</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Vencimento:</span>
                                    <span>Dia 20</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Bandeira:</span>
                                    <span>Mastercard ****4657</span>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="progress mb-2">
                                <div class="progress-bar bg-warning" role="progressbar" 
                                     style="width: 47%" aria-valuenow="47" aria-valuemin="0" aria-valuemax="100">
                                    47%
                                </div>
                            </div>
                            <small class="text-muted">Utilização do limite</small>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Últimas Movimentações</h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <h6 class="mb-1">Supermercado ABC</h6>
                                        <small class="text-muted">20/01/2024</small>
                                    </div>
                                    <span class="text-danger">-R$ 156,78</span>
                                </div>
                                
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <h6 class="mb-1">Posto de Gasolina</h6>
                                        <small class="text-muted">19/01/2024</small>
                                    </div>
                                    <span class="text-danger">-R$ 95,00</span>
                                </div>
                                
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <h6 class="mb-1">Pagamento Fatura</h6>
                                        <small class="text-muted">15/01/2024</small>
                                    </div>
                                    <span class="text-success">+R$ 1.200,00</span>
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
                                <button class="btn btn-outline-primary btn-sm" onclick="addTransaction()">
                                    <i class="fas fa-plus me-2"></i>Nova Transação
                                </button>
                                
                                <button class="btn btn-outline-success btn-sm" onclick="payBill()">
                                    <i class="fas fa-credit-card me-2"></i>Pagar Fatura
                                </button>
                                
                                <button class="btn btn-outline-info btn-sm" onclick="viewStatement()">
                                    <i class="fas fa-list me-2"></i>Ver Extrato
                                </button>
                                
                                <button class="btn btn-outline-warning btn-sm" onclick="increaselimit()">
                                    <i class="fas fa-arrow-up me-2"></i>Aumentar Limite
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
// Função para alternar campos específicos do tipo de conta
function toggleAccountFields() {
    const accountType = document.getElementById('type').value;
    const creditCardFields = document.getElementById('credit_card_fields');
    const bankAccountFields = document.getElementById('bank_account_fields');
    
    // Ocultar todos os campos específicos
    creditCardFields.style.display = 'none';
    bankAccountFields.style.display = 'none';
    
    // Mostrar campos específicos baseado no tipo
    if (accountType === 'credit_card') {
        creditCardFields.style.display = 'block';
    } else if (['checking', 'savings', 'investment'].includes(accountType)) {
        bankAccountFields.style.display = 'block';
    }
}

// Calcular limite disponível automaticamente
document.getElementById('credit_limit').addEventListener('input', function() {
    const creditLimit = parseFloat(this.value) || 0;
    const currentBalance = parseFloat(document.getElementById('balance').value) || 0;
    const availableLimit = creditLimit + currentBalance; // Para cartão, saldo negativo reduz o limite
    
    document.getElementById('available_limit').value = availableLimit.toFixed(2);
});

document.getElementById('balance').addEventListener('input', function() {
    const creditLimit = parseFloat(document.getElementById('credit_limit').value) || 0;
    const currentBalance = parseFloat(this.value) || 0;
    const availableLimit = creditLimit + currentBalance;
    
    document.getElementById('available_limit').value = availableLimit.toFixed(2);
});

// Função para conciliar conta
function reconcileAccount() {
    alert('Iniciando processo de conciliação da conta...');
}

// Função para gerar extrato
function generateStatement() {
    const period = prompt('Digite o período (ex: 01/2024):');
    if (period) {
        alert(`Gerando extrato para o período ${period}...`);
    }
}

// Função para bloquear conta
function blockAccount() {
    if (confirm('Deseja bloquear esta conta? Isso impedirá novas transações.')) {
        document.getElementById('status').value = 'blocked';
        alert('Conta bloqueada com sucesso!');
    }
}

// Função para excluir conta
function deleteAccount() {
    if (confirm('Tem certeza que deseja excluir esta conta? Esta ação não pode ser desfeita.')) {
        alert('Conta excluída com sucesso!');
        window.location.href = '/accounts';
    }
}

// Função para adicionar transação
function addTransaction() {
    window.location.href = '/transactions/create?account_id=123';
}

// Função para pagar fatura
function payBill() {
    window.location.href = '/bills/create?account_id=123';
}

// Função para ver extrato
function viewStatement() {
    window.location.href = '/accounts/123/statement';
}

// Função para aumentar limite
function increaselimit() {
    const newLimit = prompt('Digite o novo limite desejado:');
    if (newLimit && !isNaN(newLimit)) {
        alert('Solicitação de aumento de limite enviada para análise!');
    }
}

// Submissão do formulário
document.getElementById('accountForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const requiredFields = ['name', 'type', 'bank', 'balance'];
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
        alert('Conta atualizada com sucesso!');
    } else {
        alert('Por favor, preencha todos os campos obrigatórios.');
    }
});

// Inicializar a página
document.addEventListener('DOMContentLoaded', function() {
    toggleAccountFields();
});
</script>
@endsection
