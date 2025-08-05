@extends('layouts.uno-app')

@section('title', 'Relatórios')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Relatórios</h1>
            </div>

            <div class="row g-4">
                <!-- Relatório de Despesas -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-danger bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-arrow-trend-down text-danger"></i>
                                </div>
                                <h5 class="card-title mb-0">Relatório de Despesas</h5>
                            </div>
                            <p class="card-text text-muted">
                                Visualize todas as suas despesas detalhadas por período.
                            </p>
                            <a href="{{ route('expenses.index') }}" class="btn btn-danger">
                                Ver Relatório
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Relatório de Receitas -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-arrow-trend-up text-success"></i>
                                </div>
                                <h5 class="card-title mb-0">Relatório de Receitas</h5>
                            </div>
                            <p class="card-text text-muted">
                                Visualize todas as suas receitas detalhadas por período.
                            </p>
                            <a href="{{ route('revenues.index') }}" class="btn btn-success">
                                Ver Relatório
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Relatório de Previsões -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-lightbulb text-warning"></i>
                                </div>
                                <h5 class="card-title mb-0">Relatório de Previsões</h5>
                            </div>
                            <p class="card-text text-muted">
                                Veja suas previsões e metas financeiras para os próximos períodos.
                            </p>
                            <a href="{{ route('goals.index') }}" class="btn btn-warning">
                                Ver Relatório
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Demonstrativo de Resultados -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-chart-line text-primary"></i>
                                </div>
                                <h5 class="card-title mb-0">Demonstrativo de Resultados</h5>
                            </div>
                            <p class="card-text text-muted">
                                Visualize suas receitas e despesas organizadas por categoria e período.
                            </p>
                            <a href="{{ route('reports.income-statement') }}" class="btn btn-primary">
                                Ver Relatório
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Fluxo de Caixa -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-chart-area text-success"></i>
                                </div>
                                <h5 class="card-title mb-0">Fluxo de Caixa</h5>
                            </div>
                            <p class="card-text text-muted">
                                Acompanhe a movimentação financeira ao longo dos meses.
                            </p>
                            <a href="{{ route('reports.cash-flow') }}" class="btn btn-success">
                                Ver Relatório
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Análise por Categorias -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-chart-pie text-info"></i>
                                </div>
                                <h5 class="card-title mb-0">Análise por Categorias</h5>
                            </div>
                            <p class="card-text text-muted">
                                Veja quais categorias consomem mais do seu orçamento.
                            </p>
                            <a href="{{ route('reports.category-analysis') }}" class="btn btn-info">
                                Ver Relatório
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Progresso das Metas -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-bullseye text-warning"></i>
                                </div>
                                <h5 class="card-title mb-0">Progresso das Metas</h5>
                            </div>
                            <p class="card-text text-muted">
                                Acompanhe o progresso das suas metas financeiras.
                            </p>
                            <a href="{{ route('reports.goal-progress') }}" class="btn btn-warning">
                                Ver Relatório
                            </a>
                        </div>
                    </div>
                </div>

                @can('view-business-reports')
                <!-- DRE (Apenas PJ) -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-danger bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-file-invoice-dollar text-danger"></i>
                                </div>
                                <h5 class="card-title mb-0">DRE</h5>
                            </div>
                            <p class="card-text text-muted">
                                Demonstrativo do Resultado do Exercício para pessoa jurídica.
                            </p>
                            <a href="{{ route('reports.dre') }}" class="btn btn-danger">
                                Ver Relatório
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Resumo de Impostos (Apenas PJ) -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-secondary bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-receipt text-secondary"></i>
                                </div>
                                <h5 class="card-title mb-0">Resumo de Impostos</h5>
                            </div>
                            <p class="card-text text-muted">
                                Simulação de impostos sobre o faturamento da empresa.
                            </p>
                            <a href="{{ route('reports.tax-summary') }}" class="btn btn-secondary">
                                Ver Relatório
                            </a>
                        </div>
                    </div>
                </div>
                @endcan

                @can('manage-team')
                <!-- Despesas da Equipe (Apenas PJ) -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-dark bg-opacity-10 rounded p-2 me-3">
                                    <i class="fas fa-users text-dark"></i>
                                </div>
                                <h5 class="card-title mb-0">Despesas da Equipe</h5>
                            </div>
                            <p class="card-text text-muted">
                                Relatório de despesas por membro da equipe.
                            </p>
                            <a href="{{ route('reports.team-expenses') }}" class="btn btn-dark">
                                Ver Relatório
                            </a>
                        </div>
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
