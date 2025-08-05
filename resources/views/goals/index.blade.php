@extends('layouts.uno-app')

@section('title', 'Metas')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Metas Financeiras</h1>
            <p class="text-muted mb-0">Defina e acompanhe seus objetivos financeiros</p>
        </div>
        <div>
            <a href="{{ route('goals.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nova Meta
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Metas Ativas</h6>
                            <h4>{{ $stats['active_goals'] }}</h4>
                        </div>
                        <i class="fas fa-bullseye fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Metas Concluídas</h6>
                            <h4>{{ $stats['completed_goals'] }}</h4>
                        </div>
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Economizado</h6>
                            <h4>{{ $stats['total_saved_formatted'] }}</h4>
                        </div>
                        <i class="fas fa-piggy-bank fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Progresso Médio</h6>
                            <h4>{{ number_format($stats['average_progress'], 1) }}%</h4>
                        </div>
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('goals.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Título da meta">
                </div>
                
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Todas</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativas</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Concluídas</option>
                        <option value="paused" {{ request('status') === 'paused' ? 'selected' : '' }}>Pausadas</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Canceladas</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="type" class="form-label">Tipo</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">Todos</option>
                        <option value="savings" {{ request('type') === 'savings' ? 'selected' : '' }}>Economia</option>
                        <option value="expense_reduction" {{ request('type') === 'expense_reduction' ? 'selected' : '' }}>Redução de Gastos</option>
                        <option value="investment" {{ request('type') === 'investment' ? 'selected' : '' }}>Investimento</option>
                        <option value="debt_payment" {{ request('type') === 'debt_payment' ? 'selected' : '' }}>Pagamento de Dívida</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="target_date" class="form-label">Prazo até</label>
                    <input type="date" class="form-control" id="target_date" name="target_date" value="{{ request('target_date') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Goals Grid -->
    @if($goals->count() > 0)
        <div class="row">
            @foreach($goals as $goal)
            <div class="col-lg-6 col-xl-4 mb-4">
                <div class="card goal-card h-100 {{ $goal->status === 'completed' ? 'border-success' : ($goal->status === 'paused' ? 'border-warning' : 'border-primary') }}">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">{{ $goal->title }}</h6>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('goals.show', $goal) }}">
                                    <i class="fas fa-eye me-2"></i>Ver Detalhes
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('goals.edit', $goal) }}">
                                    <i class="fas fa-edit me-2"></i>Editar
                                </a></li>
                                @if($goal->status === 'active')
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" onclick="addContribution({{ $goal->id }})">
                                        <i class="fas fa-plus me-2"></i>Adicionar Valor
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="pauseGoal({{ $goal->id }})">
                                        <i class="fas fa-pause me-2"></i>Pausar Meta
                                    </a></li>
                                @elseif($goal->status === 'paused')
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" onclick="resumeGoal({{ $goal->id }})">
                                        <i class="fas fa-play me-2"></i>Reativar Meta
                                    </a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteGoal({{ $goal->id }})">
                                    <i class="fas fa-trash me-2"></i>Excluir
                                </a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($goal->description)
                            <p class="text-muted small mb-3">{{ Str::limit($goal->description, 80) }}</p>
                        @endif

                        <!-- Progress -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small fw-bold">Progresso</span>
                                <span class="small {{ $goal->progress_percentage >= 100 ? 'text-success' : 'text-primary' }} fw-bold">
                                    {{ $goal->progress_percentage }}%
                                </span>
                            </div>
                            <div class="progress mb-2" style="height: 10px;">
                                <div class="progress-bar {{ $goal->progress_percentage >= 100 ? 'bg-success' : 'bg-primary' }}" 
                                     role="progressbar" style="width: {{ min($goal->progress_percentage, 100) }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="small text-muted">{{ $goal->formatted_current_amount }}</span>
                                <span class="small text-muted">{{ $goal->formatted_target_amount }}</span>
                            </div>
                        </div>

                        <!-- Goal Info -->
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <div class="small text-muted">Restante</div>
                                    <div class="fw-bold {{ $goal->remaining_amount > 0 ? 'text-primary' : 'text-success' }}">
                                        {{ $goal->formatted_remaining_amount }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="small text-muted">Prazo</div>
                                @if($goal->target_date)
                                    <div class="fw-bold {{ $goal->target_date->isPast() && $goal->status !== 'completed' ? 'text-danger' : 'text-dark' }}">
                                        {{ $goal->target_date->format('d/m/Y') }}
                                    </div>
                                    <div class="small text-muted">
                                        {{ $goal->target_date->diffForHumans() }}
                                    </div>
                                @else
                                    <div class="text-muted">Sem prazo</div>
                                @endif
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="mt-3 text-center">
                            @if($goal->status === 'completed')
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Concluída
                                </span>
                            @elseif($goal->status === 'paused')
                                <span class="badge bg-warning">
                                    <i class="fas fa-pause me-1"></i>Pausada
                                </span>
                            @elseif($goal->status === 'cancelled')
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i>Cancelada
                                </span>
                            @else
                                <span class="badge bg-primary">
                                    <i class="fas fa-bullseye me-1"></i>Ativa
                                </span>
                            @endif

                            @if($goal->auto_save_enabled)
                                <span class="badge bg-info ms-1">
                                    <i class="fas fa-robot me-1"></i>Auto-save
                                </span>
                            @endif
                        </div>

                        <!-- Quick Actions for Active Goals -->
                        @if($goal->status === 'active')
                            <div class="mt-3 d-grid gap-2">
                                <button class="btn btn-sm btn-outline-primary" onclick="addContribution({{ $goal->id }})">
                                    <i class="fas fa-plus me-1"></i>Adicionar R$ 50
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $goals->links() }}
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-bullseye fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Nenhuma meta encontrada</h5>
                <p class="text-muted">Defina suas metas financeiras para alcançar seus objetivos.</p>
                <a href="{{ route('goals.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Criar Primeira Meta
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Add Contribution Modal -->
<div class="modal fade" id="contributionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Contribuição</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="contributionForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Valor</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" class="form-control" id="amount" name="amount" 
                                   step="0.01" min="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição (opcional)</label>
                        <input type="text" class="form-control" id="description" name="description" 
                               placeholder="Ex: Salário de março">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Adicionar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir esta meta?</p>
                <p class="text-muted small">Esta ação não pode ser desfeita e todo o progresso será perdido.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function addContribution(goalId, amount = null) {
    const form = document.getElementById('contributionForm');
    form.action = `/goals/${goalId}/contribute`;
    
    if (amount) {
        document.getElementById('amount').value = amount;
    } else {
        document.getElementById('amount').value = '';
    }
    
    const modal = new bootstrap.Modal(document.getElementById('contributionModal'));
    modal.show();
}

function pauseGoal(goalId) {
    if (confirm('Tem certeza que deseja pausar esta meta?')) {
        fetch(`/goals/${goalId}/pause`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {
            location.reload();
        });
    }
}

function resumeGoal(goalId) {
    if (confirm('Tem certeza que deseja reativar esta meta?')) {
        fetch(`/goals/${goalId}/resume`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {
            location.reload();
        });
    }
}

function deleteGoal(goalId) {
    const form = document.getElementById('deleteForm');
    form.action = `/goals/${goalId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Auto-submit form on filter changes
document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('#status, #type');
    
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>
@endpush
