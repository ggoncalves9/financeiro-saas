@extends('admin.layout')

@section('title', 'Relatórios')
@section('page-title', 'Relatórios e Analytics')

@section('content')
<div class="row mb-4">
    <!-- Summary Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Receita Total</h6>
                        <h2 class="text-white">R$ {{ number_format($totalRevenue ?? 0, 2, ',', '.') }}</h2>
                        <small class="text-white-50">
                            <i class="fas fa-arrow-up me-1"></i>
                            Este mês: R$ {{ number_format($monthlyRevenue ?? 0, 2, ',', '.') }}
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">MRR (Receita Recorrente)</h6>
                        <h2 class="text-white">R$ {{ number_format($mrr ?? 0, 2, ',', '.') }}</h2>
                        <small class="text-white-50">
                            Crescimento mensal
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Taxa de Conversão</h6>
                        <h2 class="text-white">{{ number_format($conversionRate ?? 0, 1) }}%</h2>
                        <small class="text-white-50">
                            Visitantes → Clientes
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-percentage fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Churn Rate</h6>
                        <h2 class="text-white">{{ number_format($churnRate ?? 0, 1) }}%</h2>
                        <small class="text-white-50">
                            Cancelamentos mensais
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-times fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Revenue Chart -->
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0">Receita por Mês (2025)</h6>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary active" data-period="12">12 meses</button>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-period="6">6 meses</button>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-period="3">3 meses</button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Plans -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0">Planos Mais Populares</h6>
            </div>
            <div class="card-body">
                <canvas id="plansChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- User Growth -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0">Crescimento de Usuários</h6>
            </div>
            <div class="card-body">
                <canvas id="userGrowthChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Geographic Distribution -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0">Distribuição Geográfica</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Usuários</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>São Paulo</td>
                                <td>2</td>
                                <td>50%</td>
                            </tr>
                            <tr>
                                <td>Rio de Janeiro</td>
                                <td>1</td>
                                <td>25%</td>
                            </tr>
                            <tr>
                                <td>Outros</td>
                                <td>1</td>
                                <td>25%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Options -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0">Exportar Relatórios</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-outline-success w-100" onclick="exportData('excel')">
                            <i class="fas fa-file-excel me-2"></i>
                            Exportar Excel
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-outline-danger w-100" onclick="exportData('pdf')">
                            <i class="fas fa-file-pdf me-2"></i>
                            Exportar PDF
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-outline-info w-100" onclick="exportData('csv')">
                            <i class="fas fa-file-csv me-2"></i>
                            Exportar CSV
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-outline-primary w-100" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>
                            Imprimir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        datasets: [{
            label: 'Receita (R$)',
            data: [0, 0, 0, 0, 0, 0, 15000, 18000, 22000, 25000, 28000, 30000],
            borderColor: '#6c5ce7',
            backgroundColor: 'rgba(108, 92, 231, 0.1)',
            tension: 0.4,
            fill: true
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
                        return 'R$ ' + value.toLocaleString('pt-BR');
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Receita: R$ ' + context.parsed.y.toLocaleString('pt-BR');
                    }
                }
            }
        }
    }
});

// Plans Chart
const plansCtx = document.getElementById('plansChart').getContext('2d');
new Chart(plansCtx, {
    type: 'doughnut',
    data: {
        labels: ['Pro PF', 'Empresarial', 'Premium PJ', 'Free'],
        datasets: [{
            data: [35, 25, 30, 10],
            backgroundColor: ['#6c5ce7', '#74b9ff', '#00b894', '#fdcb6e'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// User Growth Chart
const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
new Chart(userGrowthCtx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul'],
        datasets: [{
            label: 'Novos Usuários',
            data: [0, 0, 0, 0, 0, 0, 4],
            backgroundColor: '#74b9ff',
            borderColor: '#0984e3',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Period filter functionality
document.querySelectorAll('[data-period]').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('[data-period]').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const period = this.dataset.period;
        // Here you would typically update the chart with new data
        console.log('Filtering for', period, 'months');
    });
});

// Export functionality
function exportData(format) {
    // This would typically make an AJAX call to generate and download the file
    alert(`Exportando dados em formato ${format.toUpperCase()}...`);
    
    // Example: window.location.href = `/admin/reports/export/${format}`;
}
</script>
@endpush
