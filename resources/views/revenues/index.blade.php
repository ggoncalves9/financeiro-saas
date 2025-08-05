@extends('layouts.uno-app')

@section('title', 'Receitas')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Receitas</h1>
            <p class="text-muted mb-0">Gerencie todas as suas receitas</p>
        </div>
        <div>
            <a href="{{ route('revenues.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Nova Receita
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('revenues.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Título ou descrição">
                </div>
                
                <div class="col-md-2">
                    <label for="category" class="form-label">Categoria</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">Todas</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Todos</option>
                        <option value="received" {{ request('status') === 'received' ? 'selected' : '' }}>Recebida</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="date_from" class="form-label">Data Inicial</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>

                <div class="col-md-2">
                    <label for="date_to" class="form-label">Data Final</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>

                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Recebido</h6>
                            <h4>{{ $summary['received_formatted'] }}</h4>
                        </div>
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">A Receber</h6>
                            <h4>{{ $summary['pending_formatted'] }}</h4>
                        </div>
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Geral</h6>
                            <h4>{{ $summary['total_formatted'] }}</h4>
                        </div>
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Média Mensal</h6>
                            <h4>{{ $summary['monthly_average_formatted'] }}</h4>
                        </div>
                        <i class="fas fa-calculator fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenues Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Receitas</h5>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="exportRevenues('csv')">
                    <i class="fas fa-file-csv me-1"></i>CSV
                </button>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="exportRevenues('excel')">
                    <i class="fas fa-file-excel me-1"></i>Excel
                </button>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="exportRevenues('pdf')">
                    <i class="fas fa-file-pdf me-1"></i>PDF
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($revenues->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'title', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none text-dark">
                                        Título
                                        @if(request('sort') === 'title')
                                            <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Categoria</th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'amount', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none text-dark">
                                        Valor
                                        @if(request('sort') === 'amount')
                                            <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'date', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                                       class="text-decoration-none text-dark">
                                        Data
                                        @if(request('sort') === 'date')
                                            <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Status</th>
                                <th>Recorrente</th>
                                <th width="120">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($revenues as $revenue)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $revenue->title }}</div>
                                    @if($revenue->description)
                                        <div class="text-muted small">{{ Str::limit($revenue->description, 50) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $revenue->category }}</span>
                                    @if($revenue->is_business)
                                        <span class="badge bg-info">Empresarial</span>
                                    @endif
                                </td>
                                <td class="fw-bold text-success">{{ $revenue->formatted_amount }}</td>
                                <td>{{ $revenue->date->format('d/m/Y') }}</td>
                                <td>
                                    @if($revenue->status === 'received')
                                        <span class="badge bg-success">Recebida</span>
                                    @elseif($revenue->status === 'pending')
                                        <span class="badge bg-warning">Pendente</span>
                                    @else
                                        <span class="badge bg-danger">Cancelada</span>
                                    @endif
                                </td>
                                <td>
                                    @if($revenue->recurring)
                                        <span class="badge bg-primary">{{ ucfirst($revenue->recurring_type) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('revenues.show', $revenue) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('revenues.edit', $revenue) }}" 
                                           class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteRevenue({{ $revenue->id }})" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Mostrando {{ $revenues->firstItem() }} a {{ $revenues->lastItem() }} 
                        de {{ $revenues->total() }} registros
                    </div>
                    {{ $revenues->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-arrow-up fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhuma receita encontrada</h5>
                    <p class="text-muted">Crie sua primeira receita para começar a controlar suas finanças.</p>
                    <a href="{{ route('revenues.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Nova Receita
                    </a>
                </div>
            @endif
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
                <p>Tem certeza que deseja excluir esta receita?</p>
                <p class="text-muted small">Esta ação não pode ser desfeita.</p>
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
function deleteRevenue(id) {
    const form = document.getElementById('deleteForm');
    form.action = `{{ route('revenues.index') }}/${id}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function exportRevenues(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('export', format);
    
    window.location.href = `{{ route('revenues.index') }}?${params.toString()}`;
}

// Auto-submit form on filter changes
document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('#category, #status');
    
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>
@endpush
