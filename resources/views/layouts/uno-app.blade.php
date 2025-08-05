<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Financeiro SaaS')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #06b6d4;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --card-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --card-shadow-hover: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        [data-bs-theme="dark"] {
            --primary-color: #818cf8;
            --primary-dark: #6366f1;
            --secondary-color: #94a3b8;
            --light-color: #1e293b;
            --dark-color: #f8fafc;
            --sidebar-bg: #020617;
            --sidebar-hover: #0f172a;
        }

        body {
            background-color: var(--light-color);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #1e293b 100%);
            border-right: 1px solid rgba(148, 163, 184, 0.1);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.1);
        }

        .sidebar-brand h4 {
            color: white;
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .sidebar-nav .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 0.75rem 1.5rem;
            margin: 0.25rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .sidebar-nav .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: white;
            transform: translateX(4px);
        }

        .sidebar-nav .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-wrapper {
            margin-left: 280px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .main-header {
            background: white;
            border-bottom: 1px solid rgba(148, 163, 184, 0.1);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }

        [data-bs-theme="dark"] .main-header {
            background: #1e293b;
            border-bottom-color: rgba(148, 163, 184, 0.1);
        }

        .main-content {
            padding: 2rem;
        }

        /* Cards */
        .card {
            border: none;
            box-shadow: var(--card-shadow);
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            background: white;
        }

        [data-bs-theme="dark"] .card {
            background: #1e293b;
        }

        .card:hover {
            box-shadow: var(--card-shadow-hover);
            transform: translateY(-2px);
        }

        .stats-card {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
        }

        .stats-card.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .stats-card.danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .stats-card.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .stats-card.info {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
        }

        /* KPI Cards */
        .kpi-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(148, 163, 184, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--info-color));
        }

        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--card-shadow-hover);
        }

        [data-bs-theme="dark"] .kpi-card {
            background: #1e293b;
            border-color: rgba(148, 163, 184, 0.1);
        }

        .kpi-value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
        }

        .kpi-label {
            color: var(--secondary-color);
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .kpi-change {
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .kpi-change.positive {
            color: var(--success-color);
        }

        .kpi-change.negative {
            color: var(--danger-color);
        }

        /* Buttons */
        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(99, 102, 241, 0.3);
        }

        /* Theme Toggle */
        .theme-toggle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--light-color);
            border: 1px solid rgba(148, 163, 184, 0.2);
            color: var(--secondary-color);
            transition: all 0.2s ease;
        }

        .theme-toggle:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .quick-action-btn {
            aspect-ratio: 1;
            border-radius: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            text-decoration: none;
            color: white;
        }

        .quick-action-btn:hover {
            transform: scale(1.05);
            color: white;
        }

        .quick-action-btn.success {
            background: linear-gradient(135deg, var(--success-color), #059669);
        }

        .quick-action-btn.danger {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
        }

        .quick-action-btn.info {
            background: linear-gradient(135deg, var(--info-color), #0891b2);
        }

        .quick-action-btn.primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        }

        /* Chart Container */
        .chart-container {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(148, 163, 184, 0.1);
        }

        [data-bs-theme="dark"] .chart-container {
            background: #1e293b;
            border-color: rgba(148, 163, 184, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -280px;
            }

            .sidebar.show {
                margin-left: 0;
            }

            .main-wrapper {
                margin-left: 0;
            }

            .main-content {
                padding: 1rem;
            }

            .main-header {
                padding: 1rem;
            }

            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Money Display */
        .text-money-positive {
            color: var(--success-color);
            font-weight: 600;
        }

        .text-money-negative {
            color: var(--danger-color);
            font-weight: 600;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 0.75rem;
            border-left: 4px solid;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border-left-color: var(--success-color);
            color: var(--success-color);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border-left-color: var(--danger-color);
            color: var(--danger-color);
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            border-left-color: var(--warning-color);
            color: var(--warning-color);
        }

        /* Loading Animation */
        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Animation for page transitions */
        .page-content {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h4>
                <i class="fas fa-chart-line"></i>
                Financeiro Pro
            </h4>
            <div class="mt-3 mb-2">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-2"></i>
                        {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu">
                        <li><h6 class="dropdown-header">{{ Auth::user()->email }}</h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2"></i> Perfil</a></li>
                        <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="fas fa-cog me-2"></i> Configurações</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Sair
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="sidebar-nav">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('revenues.*') ? 'active' : '' }}" href="{{ route('revenues.index') }}">
                <i class="fas fa-arrow-trend-up"></i>
                Receitas
            </a>
            <a class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="{{ route('expenses.index') }}">
                <i class="fas fa-arrow-trend-down"></i>
                Despesas
            </a>
            <a class="nav-link {{ request()->routeIs('goals.*') ? 'active' : '' }}" href="{{ route('goals.index') }}">
                <i class="fas fa-bullseye"></i>
                Metas
            </a>
            <a class="nav-link {{ request()->routeIs('accounts.*') ? 'active' : '' }}" href="{{ route('accounts.index') }}">
                <i class="fas fa-university"></i>
                Contas
            </a>
            <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                <i class="fas fa-tags"></i>
                Categorias
            </a>
            
            @if(auth()->user()->type === 'pj' || auth()->user()->isPJ())
            <a class="nav-link {{ request()->routeIs('team.*') ? 'active' : '' }}" href="{{ route('team.index') }}">
                <i class="fas fa-users"></i>
                Equipes
            </a>
            @endif
            
            <a class="nav-link {{ request()->routeIs('plans.*') ? 'active' : '' }}" href="{{ route('plans.index') }}">
                <i class="fas fa-rocket"></i>
                Planos
            </a>

            <!-- Relatórios -->
            <div class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#reportsMenu" role="button" aria-expanded="false" aria-controls="reportsMenu">
                    <i class="fas fa-chart-pie"></i>
                    Relatórios
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
                <div class="collapse {{ request()->routeIs('reports.*') ? 'show' : '' }}" id="reportsMenu">
                    <a class="nav-link ps-4 {{ request()->routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                        <i class="fas fa-list"></i> Visão Geral
                    </a>
                    <a class="nav-link ps-4 {{ request()->routeIs('reports.income-statement') ? 'active' : '' }}" href="{{ route('reports.income-statement') }}">
                        <i class="fas fa-file-invoice-dollar"></i> Demonstrativo de Resultados
                    </a>
                    <a class="nav-link ps-4 {{ request()->routeIs('reports.cash-flow') ? 'active' : '' }}" href="{{ route('reports.cash-flow') }}">
                        <i class="fas fa-water"></i> Fluxo de Caixa
                    </a>
                    <a class="nav-link ps-4 {{ request()->routeIs('reports.category-analysis') ? 'active' : '' }}" href="{{ route('reports.category-analysis') }}">
                        <i class="fas fa-tags"></i> Análise por Categoria
                    </a>
                    <a class="nav-link ps-4 {{ request()->routeIs('reports.goal-progress') ? 'active' : '' }}" href="{{ route('reports.goal-progress') }}">
                        <i class="fas fa-bullseye"></i> Progresso de Metas
                    </a>
                </div>
            </div>
            @if(auth()->user()->hasRole('admin'))
            <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-crown"></i>
                Administração
            </a>
            @endif
            
            <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    Sair
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Header -->
        <header class="main-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-secondary d-md-none" type="button" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div>
                        <h1 class="h4 mb-0">@yield('title', 'Dashboard')</h1>
                        <small class="text-muted">Bem-vindo de volta, {{ Auth::user()->name }}!</small>
                    </div>
                </div>
                
                <div class="d-flex align-items-center gap-3">
                    <button class="theme-toggle" id="themeToggle" onclick="toggleTheme()">
                        <i class="fas fa-moon" id="themeIcon"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-content">
                <!-- Alerts -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Logout Form (Hidden) -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Theme Management
        function toggleTheme() {
            const body = document.body;
            const themeIcon = document.getElementById('themeIcon');
            const currentTheme = body.getAttribute('data-bs-theme');
            
            if (currentTheme === 'dark') {
                body.removeAttribute('data-bs-theme');
                themeIcon.className = 'fas fa-moon';
                localStorage.setItem('theme', 'light');
            } else {
                body.setAttribute('data-bs-theme', 'dark');
                themeIcon.className = 'fas fa-sun';
                localStorage.setItem('theme', 'dark');
            }
        }

        // Load saved theme
        function loadTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            const body = document.body;
            const themeIcon = document.getElementById('themeIcon');
            
            if (savedTheme === 'dark') {
                body.setAttribute('data-bs-theme', 'dark');
                themeIcon.className = 'fas fa-sun';
            } else {
                body.removeAttribute('data-bs-theme');
                themeIcon.className = 'fas fa-moon';
            }
        }

        // Sidebar Toggle for Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Auto-dismiss alerts
        function autoDismissAlerts() {
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert:not(.alert-persistent)');
                alerts.forEach(alert => {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                });
            }, 5000);
        }

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.querySelector('[onclick="toggleSidebar()"]');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !toggleButton.contains(e.target) && 
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });

        // Add loading state to forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<div class="loading-spinner me-2"></div>Carregando...';
                    submitBtn.disabled = true;
                    
                    // Re-enable after 10 seconds as fallback
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 10000);
                }
            });
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadTheme();
            autoDismissAlerts();
        });

        // CSRF Token for AJAX requests
        window.axios = {
            defaults: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }
        };
    </script>

    @stack('scripts')
</body>
</html>
