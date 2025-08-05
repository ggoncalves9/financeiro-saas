@extends('admin.layout')

@section('title', 'Gerenciar Assinaturas')
@section('page-title', 'Assinaturas')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0">Lista de Assinaturas</h6>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-arrow-left"></i> Voltar ao Dashboard
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuário</th>
                                <th>Plano</th>
                                <th>Status</th>
                                <th>Valor</th>
                                <th>Criado em</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscriptions as $subscription)
                            <tr>
                                <td>{{ $subscription->id }}</td>
                                <td>
                                    @if($subscription->user)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                    <span class="text-white fw-bold">{{ strtoupper(substr($subscription->user->name, 0, 1)) }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $subscription->user->name }}</div>
                                                <small class="text-muted">{{ $subscription->user->email }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Usuário não encontrado</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $subscription->plan ?? $subscription->stripe_price ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $subscription->status === 'active' ? 'success' : ($subscription->status === 'trialing' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </td>
                                <td>R$ {{ number_format($subscription->amount ?? 0, 2, ',', '.') }}</td>
                                <td>{{ $subscription->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" title="Ver detalhes">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($subscription->status !== 'active')
                                            <button type="button" class="btn btn-sm btn-outline-success subscription-activate" 
                                                    data-id="{{ $subscription->id }}" title="Ativar">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        @if($subscription->status === 'active')
                                            <button type="button" class="btn btn-sm btn-outline-danger subscription-cancel" 
                                                    data-id="{{ $subscription->id }}" title="Cancelar">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>Nenhuma assinatura encontrada.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $subscriptions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Subscription actions
document.querySelectorAll('.subscription-activate').forEach(button => {
    button.addEventListener('click', function() {
        const subscriptionId = this.dataset.id;
        
        if (confirm('Tem certeza que deseja ativar esta assinatura?')) {
            fetch(`/admin/subscriptions/${subscriptionId}/activate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erro ao ativar assinatura');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao ativar assinatura');
            });
        }
    });
});

document.querySelectorAll('.subscription-cancel').forEach(button => {
    button.addEventListener('click', function() {
        const subscriptionId = this.dataset.id;
        
        if (confirm('Tem certeza que deseja cancelar esta assinatura?')) {
            fetch(`/admin/subscriptions/${subscriptionId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erro ao cancelar assinatura');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao cancelar assinatura');
            });
        }
    });
});
</script>
@endpush
