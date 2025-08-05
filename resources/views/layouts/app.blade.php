<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Financeiro SaaS') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        /* Variáveis CSS - Estilo Mobills */
        :root {
            --primary-color: #6c5ce7;
            --success-color: #00b894;
            --warning-color: #fdcb6e;
            --danger-color: #e17055;
            --dark-color: #2d3436;
            --light-color: #ddd;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-success: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --gradient-warning: linear-gradient(135deg, #fc7944 0%, #ff9a00 100%);
            --gradient-danger: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }

        /* Layout geral */
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Figtree', sans-serif;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: var(--gradient-primary);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            overflow-y: auto;
        }

        .main-content {
            margin-left: 250px;
            padding: 0;
            min-height: 100vh;
        }

        .main-content.no-sidebar {
            margin-left: 0;
        }

        /* Logo e navegação */
        .logo-container {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .logo-container h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 1.4rem;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 15px 20px;
            border-radius: 0;
            margin: 0;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-left: 3px solid white;
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 12px;
        }

        /* Cards e componentes */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .stats-card {
            background: var(--gradient-primary);
            color: white;
            border-radius: 16px;
            padding: 24px;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: 1;
        }

        .stats-card.success {
            background: var(--gradient-success);
        }

        .stats-card.warning {
            background: var(--gradient-warning);
        }

        .stats-card.danger {
            background: var(--gradient-danger);
        }

        /* Botões */
        .btn {
            border-radius: 12px;
            padding: 10px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: var(--gradient-primary);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(108, 92, 231, 0.4);
        }

        .btn-success {
            background: var(--gradient-success);
        }

        .btn-warning {
            background: var(--gradient-warning);
        }

        .btn-danger {
            background: var(--gradient-danger);
        }

        /* Formulários */
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(108, 92, 231, 0.25);
        }

        /* Tabelas */
        .table {
            border-radius: 12px;
            overflow: hidden;
            background: white;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--dark-color);
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 16px;
        }

        .table td {
            padding: 16px;
            vertical-align: middle;
            border-top: 1px solid #f1f3f4;
        }

        .table tbody tr:hover {
            background-color: rgba(108, 92, 231, 0.05);
        }

        /* Badges */
        .badge {
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.75rem;
        }

        /* Navbar */
        .navbar {
            background: white !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 0 0 20px 20px;
            margin-bottom: 30px;
            padding: 16px 0;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }

        /* Dropdown */
        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Alertas */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 16px 20px;
        }

        /* Paginação */
        .pagination .page-link {
            border: none;
            border-radius: 8px;
            margin: 0 2px;
            color: var(--primary-color);
        }

        .pagination .page-item.active .page-link {
            background: var(--gradient-primary);
            border: none;
        }

        /* Mobile responsivo */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            
            .mobile-menu-toggle {
                display: block !important;
            }
        }

        /* Animações */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeInUp 0.6s ease-out;
        }

        .card:nth-child(2) { animation-delay: 0.1s; }
        .card:nth-child(3) { animation-delay: 0.2s; }
        .card:nth-child(4) { animation-delay: 0.3s; }

        /* Utilitários */
        .text-money-positive {
            color: var(--success-color);
        }

        .text-money-negative {
            color: var(--danger-color);
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="d-flex">
        @auth
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="logo-container">
                <i class="fas fa-chart-line mb-2" style="font-size: 2rem;"></i>
                <h4>{{ config('app.name', 'Financeiro SaaS') }}</h4>
                <small style="color: rgba(255,255,255,0.8);">Sua gestão financeira</small>
            </div>

            <ul class="nav flex-column px-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('accounts.*') ? 'active' : '' }}" href="{{ route('accounts.index') }}">
                        <i class="fas fa-university"></i>
                        Contas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('revenues.*') ? 'active' : '' }}" href="{{ route('revenues.index') }}">
                        <i class="fas fa-arrow-up"></i>
                        Receitas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="{{ route('expenses.index') }}">
                        <i class="fas fa-arrow-down"></i>
                        Despesas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('goals.*') ? 'active' : '' }}" href="{{ route('goals.index') }}">
                        <i class="fas fa-bullseye"></i>
                        Metas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                        <i class="fas fa-tags"></i>
                        Categorias
                    </a>
                </li>

                @if(auth()->user()->isPJ())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('team.*') ? 'active' : '' }}" href="{{ route('team.index') }}">
                        <i class="fas fa-users"></i>
                        Equipe
                    </a>
                </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                        <i class="fas fa-chart-bar"></i>
                        Relatórios
                    </a>
                </li>

                <li class="nav-item mt-4">
                    <hr style="border-color: rgba(255,255,255,0.2);">
                </li>

                {{-- Admin Panel removido do menu principal --}}

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                        <i class="fas fa-user"></i>
                        Perfil
                    </a>
                </li>

                @if(auth()->check() && auth()->user()->isPJ())
                <hr class="my-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('team.*') ? 'active' : '' }}" href="{{ route('team.index') }}">
                        <i class="fas fa-users me-2"></i>
                        Equipe
                    </a>
                </li>
                @endif

                <hr class="my-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('subscription.*') ? 'active' : '' }}" href="{{ route('subscription.show') }}">
                        <i class="fas fa-crown me-2"></i>
                        Plano
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                        <i class="fas fa-user me-2"></i>
                        Perfil
                    </a>
                </li>
            </ul>

            <div class="mt-auto p-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Sair
                    </button>
                </form>
            </div>
        </nav>
        @endauth

        <!-- Main Content -->
        <main class="main-content flex-grow-1 @guest no-sidebar @endguest">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4">
                <div class="container-fluid">
                    <button class="btn btn-outline-secondary d-md-none mobile-menu-toggle" type="button" style="display: none;">
                        <i class="fas fa-bars"></i>
                    </button>

                    @auth
                    <div class="navbar-nav ms-auto">
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-2"></i>
                                {{ auth()->user()->name }}
                                @if(auth()->user()->tenant && auth()->user()->tenant->plan !== 'free')
                                    <span class="badge bg-primary ms-1">{{ strtoupper(auth()->user()->tenant->plan) }}</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user me-2"></i>Perfil
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('subscription.show') }}">
                                    <i class="fas fa-crown me-2"></i>Plano
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Sair
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endauth
                </div>
            </nav>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Custom JS -->
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileToggle = document.querySelector('.mobile-menu-toggle');
            const sidebar = document.querySelector('.sidebar');
            
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
        });

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Format money inputs
        function formatMoney(input) {
            let value = input.value.replace(/\D/g, '');
            value = (parseInt(value) / 100).toFixed(2);
            value = value.replace('.', ',');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = 'R$ ' + value;
        }

        // Utility functions
        function showLoading(element) {
            element.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Carregando...';
            element.disabled = true;
        }

        function hideLoading(element, originalText) {
            element.innerHTML = originalText;
            element.disabled = false;
        }

        // CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
