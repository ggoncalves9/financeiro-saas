@extends('layouts.uno-app')

@section('title', 'Detalhes da Despesa')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Detalhes da Despesa</h1>
                <div class="btn-group">
                    <a href="{{ url('/expenses') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar
                    </a>
                    <a href="{{ url('/expenses/123/edit') }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Pagamento Fornecedor ABC</h5>
                            <span class="badge bg-success fs-6">Paga</span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Informações Gerais</h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="text-muted">Categoria:</td>
                                            <td>
                                                <span class="badge bg-info">Material de Escritório</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Valor:</td>
                                            <td class="fw-bold text-primary fs-5">R$ 1.250,00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Vencimento:</td>
                                            <td>15/02/2024</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Data de Pagamento:</td>
                                            <td class="text-success">10/02/2024</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Fornecedor:</td>
                                            <td>Fornecedor ABC Ltda</td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6 class="text-muted">Detalhes do Pagamento</h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="text-muted">Método de Pagamento:</td>
                                            <td>
                                                <i class="fas fa-credit-card me-2"></i>Cartão de Crédito
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Conta:</td>
                                            <td>Banco do Brasil - CC 12345-6</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Recorrente:</td>
                                            <td>
                                                <span class="badge bg-warning">Sim - Mensal</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Tags:</td>
                                            <td>
                                                <span class="badge bg-secondary me-1">escritório</span>
                                                <span class="badge bg-secondary me-1">material</span>
                                                <span class="badge bg-secondary me-1">mensal</span>
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
                                        Material de escritório - compra mensal conforme aprovado na reunião de planejamento. 
                                        Inclui papel A4, canetas, grampeadores e outros itens básicos para funcionamento do escritório.
                                    </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-muted">Comprovante</h6>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-pdf text-danger fa-2x me-3"></i>
                                        <div>
                                            <a href="#" class="text-decoration-none" onclick="viewAttachment()">
                                                nota_fiscal_abc.pdf
                                            </a>
                                            <br>
                                            <small class="text-muted">Enviado em 05/02/2024 - 245 KB</small>
                                        </div>
                                        <div class="ms-auto">
                                            <button class="btn btn-sm btn-outline-primary me-2" onclick="downloadAttachment()">
                                                <i class="fas fa-download"></i> Download
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="viewAttachment()">
                                                <i class="fas fa-eye"></i> Visualizar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-success" onclick="markAsPaid()" 
                                        style="display: none;" id="payButton">
                                    <i class="fas fa-check me-2"></i>Marcar como Paga
                                </button>
                                
                                <button type="button" class="btn btn-warning" onclick="duplicateExpense()">
                                    <i class="fas fa-copy me-2"></i>Duplicar
                                </button>
                                
                                <button type="button" class="btn btn-info" onclick="generateReport()">
                                    <i class="fas fa-file-alt me-2"></i>Relatório
                                </button>
                                
                                <button type="button" class="btn btn-danger" onclick="deleteExpense()">
                                    <i class="fas fa-trash me-2"></i>Excluir
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Despesas Recorrentes -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Próximas Recorrências</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Vencimento</th>
                                            <th>Valor</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>15/03/2024</td>
                                            <td>R$ 1.250,00</td>
                                            <td><span class="badge bg-warning">Pendente</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-success" onclick="payRecurring('2024-03')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-primary" onclick="editRecurring('2024-03')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>15/04/2024</td>
                                            <td>R$ 1.250,00</td>
                                            <td><span class="badge bg-secondary">Agendada</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="editRecurring('2024-04')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>15/05/2024</td>
                                            <td>R$ 1.250,00</td>
                                            <td><span class="badge bg-secondary">Agendada</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="editRecurring('2024-05')">
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
                            <h5 class="card-title mb-0">Resumo Financeiro</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <h3 class="text-primary">R$ 1.250,00</h3>
                                    <small class="text-muted">Valor Total</small>
                                </div>
                            </div>
                            
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h6 class="text-success">R$ 1.250,00</h6>
                                        <small class="text-muted">Pago</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-warning">R$ 0,00</h6>
                                    <small class="text-muted">Pendente</small>
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
                                    <div class="timeline-marker bg-warning"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Comprovante anexado</h6>
                                        <p class="timeline-text">Nota fiscal PDF enviada</p>
                                        <small class="text-muted">05/02/2024 às 17:20</small>
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

                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Ações Rápidas</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary btn-sm" onclick="sendReminder()">
                                    <i class="fas fa-bell me-2"></i>Enviar Lembrete
                                </button>
                                
                                <button class="btn btn-outline-info btn-sm" onclick="exportData()">
                                    <i class="fas fa-download me-2"></i>Exportar Dados
                                </button>
                                
                                <button class="btn btn-outline-secondary btn-sm" onclick="addNote()">
                                    <i class="fas fa-sticky-note me-2"></i>Adicionar Nota
                                </button>
                                
                                <button class="btn btn-outline-warning btn-sm" onclick="shareExpense()">
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

<script>
// Função para marcar como paga
function markAsPaid() {
    if (confirm('Deseja marcar esta despesa como paga?')) {
        alert('Despesa marcada como paga!');
        location.reload();
    }
}

// Função para duplicar despesa
function duplicateExpense() {
    if (confirm('Deseja criar uma nova despesa baseada nesta?')) {
        window.location.href = '/expenses/create?duplicate=true&source=123';
    }
}

// Função para excluir despesa
function deleteExpense() {
    if (confirm('Tem certeza que deseja excluir esta despesa? Esta ação não pode ser desfeita.')) {
        // Implementar exclusão
        alert('Despesa excluída com sucesso!');
        window.location.href = '/expenses';
    }
}

// Função para gerar relatório
function generateReport() {
    alert('Gerando relatório da despesa...');
    // Implementar geração de relatório
}

// Função para visualizar anexo
function viewAttachment() {
    window.open('/expenses/123/attachment/view', '_blank');
}

// Função para fazer download do anexo
function downloadAttachment() {
    window.location.href = '/expenses/123/attachment/download';
}

// Função para pagar recorrência
function payRecurring(period) {
    if (confirm(`Deseja marcar a recorrência de ${period} como paga?`)) {
        alert('Recorrência marcada como paga!');
        location.reload();
    }
}

// Função para editar recorrência
function editRecurring(period) {
    window.location.href = `/expenses/recurring/${period}/edit`;
}

// Função para enviar lembrete
function sendReminder() {
    alert('Lembrete enviado por email!');
}

// Função para exportar dados
function exportData() {
    window.location.href = '/expenses/123/export';
}

// Função para adicionar nota
function addNote() {
    const note = prompt('Digite sua nota:');
    if (note) {
        alert('Nota adicionada com sucesso!');
    }
}

// Função para compartilhar despesa
function shareExpense() {
    const url = window.location.href;
    if (navigator.share) {
        navigator.share({
            title: 'Despesa - Pagamento Fornecedor ABC',
            url: url
        });
    } else {
        navigator.clipboard.writeText(url);
        alert('Link copiado para a área de transferência!');
    }
}
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

.table-borderless td {
    border: none;
    padding: 0.25rem 0.5rem;
}
</style>
@endsection
