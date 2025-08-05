@extends('layouts.uno-app')

@section('title', 'Demonstrativo de Resultados')

@section('content')
<div class="container py-4">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i> Demonstrativo de Resultados</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="kpi-card">
                        <div class="kpi-label">Receitas</div>
                        <div class="kpi-value text-money-positive">R$ {{ number_format($revenues, 2, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="kpi-card">
                        <div class="kpi-label">Despesas</div>
                        <div class="kpi-value text-money-negative">R$ {{ number_format($expenses, 2, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="kpi-card">
                        <div class="kpi-label">Resultado</div>
                        <div class="kpi-value {{ $netIncome >= 0 ? 'text-money-positive' : 'text-money-negative' }}">R$ {{ number_format($netIncome, 2, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <h6 class="mb-3">Receitas por Categoria</h6>
                    <ul class="list-group">
                        @forelse($revenuesByCategory as $cat)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $cat['label'] }}
                                <span class="badge bg-success">R$ {{ number_format($cat['total'], 2, ',', '.') }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Nenhuma receita encontrada</li>
                        @endforelse
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="mb-3">Despesas por Categoria</h6>
                    <ul class="list-group">
                        @forelse($expensesByCategory as $cat)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $cat['label'] }}
                                <span class="badge bg-danger">R$ {{ number_format($cat['total'], 2, ',', '.') }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Nenhuma despesa encontrada</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
