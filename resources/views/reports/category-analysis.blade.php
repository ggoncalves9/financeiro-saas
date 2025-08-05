@extends('layouts.uno-app')

@section('title', 'Análise por Categoria')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-tags me-2"></i> Análise por Categoria</h5>
        </div>
        <div class="card-body">
            <p>Relatório de análise das receitas e despesas por categoria.</p>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <strong>Receitas por Categoria</strong>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueCategoryChart" height="200"></canvas>
                            @if(!empty($revenueCategories) && count($revenueCategories) > 0)
                                <ul class="list-group mt-3">
                                    @foreach($revenueCategories as $cat)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-circle me-2" style="color: {{ $cat['color'] ?? '#10b981' }}"></i>{{ $cat['name'] }}</span>
                                            <span class="badge bg-success">R$ {{ number_format($cat['total'], 2, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>Nenhuma receita encontrada no período.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-danger text-white">
                            <strong>Despesas por Categoria</strong>
                        </div>
                        <div class="card-body">
                            <canvas id="expenseCategoryChart" height="200"></canvas>
                            @if(!empty($expenseCategories) && count($expenseCategories) > 0)
                                <ul class="list-group mt-3">
                                    @foreach($expenseCategories as $cat)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-circle me-2" style="color: {{ $cat['color'] ?? '#ef4444' }}"></i>{{ $cat['name'] }}</span>
                                            <span class="badge bg-danger">R$ {{ number_format($cat['total'], 2, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>Nenhuma despesa encontrada no período.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de receitas por categoria
    var revenueData = {
        labels: @json(collect($revenueCategories ?? [])->pluck('name')),
        datasets: [{
            data: @json(collect($revenueCategories ?? [])->pluck('total')),
            backgroundColor: @json(collect($revenueCategories ?? [])->pluck('color')),
        }]
    };
    var ctxRevenue = document.getElementById('revenueCategoryChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'pie',
        data: revenueData,
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'bottom' }
            }
        }
    });

    // Gráfico de despesas por categoria
    var expenseData = {
        labels: @json(collect($expenseCategories ?? [])->pluck('name')),
        datasets: [{
            data: @json(collect($expenseCategories ?? [])->pluck('total')),
            backgroundColor: @json(collect($expenseCategories ?? [])->pluck('color')),
        }]
    };
    var ctxExpense = document.getElementById('expenseCategoryChart').getContext('2d');
    new Chart(ctxExpense, {
        type: 'pie',
        data: expenseData,
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'bottom' }
            }
        }
    });
});
</script>
@endpush
