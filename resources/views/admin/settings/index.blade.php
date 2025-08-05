@extends('admin.layout')

@section('title', 'Configurações')
@section('page-title', 'Configurações do Sistema')

@section('content')
<div class="row">
    <!-- General Settings -->
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0">Configurações Gerais</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.general.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="app_name" class="form-label">Nome da Aplicação</label>
                        <input type="text" class="form-control" id="app_name" name="app_name" 
                               value="{{ config('app.name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="app_url" class="form-label">URL da Aplicação</label>
                        <input type="url" class="form-control" id="app_url" name="app_url" 
                               value="{{ config('app.url') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="timezone" class="form-label">Fuso Horário</label>
                        <select class="form-select" id="timezone" name="timezone">
                            <option value="America/Sao_Paulo" selected>America/São Paulo</option>
                            <option value="UTC">UTC</option>
                            <option value="America/New_York">America/New York</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode">
                            <label class="form-check-label" for="maintenance_mode">
                                Modo Manutenção
                            </label>
                        </div>
                        <small class="text-muted">Desabilita o acesso público ao sistema</small>
                    </div>

                    <button type="submit" class="btn btn-primary">Salvar Configurações</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Email Settings -->
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0">Configurações de Email</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.email.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="mail_driver" class="form-label">Driver de Email</label>
                        <select class="form-select" id="mail_driver" name="mail_driver">
                            <option value="smtp" selected>SMTP</option>
                            <option value="mailgun">Mailgun</option>
                            <option value="ses">Amazon SES</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="mail_host" class="form-label">Host SMTP</label>
                        <input type="text" class="form-control" id="mail_host" name="mail_host" 
                               value="{{ config('mail.mailers.smtp.host') }}" placeholder="smtp.gmail.com">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="mail_port" class="form-label">Porta</label>
                            <input type="number" class="form-control" id="mail_port" name="mail_port" 
                                   value="{{ config('mail.mailers.smtp.port') }}" placeholder="587">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="mail_encryption" class="form-label">Criptografia</label>
                            <select class="form-select" id="mail_encryption" name="mail_encryption">
                                <option value="tls" selected>TLS</option>
                                <option value="ssl">SSL</option>
                                <option value="">Nenhuma</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="mail_username" class="form-label">Usuário</label>
                        <input type="email" class="form-control" id="mail_username" name="mail_username" 
                               value="{{ config('mail.mailers.smtp.username') }}">
                    </div>

                    <div class="mb-3">
                        <label for="mail_from_address" class="form-label">Email de Envio</label>
                        <input type="email" class="form-control" id="mail_from_address" name="mail_from_address" 
                               value="{{ config('mail.from.address') }}">
                    </div>

                    <button type="submit" class="btn btn-success">Salvar Email</button>
                    <button type="button" class="btn btn-outline-primary" onclick="testEmail()">Testar Email</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Security Settings -->
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0">Configurações de Segurança</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.general.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="force_https" name="force_https">
                            <label class="form-check-label" for="force_https">
                                Forçar HTTPS
                            </label>
                        </div>
                        <small class="text-muted">Redireciona automaticamente HTTP para HTTPS</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="two_factor_required" name="two_factor_required">
                            <label class="form-check-label" for="two_factor_required">
                                2FA Obrigatório para Admins
                            </label>
                        </div>
                        <small class="text-muted">Exige autenticação de dois fatores para administradores</small>
                    </div>

                    <div class="mb-3">
                        <label for="session_lifetime" class="form-label">Tempo de Sessão (minutos)</label>
                        <input type="number" class="form-control" id="session_lifetime" name="session_lifetime" 
                               value="120" min="5" max="1440">
                    </div>

                    <div class="mb-3">
                        <label for="max_login_attempts" class="form-label">Máximo de Tentativas de Login</label>
                        <input type="number" class="form-control" id="max_login_attempts" name="max_login_attempts" 
                               value="5" min="3" max="20">
                    </div>

                    <button type="submit" class="btn btn-warning">Salvar Segurança</button>
                </form>
            </div>
        </div>
    </div>

    <!-- System Info -->
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0">Informações do Sistema</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>Versão Laravel:</strong></td>
                        <td>{{ app()->version() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Versão PHP:</strong></td>
                        <td>{{ PHP_VERSION }}</td>
                    </tr>
                    <tr>
                        <td><strong>Ambiente:</strong></td>
                        <td>
                            <span class="badge bg-{{ app()->environment() === 'production' ? 'success' : 'warning' }}">
                                {{ strtoupper(app()->environment()) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Debug:</strong></td>
                        <td>
                            <span class="badge bg-{{ config('app.debug') ? 'danger' : 'success' }}">
                                {{ config('app.debug') ? 'ATIVO' : 'INATIVO' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Timezone:</strong></td>
                        <td>{{ config('app.timezone') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Locale:</strong></td>
                        <td>{{ config('app.locale') }}</td>
                    </tr>
                </table>

                <hr>

                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary" onclick="clearCache()">
                        <i class="fas fa-broom me-2"></i>
                        Limpar Cache
                    </button>
                    <button class="btn btn-outline-warning" onclick="clearLogs()">
                        <i class="fas fa-trash me-2"></i>
                        Limpar Logs
                    </button>
                    <button class="btn btn-outline-info" onclick="optimizeSystem()">
                        <i class="fas fa-rocket me-2"></i>
                        Otimizar Sistema
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Toggle -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0">Recursos do Sistema</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enable_registration" name="enable_registration" checked>
                            <label class="form-check-label" for="enable_registration">
                                <strong>Registro de Usuários</strong><br>
                                <small class="text-muted">Permite novos cadastros</small>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enable_subscriptions" name="enable_subscriptions" checked>
                            <label class="form-check-label" for="enable_subscriptions">
                                <strong>Sistema de Assinaturas</strong><br>
                                <small class="text-muted">Ativa cobrança recorrente</small>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enable_notifications" name="enable_notifications" checked>
                            <label class="form-check-label" for="enable_notifications">
                                <strong>Notificações</strong><br>
                                <small class="text-muted">Emails e alertas automáticos</small>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enable_api" name="enable_api">
                            <label class="form-check-label" for="enable_api">
                                <strong>API Externa</strong><br>
                                <small class="text-muted">Acesso via API REST</small>
                            </label>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" onclick="saveFeatures()">Salvar Recursos</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function testEmail() {
    // Simula teste de email
    alert('Funcionalidade de teste de email será implementada');
}

function clearCache() {
    if (confirm('Tem certeza que deseja limpar o cache?')) {
        fetch('/admin/system/cache/clear', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cache limpo com sucesso!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao limpar cache');
        });
    }
}

function clearLogs() {
    if (confirm('Tem certeza que deseja limpar os logs?')) {
        alert('Funcionalidade de limpeza de logs será implementada');
    }
}

function optimizeSystem() {
    if (confirm('Tem certeza que deseja otimizar o sistema?')) {
        alert('Funcionalidade de otimização será implementada');
    }
}

function saveFeatures() {
    const features = {
        enable_registration: document.getElementById('enable_registration').checked,
        enable_subscriptions: document.getElementById('enable_subscriptions').checked,
        enable_notifications: document.getElementById('enable_notifications').checked,
        enable_api: document.getElementById('enable_api').checked,
    };

    fetch('/admin/settings/features', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(features)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Recursos salvos com sucesso!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao salvar recursos');
    });
}
</script>
@endpush
