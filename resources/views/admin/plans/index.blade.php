@extends('admin.layout')

@section('title', 'Gestão de Planos')
@section('page-title', 'Gestão de Planos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Planos de Assinatura</h1>
    <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Novo Plano
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    @forelse($plans as $plan)
    <div class="col-md-4 mb-4">
        <div class="card h-100 {{ !$plan->is_active ? 'border-secondary' : '' }}">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $plan->name }}</h5>
                <div>
                    @if($plan->is_active)
                        <span class="badge bg-success">Ativo</span>
                    @else
                        <span class="badge bg-secondary">Inativo</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <h2 class="text-primary">{{ $plan->formatted_price }}</h2>
                    <small class="text-muted">{{ $plan->billing_cycle_label }}</small>
                </div>

                @if($plan->description)
                    <p class="text-muted">{{ $plan->description }}</p>
                @endif

                <div class="mb-3">
                    <strong>Limites:</strong>
                    <ul class="list-unstyled ms-3">
                        @if($plan->max_users)
                            <li><i class="fas fa-users me-2"></i>{{ $plan->max_users }} usuários</li>
                        @else
                            <li><i class="fas fa-users me-2"></i>Usuários ilimitados</li>
                        @endif
                        
                        @if($plan->max_transactions)
                            <li><i class="fas fa-exchange-alt me-2"></i>{{ number_format($plan->max_transactions) }} transações/mês</li>
                        @else
                            <li><i class="fas fa-exchange-alt me-2"></i>Transações ilimitadas</li>
                        @endif

                        @if($plan->trial_days > 0)
                            <li><i class="fas fa-clock me-2"></i>{{ $plan->trial_days }} dias de teste</li>
                        @endif
                    </ul>
                </div>

                @if($plan->features && count($plan->features) > 0)
                    <div class="mb-3">
                        <strong>Funcionalidades:</strong>
                        <ul class="list-unstyled ms-3">
                            @foreach($plan->features as $feature)
                                <li><i class="fas fa-check text-success me-2"></i>{{ $feature }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="text-muted small">
                    <strong>{{ $plan->users_count }}</strong> usuário(s) usando este plano
                </div>
            </div>
            <div class="card-footer">
                <div class="btn-group w-100">
                    <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    
                    @if($plan->is_active)
                        <form action="{{ route('admin.plans.deactivate', $plan) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-pause"></i> Desativar
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.plans.activate', $plan) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-play"></i> Ativar
                            </button>
                        </form>
                    @endif

                    @if($plan->users_count == 0)
                        <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Tem certeza que deseja excluir este plano?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash"></i> Excluir
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>
            Nenhum plano cadastrado. <a href="{{ route('admin.plans.create') }}">Criar primeiro plano</a>
        </div>
    </div>
    @endforelse
</div>
@endsection
