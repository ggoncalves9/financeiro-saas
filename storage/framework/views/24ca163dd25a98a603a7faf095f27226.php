<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Financeiro SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                    <div class="container">
                        <a class="navbar-brand" href="#">
                            <i class="fas fa-chart-line me-2"></i>
                            Financeiro SaaS
                        </a>
                        <div class="navbar-nav ms-auto">
                            <a class="nav-link" href="/login">
                                <i class="fas fa-sign-in-alt me-1"></i>
                                Login
                            </a>
                            <a class="nav-link" href="/register">
                                <i class="fas fa-user-plus me-1"></i>
                                Registrar
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <div class="row min-vh-100">
            <div class="col-12">
                <div class="hero-section bg-gradient text-white py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="container text-center">
                        <h1 class="display-4 mb-4">
                            <i class="fas fa-rocket me-3"></i>
                            Sistema Financeiro SaaS
                        </h1>
                        <p class="lead mb-4">
                            Gerencie suas finanças de forma inteligente e segura
                        </p>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-transparent border-light mb-4">
                                    <div class="card-body text-center">
                                        <i class="fas fa-chart-bar fa-3x mb-3"></i>
                                        <h5>Dashboard Completo</h5>
                                        <p>Visualize suas métricas financeiras em tempo real</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-transparent border-light mb-4">
                                    <div class="card-body text-center">
                                        <i class="fas fa-shield-alt fa-3x mb-3"></i>
                                        <h5>Segurança LGPD</h5>
                                        <p>Conformidade total com regulamentações de privacidade</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-transparent border-light mb-4">
                                    <div class="card-body text-center">
                                        <i class="fas fa-users fa-3x mb-3"></i>
                                        <h5>Multi-tenant</h5>
                                        <p>Suporte completo para múltiplos clientes</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container py-5">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h2 class="mb-4">Status do Sistema</h2>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Sistema Operacional!</strong> Todas as funcionalidades estão ativas.
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-cogs me-2"></i>
                                        Funcionalidades
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Laravel 10+ Framework
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Sistema de Autenticação
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Dashboard Financeiro
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Integração Stripe
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Relatórios PDF
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fas fa-check text-success me-2"></i>
                                            Exportação Excel
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user-cog me-2"></i>
                                        Usuários de Teste
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Administrador:</strong><br>
                                        <small class="text-muted">Email:</small> admin@admin.com<br>
                                        <small class="text-muted">Senha:</small> password
                                    </div>
                                    <div class="mb-3">
                                        <strong>Usuário:</strong><br>
                                        <small class="text-muted">Email:</small> user@user.com<br>
                                        <small class="text-muted">Senha:</small> password
                                    </div>
                                    <a href="/login" class="btn btn-success">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        Fazer Login
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="bg-dark text-white py-4">
            <div class="container text-center">
                <p class="mb-0">
                    <i class="fas fa-code me-2"></i>
                    Sistema Financeiro SaaS - Laravel 10+ | PHP 8.3.16
                </p>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH /home/ubuntu/financeiro-saas/resources/views/welcome.blade.php ENDPATH**/ ?>