@extends('layouts.uno-app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
<!-- Quick Actions -->
<div class="quick-actions">
    <a href="#" class="quick-action-btn success" data-bs-toggle="modal" data-bs-target="#quickRevenueModal">
        <i class="fas fa-plus-circle fa-2x"></i>
        <span>Nova Receita</span>
    </a>
    <a href="#" class="quick-action-btn danger" data-bs-toggle="modal" data-bs-target="#quickExpenseModal">
        <i class="fas fa-minus-circle fa-2x"></i>
        <span>Nova Despesa</span>
    </a>
    <a href="{{ route('goals.create') }}" class="quick-action-btn primary">
        <i class="fas fa-bullseye fa-2x"></i>
        <span>Nova Meta</span>
    </a>
    <a href="{{ route('accounts.create') }}" class="quick-action-btn info">
        <i class="fas fa-university fa-2x"></i>
        <span>Nova Conta</span>
    </a>
</div>

<!-- KPI Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="kpi-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="kpi-label">Receita Total</div>
                    <div class="kpi-value text-success">{{ $formatted_monthly_revenues ?? 'R$ 0,00' }}</div>
                </div>
                <div class="p-3 rounded-circle" style="background: rgba(16, 185, 129, 0.1);">
                    <i class="fas fa-arrow-trend-up text-success"></i>
                </div>
            </div>
            <div class="kpi-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>+12.5% vs mês anterior</span>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="kpi-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="kpi-label">Despesas Totais</div>
                    <div class="kpi-value text-danger">{{ $formatted_monthly_expenses ?? 'R$ 0,00' }}</div>
                </div>
                <div class="p-3 rounded-circle" style="background: rgba(239, 68, 68, 0.1);">
                    <i class="fas fa-arrow-trend-down text-danger"></i>
                </div>
            </div>
            <div class="kpi-change negative">
                <i class="fas fa-arrow-down"></i>
                <span>+8.2% vs mês anterior</span>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="kpi-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="kpi-label">Saldo Consolidado</div>
                    <div class="kpi-value {{ ($monthly_balance ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $formatted_monthly_balance ?? 'R$ 0,00' }}
                    </div>
                </div>
                <div class="p-3 rounded-circle" style="background: rgba(6, 182, 212, 0.1);">
                    <i class="fas fa-wallet text-info"></i>
                </div>
            </div>
            <div class="kpi-change {{ ($monthly_balance ?? 0) >= 0 ? 'positive' : 'negative' }}">
                <i class="fas fa-arrow-{{ ($monthly_balance ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                <span>{{ ($monthly_balance ?? 0) >= 0 ? 'Situação positiva' : 'Atenção necessária' }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="kpi-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="kpi-label">Metas Alcançadas</div>
                    <div class="kpi-value text-primary">{{ $goals_achieved ?? 0 }}/{{ $total_goals ?? 0 }}</div>
                </div>
                <div class="p-3 rounded-circle" style="background: rgba(99, 102, 241, 0.1);">
                    <i class="fas fa-target text-primary"></i>
                </div>
            </div>
            <div class="kpi-change positive">
                <i class="fas fa-trophy"></i>
                <span>{{ $goals_completion_rate ?? 0 }}% de conclusão</span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-xl-8 col-lg-7">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Evolução Mensal - Receitas vs Despesas</h5>
                <div class="btn-group btn-group-sm" role="group">
                    <input type="radio" class="btn-check" name="chartPeriod" id="chart6m" checked>
                    <label class="btn btn-outline-primary" for="chart6m">6M</label>
                    
                    <input type="radio" class="btn-check" name="chartPeriod" id="chart12m">
                    <label class="btn btn-outline-primary" for="chart12m">12M</label>
                </div>
            </div>
            <div style="height: 350px;">
                <canvas id="revenueExpenseChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-lg-5">
        <div class="chart-container h-100">
            <h5 class="mb-3">Despesas por Categoria</h5>
            <div style="height: 300px;">
                <canvas id="expenseCategoryChart"></canvas>
            </div>
            <h5 class="mb-3 mt-4">Receitas por Categoria</h5>
            <div style="height: 300px;">
                <canvas id="revenueCategoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Goals and Recent Transactions -->
<div class="row g-4 mb-4">
    <div class="col-xl-6 col-lg-6">
        <div class="card h-100">
            <div class="card-header border-0 bg-transparent">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Progresso das Metas</h5>
                    <a href="{{ route('goals.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
            </div>
            <div class="card-body">
                @if($goals->count() > 0)
                    @foreach($goals->take(4) as $goal)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-medium">{{ $goal->title }}</span>
                            <span class="text-primary fw-bold">{{ $goal->progress_percentage }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-gradient" role="progressbar" 
                                 style="width: {{ $goal->progress_percentage }}%; background: linear-gradient(90deg, #6366f1, #06b6d4);"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">{{ $goal->current_amount ?? 'R$ 0' }}</small>
                            <small class="text-muted">{{ $goal->target_amount ?? 'R$ 0' }}</small>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-bullseye fa-3x mb-3 opacity-50"></i>
                        <p class="mb-2">Nenhuma meta cadastrada</p>
                        <a href="{{ route('goals.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Criar primeira meta
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-xl-6 col-lg-6">
        <div class="card h-100">
            <div class="card-header border-0 bg-transparent">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Transações Recentes</h5>
                    <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
                </div>
            </div>
            <div class="card-body">
                @if($recent_expenses->count() > 0)
                    @foreach($recent_expenses->take(5) as $expense)
                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="p-2 rounded-circle me-3" style="background: rgba(239, 68, 68, 0.1);">
                                <i class="fas fa-arrow-down text-danger"></i>
                            </div>
                            <div>
                                <div class="fw-medium">{{ $expense->title }}</div>
                                <small class="text-muted">{{ $expense->date->format('d/m/Y') }} • {{ $expense->category }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="text-danger fw-bold">{{ $expense->formatted_amount }}</div>
                            @if($expense->status === 'pending')
                                <span class="badge bg-warning">Pendente</span>
                            @elseif($expense->isOverdue())
                                <span class="badge bg-danger">Vencida</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-receipt fa-3x mb-3 opacity-50"></i>
                        <p class="mb-2">Nenhuma transação recente</p>
                        <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Adicionar despesa
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Goals Comparison Chart -->
<div class="row g-4">
    <div class="col-12">
        <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Comparativo de Metas</h5>
                <button class="btn btn-outline-secondary btn-sm" onclick="toggleChart('goalsChart')">
                    <i class="fas fa-chart-bar me-1"></i>Alternar visualização
                </button>
            </div>
            <div style="height: 300px;" id="goalsChartContainer">
                <canvas id="goalsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Alerts and Notifications -->
@if(isset($upcoming_expenses) && $upcoming_expenses->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-warning border-0" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05));">
            <div class="d-flex align-items-start">
                <div class="p-2 rounded-circle me-3" style="background: rgba(245, 158, 11, 0.2);">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="text-warning mb-2">Despesas Próximas ao Vencimento</h6>
                    <div class="row">
                        @foreach($upcoming_expenses->take(3) as $expense)
                        <div class="col-md-4">
                            <div class="small">
                                <strong>{{ $expense->title }}</strong><br>
                                Vencimento: {{ $expense->due_date->format('d/m/Y') }}<br>
                                Valor: {{ $expense->formatted_amount }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($upcoming_expenses->count() > 3)
                    <a href="{{ route('expenses.index', ['status' => 'pending']) }}" class="btn btn-warning btn-sm mt-2">
                        Ver todas ({{ $upcoming_expenses->count() }})
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if(isset($overdue_expenses) && $overdue_expenses > 0)
<div class="row">
    <div class="col-12">
        <div class="alert alert-danger border-0" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));">
            <div class="d-flex align-items-start">
                <div class="p-2 rounded-circle me-3" style="background: rgba(239, 68, 68, 0.2);">
                    <i class="fas fa-exclamation-circle text-danger"></i>
                </div>
                <div>
                    <h6 class="text-danger mb-1">Atenção!</h6>
                    <p class="mb-2">Você tem {{ $overdue_expenses }} despesa(s) em atraso.</p>
                    <a href="{{ route('expenses.index', ['overdue' => 1]) }}" class="btn btn-danger btn-sm">
                        Resolver pendências
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal de Nova Receita Rápida -->
<div class="modal fade" id="quickRevenueModal" tabindex="-1" aria-labelledby="quickRevenueModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickRevenueModalLabel">
                    <i class="fas fa-plus-circle text-success me-2"></i>Nova Receita Rápida
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickRevenueForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="revenueDescription" class="form-label">Descrição *</label>
                            <input type="text" class="form-control" id="revenueDescription" name="description" required>
                        </div>
                        <div class="col-md-6">
                            <label for="revenueAmount" class="form-label">Valor *</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" id="revenueAmount" name="amount" required pattern="^\d{1,3}(\.\d{3})*(,\d{2})?$" inputmode="decimal" placeholder="0,00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="revenueAccount" class="form-label">Conta *</label>
                            <select class="form-select" id="revenueAccount" name="account_id" required>
                                <option value="">Selecione uma conta</option>
                                @foreach($accounts ?? [] as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="revenueCategory" class="form-label">Categoria *</label>
                            <select class="form-select" id="revenueCategory" name="category_id" required>
                                <option value="">Selecione uma categoria</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="revenueDueDate" class="form-label">Data de Vencimento *</label>
                            <input type="date" class="form-control" id="revenueDueDate" name="due_date" required>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="revenueStatus" name="status" value="confirmed">
                                <label class="form-check-label" for="revenueStatus">
                                    Quitar agora
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Salvar Receita
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Nova Despesa Rápida -->
<div class="modal fade" id="quickExpenseModal" tabindex="-1" aria-labelledby="quickExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickExpenseModalLabel">
                    <i class="fas fa-minus-circle text-danger me-2"></i>Nova Despesa Rápida
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickExpenseForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="expenseDescription" class="form-label">Descrição *</label>
                            <input type="text" class="form-control" id="expenseDescription" name="description" required>
                        </div>
                        <div class="col-md-6">
                            <label for="expenseAmount" class="form-label">Valor *</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" id="expenseAmount" name="amount" required pattern="^\d{1,3}(\.\d{3})*(,\d{2})?$" inputmode="decimal" placeholder="0,00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="expenseAccount" class="form-label">Conta *</label>
                            <select class="form-select" id="expenseAccount" name="account_id" required>
                                <option value="">Selecione uma conta</option>
                                @foreach($accounts ?? [] as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="expenseCategory" class="form-label">Categoria *</label>
                            <select class="form-select" id="expenseCategory" name="category_id" required>
                                <option value="">Selecione uma categoria</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="expenseDueDate" class="form-label">Data de Vencimento *</label>
                            <input type="date" class="form-control" id="expenseDueDate" name="due_date" required>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="expenseStatus" name="status" value="paid">
                                <label class="form-check-label" for="expenseStatus">
                                    Pagar agora
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save me-2"></i>Salvar Despesa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
let goalsChartInstance = null;
let goalsChartType = 'bar';
function toggleChart(chartId) {
    const ctx = document.getElementById(chartId).getContext('2d');
    if (goalsChartInstance) {
        goalsChartInstance.destroy();
    }
    goalsChartType = goalsChartType === 'bar' ? 'line' : 'bar';
    goalsChartInstance = new Chart(ctx, {
        type: goalsChartType,
        data: {
            labels: goalsData.labels,
            datasets: [
                {
                    label: 'Atual',
                    data: goalsData.current,
                    backgroundColor: '#6366f1'
                },
                {
                    label: 'Meta',
                    data: goalsData.target,
                    backgroundColor: '#06b6d4'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                title: { display: false }
            }
        }
    });
}


// Dados dos gráficos vindos do backend
var chartData = {!! json_encode($chart_data) !!};
var goalsData = {!! json_encode($goals_chart_data) !!};
var expenseCategories = {!! json_encode($expense_categories) !!};
var revenueCategories = {!! json_encode($revenue_categories) !!};

document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de pizza de despesas por categoria
    if (window.Chart && document.getElementById('expenseCategoryChart')) {
        const ctxExpenseCat = document.getElementById('expenseCategoryChart').getContext('2d');
        if (expenseCategories && expenseCategories.length > 0) {
            const validCategories = expenseCategories.filter(c => (c.name ?? c.label ?? '').trim() !== '');
            if (validCategories.length > 0) {
                new Chart(ctxExpenseCat, {
                    type: 'pie',
                    data: {
                        labels: validCategories.map(c => c.name ?? c.label),
                        datasets: [{
                            data: validCategories.map(c => c.total ?? c.value ?? 0),
                            backgroundColor: [
                                '#ef4444', '#f59e0b', '#06b6d4', '#6366f1', '#10b981', '#64748b'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true },
                            title: { display: false }
                        }
                    }
                });
            } else {
                ctxExpenseCat.font = '16px Arial';
                ctxExpenseCat.fillStyle = '#888';
                ctxExpenseCat.textAlign = 'center';
                ctxExpenseCat.fillText('Sem dados para exibir', 150, 150);
            }
        } else {
            ctxExpenseCat.font = '16px Arial';
            ctxExpenseCat.fillStyle = '#888';
            ctxExpenseCat.textAlign = 'center';
            ctxExpenseCat.fillText('Sem dados para exibir', 150, 150);
        }
    }

    // Gráfico de pizza de receitas por categoria
    if (window.Chart && document.getElementById('revenueCategoryChart')) {
        const ctxRevenueCat = document.getElementById('revenueCategoryChart').getContext('2d');
        if (revenueCategories && revenueCategories.length > 0) {
            const validCategories = revenueCategories.filter(c => (c.name ?? c.label ?? '').trim() !== '');
            if (validCategories.length > 0) {
                new Chart(ctxRevenueCat, {
                    type: 'pie',
                    data: {
                        labels: validCategories.map(c => c.name ?? c.label),
                        datasets: [{
                            data: validCategories.map(c => c.total ?? c.value ?? 0),
                            backgroundColor: [
                                '#10b981', '#06b6d4', '#6366f1', '#f59e0b', '#ef4444', '#64748b'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true },
                            title: { display: false }
                        }
                    }
                });
            } else {
                ctxRevenueCat.font = '16px Arial';
                ctxRevenueCat.fillStyle = '#888';
                ctxRevenueCat.textAlign = 'center';
                ctxRevenueCat.fillText('Sem dados para exibir', 150, 150);
            }
        } else {
            ctxRevenueCat.font = '16px Arial';
            ctxRevenueCat.fillStyle = '#888';
            ctxRevenueCat.textAlign = 'center';
            ctxRevenueCat.fillText('Sem dados para exibir', 150, 150);
        }
    }
    console.log('Dashboard carregado');
    // Verificar se as contas estão presentes nos selects
    const revenueAccountSelect = document.getElementById('revenueAccount');
    const expenseAccountSelect = document.getElementById('expenseAccount');
    console.log('Opções no select de receita:', revenueAccountSelect.options.length);
    console.log('Opções no select de despesa:', expenseAccountSelect.options.length);
    for(let i = 0; i < revenueAccountSelect.options.length; i++) {
        console.log('Receita - Opção', i, ':', revenueAccountSelect.options[i].text, revenueAccountSelect.options[i].value);
    }
    for(let i = 0; i < expenseAccountSelect.options.length; i++) {
        console.log('Despesa - Opção', i, ':', expenseAccountSelect.options[i].text, expenseAccountSelect.options[i].value);
    }

    // Inicializar gráfico de receitas vs despesas
    if (window.Chart && document.getElementById('revenueExpenseChart')) {
        const ctx = document.getElementById('revenueExpenseChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Receitas',
                        data: chartData.revenues,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,0.1)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Despesas',
                        data: chartData.expenses,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239,68,68,0.1)',
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true },
                    title: { display: false }
                }
            }
        });
    }

    // Inicializar gráfico de metas
    if (window.Chart && document.getElementById('goalsChart')) {
        const ctxGoals = document.getElementById('goalsChart').getContext('2d');
        goalsChartInstance = new Chart(ctxGoals, {
            type: goalsChartType,
            data: {
                labels: goalsData.labels,
                datasets: [
                    {
                        label: 'Atual',
                        data: goalsData.current,
                        backgroundColor: '#6366f1'
                    },
                    {
                        label: 'Meta',
                        data: goalsData.target,
                        backgroundColor: '#06b6d4'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true },
                    title: { display: false }
                }
            }
        });
    }

    // Máscara para valor monetário no campo de receita rápida
    document.getElementById('revenueAmount').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = (parseInt(value, 10) || 0).toString();
        if (value.length <= 2) {
            value = value.padStart(3, '0');
        }
        let formatted = value.replace(/(\d+)(\d{2})$/, '$1,$2');
        formatted = formatted.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        e.target.value = formatted;
    });

    // Máscara para valor monetário no campo de despesa rápida
    document.getElementById('expenseAmount').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = (parseInt(value, 10) || 0).toString();
        if (value.length <= 2) {
            value = value.padStart(3, '0');
        }
        let formatted = value.replace(/(\d+)(\d{2})$/, '$1,$2');
        formatted = formatted.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        e.target.value = formatted;
    });

    // Mostrar/ocultar campos de recorrência na receita rápida
    document.getElementById('revenueRecurring').addEventListener('change', function() {
        const recurringFields = document.getElementById('recurringFieldsRevenue');
        recurringFields.style.display = this.checked ? 'block' : 'none';
    });

    // Handle Quick Revenue Form
    document.getElementById('quickRevenueForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validar campos obrigatórios
        const description = document.getElementById('revenueDescription').value.trim();
        const amount = document.getElementById('revenueAmount').value.trim();
        const accountId = document.getElementById('revenueAccount').value;
        const categoryId = document.getElementById('revenueCategory').value;
        
        if (!description) {
            alert('Por favor, preencha a descrição.');
            return;
        }
        if (!amount) {
            alert('Por favor, preencha o valor.');
            return;
        }
        if (!accountId) {
            alert('Por favor, selecione uma conta.');
            return;
        }
        if (!categoryId) {
            alert('Por favor, selecione uma categoria.');
            return;
        }
        
        const formData = new FormData();
        formData.append('description', description);
        formData.append('account_id', accountId);
        formData.append('category_id', categoryId);
        // Data de vencimento
        const dueDate = document.getElementById('revenueDueDate').value;
        if (!dueDate) {
            alert('Por favor, selecione a data de vencimento.');
            return;
        }
        formData.append('due_date', dueDate);
        // Converter valor formatado para float
        let rawValue = amount.replace(/\./g, '').replace(/,/g, '.');
        if (isNaN(parseFloat(rawValue)) || parseFloat(rawValue) <= 0) {
            alert('Por favor, insira um valor válido.');
            return;
        }
        formData.append('amount', rawValue);
        
        // Definir status conforme checkbox
        let statusChecked = document.getElementById('revenueStatus').checked;
        formData.append('status', statusChecked ? 'confirmed' : 'pending');
        
        // Adicionar campos de recorrência se marcado
        const isRecurring = document.getElementById('revenueRecurring').checked;
        formData.append('is_recurring', isRecurring ? '1' : '0');
        if (isRecurring) {
            const frequency = document.getElementById('revenueFrequency').value;
            const repeatUntil = document.getElementById('revenueRepeatUntil').value;
            if (frequency) formData.append('frequency', frequency);
            if (repeatUntil) formData.append('repeat_until', repeatUntil);
        }
        
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Salvando...';
        submitButton.disabled = true;
        
        fetch('{{ route("revenues.quick-store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro de rede: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Receita salva com sucesso!');
                // Close modal and reset form
                const modal = bootstrap.Modal.getInstance(document.getElementById('quickRevenueModal'));
                modal.hide();
                this.reset();
                document.getElementById('recurringFieldsRevenue').style.display = 'none';
                // Reload page to update data
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                alert(data.message || 'Erro ao salvar receita!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro de conexão. Verifique sua internet e tente novamente.');
        })
        .finally(() => {
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        });
    });

    // Mostrar/ocultar campos de recorrência na despesa rápida
    document.getElementById('expenseRecurring').addEventListener('change', function() {
        const recurringFields = document.getElementById('recurringFieldsExpense');
        recurringFields.style.display = this.checked ? 'block' : 'none';
    });

    // Handle Quick Expense Form
    document.getElementById('quickExpenseForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validar campos obrigatórios
        const description = document.getElementById('expenseDescription').value.trim();
        const amount = document.getElementById('expenseAmount').value.trim();
        const accountId = document.getElementById('expenseAccount').value;
        const categoryId = document.getElementById('expenseCategory').value;
        
        if (!description) {
            alert('Por favor, preencha a descrição.');
            return;
        }
        if (!amount) {
            alert('Por favor, preencha o valor.');
            return;
        }
        if (!accountId) {
            alert('Por favor, selecione uma conta.');
            return;
        }
        if (!categoryId) {
            alert('Por favor, selecione uma categoria.');
            return;
        }
        
        const formData = new FormData();
        formData.append('description', description);
        formData.append('account_id', accountId);
        formData.append('category_id', categoryId);
        // Data de vencimento
        const dueDate = document.getElementById('expenseDueDate').value;
        if (!dueDate) {
            alert('Por favor, selecione a data de vencimento.');
            return;
        }
        formData.append('due_date', dueDate);
        // Converter valor formatado para float
        let rawValue = amount.replace(/\./g, '').replace(/,/g, '.');
        if (isNaN(parseFloat(rawValue)) || parseFloat(rawValue) <= 0) {
            alert('Por favor, insira um valor válido.');
            return;
        }
        formData.append('amount', rawValue);
        
        // Definir status conforme checkbox
        let statusChecked = document.getElementById('expenseStatus').checked;
        formData.append('status', statusChecked ? 'paid' : 'pending');
        
        // Adicionar campos de recorrência se marcado
        const isRecurring = document.getElementById('expenseRecurring').checked;
        formData.append('is_recurring', isRecurring ? '1' : '0');
        if (isRecurring) {
            const frequency = document.getElementById('expenseFrequency').value;
            const repeatUntil = document.getElementById('expenseRepeatUntil').value;
            if (frequency) formData.append('frequency', frequency);
            if (repeatUntil) formData.append('repeat_until', repeatUntil);
        }
        
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Salvando...';
        submitButton.disabled = true;
        
        fetch('{{ route("expenses.quick-store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro de rede: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Despesa salva com sucesso!');
                // Close modal and reset form
                const modal = bootstrap.Modal.getInstance(document.getElementById('quickExpenseModal'));
                modal.hide();
                this.reset();
                document.getElementById('recurringFieldsExpense').style.display = 'none';
                // Reload page to update data
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                alert(data.message || 'Erro ao salvar despesa!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro de conexão. Verifique sua internet e tente novamente.');
        })
        .finally(() => {
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        });
    });
});
</script>
</div>
@endsection
