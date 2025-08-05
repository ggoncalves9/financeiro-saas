@extends('layouts.admin')
@extends('admin.layout')
@section('content')
<div class="container">
    <h1 class="mb-4">Analytics do Sistema</h1>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Receita Total</h5>
                    <p class="card-text">R$ {{ number_format(array_sum($analytics['revenue_by_month']), 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Despesa Total</h5>
                    <p class="card-text">R$ {{ number_format(array_sum($analytics['expenses_by_month']), 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Novos Usuários</h5>
                    <p class="card-text">{{ array_sum($analytics['user_growth']) }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <canvas id="analyticsChart" height="120"></canvas>
            @if(array_sum($analytics['revenue_by_month']) == 0 && array_sum($analytics['expenses_by_month']) == 0 && array_sum($analytics['user_growth']) == 0)
                <div class="alert alert-warning mt-4">Nenhum dado disponível para exibir o gráfico.</div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const analytics = @json($analytics);
    const ctx = document.getElementById('analyticsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: analytics.months,
            datasets: [
                {
                    label: 'Receitas',
                    data: analytics.revenue_by_month,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                },
                {
                    label: 'Despesas',
                    data: analytics.expenses_by_month,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: true,
                },
                {
                    label: 'Novos Usuários',
                    data: analytics.user_growth,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Analytics Mensal' }
            }
        }
    });
</script>
@endsection
