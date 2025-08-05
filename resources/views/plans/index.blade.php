
@extends('layouts.uno-app')
@section('title', 'Planos')
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2 class="mb-3"><i class="fas fa-rocket text-primary"></i> Escolha o Plano Ideal</h2>
            <p class="lead text-muted">Selecione o plano que melhor atende às suas necessidades</p>
        </div>
    </div>

    <div class="row justify-content-center">
        @foreach($plans as $plan)
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100 {{ $plan->name === 'Premium' ? 'border-primary shadow' : '' }}">
                @if($plan->name === 'Premium')
                <div class="badge bg-primary position-absolute top-0 start-50 translate-middle px-3 py-2">
                    <i class="fas fa-star me-1"></i> Mais Popular
                </div>
                @endif
                
                <div class="card-body text-center d-flex flex-column">
                    <h5 class="card-title mt-2">{{ $plan->name }}</h5>
                    <p class="text-muted small">{{ $plan->description }}</p>
                    
                    <div class="price-section my-4">
                        @if($plan->price == 0)
                            <h3 class="text-success mb-0">Gratuito</h3>
                            <small class="text-muted">Para sempre</small>
                        @else
                            <h3 class="text-primary mb-0">
                                R$ {{ number_format($plan->price, 2, ',', '.') }}
                            </h3>
                            <small class="text-muted">
                                por {{ $plan->billing_cycle === 'monthly' ? 'mês' : 'ano' }}
                            </small>
                        @endif
                    </div>

                    <ul class="list-unstyled text-start flex-grow-1">
                        @if($plan->features)
                            @foreach($plan->features as $feature)
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                {{ $feature }}
                            </li>
                            @endforeach
                        @endif
                    </ul>

                    <div class="mt-auto">
                        @if($plan->trial_days > 0)
                            <p class="small text-info mb-2">
                                <i class="fas fa-gift me-1"></i>
                                {{ $plan->trial_days }} dias grátis
                            </p>
                        @endif

                        @if($plan->price == 0)
                            <button class="btn btn-outline-success w-100" onclick="selectPlan({{ $plan->id }})">
                                Começar Grátis
                            </button>
                        @else
                            <button class="btn {{ $plan->name === 'Premium' ? 'btn-primary' : 'btn-outline-primary' }} w-100" 
                                    onclick="selectPlan({{ $plan->id }})">
                                Assinar Agora
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Comparison Table -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h5 class="mb-0">Comparação Detalhada dos Planos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Recurso</th>
                                    @foreach($plans as $plan)
                                    <th class="text-center">{{ $plan->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Preço</strong></td>
                                    @foreach($plans as $plan)
                                    <td class="text-center">
                                        @if($plan->price == 0)
                                            <span class="text-success">Gratuito</span>
                                        @else
                                            <strong>R$ {{ number_format($plan->price, 2, ',', '.') }}</strong>
                                            <br><small class="text-muted">{{ $plan->billing_cycle === 'monthly' ? '/mês' : '/ano' }}</small>
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td>Transações</td>
                                    @foreach($plans as $plan)
                                    <td class="text-center">
                                        @if($plan->max_transactions)
                                            {{ $plan->max_transactions }} por mês
                                        @else
                                            <i class="fas fa-check text-success"></i> Ilimitadas
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td>Usuários</td>
                                    @foreach($plans as $plan)
                                    <td class="text-center">
                                        @if($plan->max_users)
                                            {{ $plan->max_users }} usuário{{ $plan->max_users > 1 ? 's' : '' }}
                                        @else
                                            <i class="fas fa-check text-success"></i> Ilimitados
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td>Período de Teste</td>
                                    @foreach($plans as $plan)
                                    <td class="text-center">
                                        @if($plan->trial_days > 0)
                                            <span class="text-info">{{ $plan->trial_days }} dias</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectPlan(planId) {
    @auth
        window.location.href = `/checkout/${planId}`;
    @else
        window.location.href = `/register?plan=${planId}`;
    @endauth
}
</script>
@endsection
