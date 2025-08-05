@extends('layouts.uno-app')

@section('title', 'Perfil do Usuário')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="nav flex-column nav-pills" role="tablist">
                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#profile-tab">
                            <i class="fas fa-user me-2"></i>Dados Pessoais
                        </button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#security-tab">
                            <i class="fas fa-shield-alt me-2"></i>Segurança
                        </button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#subscription-tab">
                            <i class="fas fa-credit-card me-2"></i>Assinatura
                        </button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#preferences-tab">
                            <i class="fas fa-cog me-2"></i>Preferências
                        </button>
                        @if(auth()->user()->user_type === 'pj')
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#company-tab">
                            <i class="fas fa-building me-2"></i>Dados da Empresa
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="col-md-9">
            <div class="tab-content">
                <!-- Profile Tab -->
                <div class="tab-pane fade show active" id="profile-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Dados Pessoais</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <div class="row">
                                    <div class="col-md-4 text-center mb-4">
                                        <div class="position-relative">
                                            @if(auth()->user()->avatar)
                                                <img src="{{ auth()->user()->avatar_url }}" 
                                                     class="rounded-circle" width="150" height="150" 
                                                     style="object-fit: cover;">
                                            @else
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white" 
                                                     style="width: 150px; height: 150px; font-size: 3rem;">
                                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle"
                                                    onclick="document.getElementById('avatar').click()">
                                                <i class="fas fa-camera"></i>
                                            </button>
                                        </div>
                                        <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*">
                                    </div>

                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="name" class="form-label">Nome Completo</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                       id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="email" class="form-label">E-mail</label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                       id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="phone" class="form-label">Telefone</label>
                                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                                       id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="birth_date" class="form-label">Data de Nascimento</label>
                                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                                       id="birth_date" name="birth_date" 
                                                       value="{{ old('birth_date', auth()->user()->birth_date?->format('Y-m-d')) }}">
                                                @error('birth_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            @if(auth()->user()->user_type === 'pf')
                                            <div class="col-md-6 mb-3">
                                                <label for="cpf" class="form-label">CPF</label>
                                                <input type="text" class="form-control @error('cpf') is-invalid @enderror" 
                                                       id="cpf" name="cpf" value="{{ old('cpf', auth()->user()->cpf) }}">
                                                @error('cpf')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Salvar Alterações
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Security Tab -->
                <div class="tab-pane fade" id="security-tab">
                    <div class="row">
                        <!-- Change Password -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Alterar Senha</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('profile.password') }}">
                                        @csrf
                                        @method('PATCH')

                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">Senha Atual</label>
                                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                                   id="current_password" name="current_password" required>
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="password" class="form-label">Nova Senha</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" name="password" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                                            <input type="password" class="form-control" 
                                                   id="password_confirmation" name="password_confirmation" required>
                                        </div>

                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-key me-2"></i>Alterar Senha
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Two Factor Authentication -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Autenticação de Dois Fatores (2FA)</h6>
                                </div>
                                <div class="card-body">
                                    @if(auth()->user()->two_factor_secret)
                                        <div class="alert alert-success">
                                            <i class="fas fa-shield-alt me-2"></i>
                                            2FA está ativado em sua conta
                                        </div>

                                        <form method="POST" action="{{ route('profile.two-factor.disable') }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="fas fa-times me-2"></i>Desativar 2FA
                                            </button>
                                        </form>

                                        @if(auth()->user()->two_factor_recovery_codes)
                                        <hr>
                                        <h6>Códigos de Recuperação</h6>
                                        <div class="bg-light p-3 rounded">
                                            @foreach(json_decode(decrypt(auth()->user()->two_factor_recovery_codes)) as $code)
                                                <code class="d-block">{{ $code }}</code>
                                            @endforeach
                                        </div>
                                        <small class="text-muted">
                                            Guarde estes códigos em local seguro. Eles podem ser usados para acessar sua conta se você perder o dispositivo de autenticação.
                                        </small>
                                        @endif
                                    @else
                                        <p class="text-muted">
                                            Adicione uma camada extra de segurança à sua conta com autenticação de dois fatores.
                                        </p>

                                        <form method="POST" action="{{ route('profile.two-factor.enable') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-shield-alt me-2"></i>Ativar 2FA
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Sessions -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Sessões Ativas</h6>
                        </div>
                        <div class="card-body">
                            @if(isset($sessions) && $sessions->count() > 0)
                                @foreach($sessions as $session)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>
                                        <div class="fw-bold">{{ $session->user_agent }}</div>
                                        <small class="text-muted">
                                            IP: {{ $session->ip_address }} | 
                                            Última atividade: {{ $session->last_activity->diffForHumans() }}
                                        </small>
                                    </div>
                                    @if($session->id !== session()->getId())
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="revokeSession('{{ $session->id }}')">
                                            Revogar
                                        </button>
                                    @else
                                        <span class="badge bg-success">Sessão Atual</span>
                                    @endif
                                </div>
                                @endforeach

                                <div class="mt-3">
                                    <form method="POST" action="{{ route('profile.logout-other-devices') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-warning">
                                            <i class="fas fa-sign-out-alt me-2"></i>Deslogar de Todos os Dispositivos
                                        </button>
                                    </form>
                                </div>
                            @else
                                <p class="text-muted text-center py-3">Nenhuma sessão ativa encontrada</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Subscription Tab -->
                <div class="tab-pane fade" id="subscription-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Gerenciar Assinatura</h5>
                        </div>
                        <div class="card-body">
                            @if(auth()->user()->tenant && auth()->user()->tenant->subscription)
                                @php $subscription = auth()->user()->tenant->subscription @endphp
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Plano Atual</h6>
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $subscription->stripe_price }}</h5>
                                                <p class="card-text">
                                                    Status: 
                                                    <span class="badge {{ $subscription->stripe_status === 'active' ? 'bg-success' : 'bg-warning' }}">
                                                        {{ ucfirst($subscription->stripe_status) }}
                                                    </span>
                                                </p>
                                                @if($subscription->trial_ends_at)
                                                    <p class="card-text">
                                                        <small class="text-muted">
                                                            Período gratuito até: {{ $subscription->trial_ends_at->format('d/m/Y') }}
                                                        </small>
                                                    </p>
                                                @endif
                                                @if($subscription->ends_at)
                                                    <p class="card-text">
                                                        <small class="text-danger">
                                                            Cancela em: {{ $subscription->ends_at->format('d/m/Y') }}
                                                        </small>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h6>Próximo Pagamento</h6>
                                        @if($subscription->stripe_status === 'active' && !$subscription->ends_at)
                                            <p>{{ $subscription->asStripeSubscription()->current_period_end->format('d/m/Y') }}</p>
                                        @else
                                            <p class="text-muted">Não aplicável</p>
                                        @endif

                                        <div class="d-grid gap-2">
                                            @if($subscription->cancelled())
                                                <a href="{{ route('billing.resume') }}" class="btn btn-success">
                                                    <i class="fas fa-play me-2"></i>Reativar Assinatura
                                                </a>
                                            @else
                                                <a href="{{ route('billing.portal') }}" class="btn btn-primary">
                                                    <i class="fas fa-credit-card me-2"></i>Gerenciar Cobrança
                                                </a>
                                                <a href="{{ route('billing.cancel') }}" class="btn btn-outline-danger">
                                                    <i class="fas fa-times me-2"></i>Cancelar Assinatura
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-credit-card fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">Você está no plano gratuito</h5>
                                    <p class="text-muted">Faça upgrade para acessar recursos premium.</p>
                                    <a href="{{ route('billing.plans') }}" class="btn btn-primary">
                                        <i class="fas fa-arrow-up me-2"></i>Ver Planos
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Preferences Tab -->
                <div class="tab-pane fade" id="preferences-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Preferências do Sistema</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('profile.preferences') }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Notificações</h6>
                                        
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="email_notifications" 
                                                   name="email_notifications" value="1" 
                                                   {{ auth()->user()->email_notifications ? 'checked' : '' }}>
                                            <label class="form-check-label" for="email_notifications">
                                                Notificações por e-mail
                                            </label>
                                        </div>

                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="expense_alerts" 
                                                   name="expense_alerts" value="1" 
                                                   {{ auth()->user()->expense_alerts ? 'checked' : '' }}>
                                            <label class="form-check-label" for="expense_alerts">
                                                Alertas de despesas vencidas
                                            </label>
                                        </div>

                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="goal_notifications" 
                                                   name="goal_notifications" value="1" 
                                                   {{ auth()->user()->goal_notifications ? 'checked' : '' }}>
                                            <label class="form-check-label" for="goal_notifications">
                                                Notificações de metas
                                            </label>
                                        </div>

                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" id="weekly_reports" 
                                                   name="weekly_reports" value="1" 
                                                   {{ auth()->user()->weekly_reports ? 'checked' : '' }}>
                                            <label class="form-check-label" for="weekly_reports">
                                                Relatórios semanais
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h6>Formato</h6>

                                        <div class="mb-3">
                                            <label for="timezone" class="form-label">Fuso Horário</label>
                                            <select class="form-select" id="timezone" name="timezone">
                                                <option value="America/Sao_Paulo" {{ auth()->user()->timezone === 'America/Sao_Paulo' ? 'selected' : '' }}>
                                                    São Paulo (UTC-3)
                                                </option>
                                                <option value="America/Manaus" {{ auth()->user()->timezone === 'America/Manaus' ? 'selected' : '' }}>
                                                    Manaus (UTC-4)
                                                </option>
                                                <option value="America/Rio_Branco" {{ auth()->user()->timezone === 'America/Rio_Branco' ? 'selected' : '' }}>
                                                    Rio Branco (UTC-5)
                                                </option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="currency_format" class="form-label">Formato de Moeda</label>
                                            <select class="form-select" id="currency_format" name="currency_format">
                                                <option value="BRL" {{ auth()->user()->currency_format === 'BRL' ? 'selected' : '' }}>
                                                    Real Brasileiro (R$)
                                                </option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="date_format" class="form-label">Formato de Data</label>
                                            <select class="form-select" id="date_format" name="date_format">
                                                <option value="d/m/Y" {{ auth()->user()->date_format === 'd/m/Y' ? 'selected' : '' }}>
                                                    DD/MM/AAAA
                                                </option>
                                                <option value="Y-m-d" {{ auth()->user()->date_format === 'Y-m-d' ? 'selected' : '' }}>
                                                    AAAA-MM-DD
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Salvar Preferências
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Company Tab (Only for PJ users) -->
                @if(auth()->user()->user_type === 'pj')
                <div class="tab-pane fade" id="company-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Dados da Empresa</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('profile.company') }}">
                                @csrf
                                @method('PATCH')

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="company_name" class="form-label">Razão Social</label>
                                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                               id="company_name" name="company_name" 
                                               value="{{ old('company_name', auth()->user()->company_name) }}">
                                        @error('company_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="cnpj" class="form-label">CNPJ</label>
                                        <input type="text" class="form-control @error('cnpj') is-invalid @enderror" 
                                               id="cnpj" name="cnpj" value="{{ old('cnpj', auth()->user()->cnpj) }}">
                                        @error('cnpj')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="state_registration" class="form-label">Inscrição Estadual</label>
                                        <input type="text" class="form-control @error('state_registration') is-invalid @enderror" 
                                               id="state_registration" name="state_registration" 
                                               value="{{ old('state_registration', auth()->user()->state_registration) }}">
                                        @error('state_registration')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="city_registration" class="form-label">Inscrição Municipal</label>
                                        <input type="text" class="form-control @error('city_registration') is-invalid @enderror" 
                                               id="city_registration" name="city_registration" 
                                               value="{{ old('city_registration', auth()->user()->city_registration) }}">
                                        @error('city_registration')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="business_address" class="form-label">Endereço Comercial</label>
                                        <textarea class="form-control @error('business_address') is-invalid @enderror" 
                                                  id="business_address" name="business_address" rows="3">{{ old('business_address', auth()->user()->business_address) }}</textarea>
                                        @error('business_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Salvar Dados da Empresa
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function revokeSession(sessionId) {
    if (confirm('Tem certeza que deseja revogar esta sessão?')) {
        fetch(`/profile/sessions/${sessionId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {
            location.reload();
        });
    }
}

// Preview avatar upload
document.getElementById('avatar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.querySelector('img, .bg-primary');
            if (img.tagName === 'IMG') {
                img.src = e.target.result;
            } else {
                // Replace placeholder with image
                const newImg = document.createElement('img');
                newImg.src = e.target.result;
                newImg.className = 'rounded-circle';
                newImg.style.width = '150px';
                newImg.style.height = '150px';
                newImg.style.objectFit = 'cover';
                img.parentNode.replaceChild(newImg, img);
            }
        };
        reader.readAsDataURL(file);
    }
});

// Mask inputs
document.getElementById('cpf')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    e.target.value = value;
});

document.getElementById('cnpj')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/(\d{2})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d)/, '$1/$2');
    value = value.replace(/(\d{4})(\d{1,2})$/, '$1-$2');
    e.target.value = value;
});

document.getElementById('phone')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/(\d{2})(\d)/, '($1) $2');
    value = value.replace(/(\d{4})(\d)/, '$1-$2');
    value = value.replace(/(\d{4})-(\d)(\d{4})/, '$1$2-$3');
    e.target.value = value;
});
</script>
@endpush
