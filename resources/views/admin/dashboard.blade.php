@extends('admin.layout')

@section('title', 'Dashboard Administrativo')
@section('page-title', 'Dashboard Administrativo')

@section('content')
<div class="row mb-4">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Total de Usuários</h6>
                        <h2 class="text-white">{{ number_format($stats['users']['total']) }}</h2>
                        <small class="text-white-50">
                            <i class="fas fa-arrow-up me-1"></i>
                            +{{ $stats['users']['new_this_month'] }} este mês
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x text-white-50"></i>
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
                        <h6 class="card-title text-white-50">Usuários Ativos</h6>
                        <h2 class="text-white">{{ number_format($stats['users']['active']) }}</h2>
                        <small class="text-white-50">
                            {{ number_format(($stats['users']['active'] / max($stats['users']['total'], 1)) * 100, 1) }}% do total
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-check fa-2x text-white-50"></i>
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
                        <h6 class="card-title text-white-50">Assinaturas Ativas</h6>
                        <h2 class="text-white">{{ number_format($stats['subscriptions']['active']) }}</h2>
                        <small class="text-white-50">
                            {{ $stats['subscriptions']['trial'] }} em teste
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-credit-card fa-2x text-white-50"></i>
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
                        <h6 class="card-title text-white-50">Receita Total</h6>
                        <h2 class="text-white">R$ {{ number_format($stats['financials']['total_revenue'], 2, ',', '.') }}</h2>
                        <small class="text-white-50">
                            R$ {{ number_format($stats['financials']['month_revenue'], 2, ',', '.') }} este mês
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- User Growth Chart -->
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0">Crescimento de Usuários (2025)</h6>
            </div>
            <div class="card-body">
                <canvas id="userGrowthChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- User Type Distribution -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0">Distribuição por Tipo</h6>
            </div>
            <div class="card-body">
                <canvas id="userTypeChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Users -->
    <div class="col-xl-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0">Usuários Recentes</h6>
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">Ver Todos</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th>Cadastro</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUsers as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                <span class="text-white fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $user->name }}</div>
                                            @if($user->company_name)
                                                <small class="text-muted">{{ $user->company_name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->type === 'pj' ? 'primary' : 'secondary' }}">
                                        {{ strtoupper($user->type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                        {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Info -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-muted">Total de Contas</h6>
                <h3 class="text-primary">{{ number_format($stats['financials']['total_accounts']) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-muted">Usuários PJ</h6>
                <h3 class="text-info">{{ number_format($stats['users']['pj']) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-muted">Usuários PF</h6>
                <h3 class="text-warning">{{ number_format($stats['users']['pf']) }}</h3>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// User Growth Chart
const ctx1 = document.getElementById('userGrowthChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        datasets: [{
            label: 'Novos Usuários',
            data: @json($stats['charts']['monthly_registrations']),
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

// User Type Chart
const ctx2 = document.getElementById('userTypeChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['Pessoa Jurídica', 'Pessoa Física'],
        datasets: [{
            data: [{{ $stats['users']['pj'] }}, {{ $stats['users']['pf'] }}],
            backgroundColor: ['#6c5ce7', '#74b9ff'],
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
</script>
@endpush
