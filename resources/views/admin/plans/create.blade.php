@extends('admin.layout')

@section('title', 'Criar Plano')
@section('page-title', 'Criar Novo Plano')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Criar Novo Plano</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.plans.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome do Plano *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Preço *</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price') }}" 
                                           step="0.01" min="0" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="billing_cycle" class="form-label">Ciclo de Cobrança *</label>
                                <select class="form-select @error('billing_cycle') is-invalid @enderror" 
                                        id="billing_cycle" name="billing_cycle" required>
                                    <option value="monthly" {{ old('billing_cycle') == 'monthly' ? 'selected' : '' }}>Mensal</option>
                                    <option value="yearly" {{ old('billing_cycle') == 'yearly' ? 'selected' : '' }}>Anual</option>
                                </select>
                                @error('billing_cycle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="trial_days" class="form-label">Dias de Teste</label>
                                <input type="number" class="form-control @error('trial_days') is-invalid @enderror" 
                                       id="trial_days" name="trial_days" value="{{ old('trial_days', 0) }}" 
                                       min="0" max="365">
                                @error('trial_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_users" class="form-label">Máximo de Usuários</label>
                                <input type="number" class="form-control @error('max_users') is-invalid @enderror" 
                                       id="max_users" name="max_users" value="{{ old('max_users') }}" 
                                       min="1" placeholder="Deixe vazio para ilimitado">
                                @error('max_users')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_transactions" class="form-label">Máximo de Transações/Mês</label>
                                <input type="number" class="form-control @error('max_transactions') is-invalid @enderror" 
                                       id="max_transactions" name="max_transactions" value="{{ old('max_transactions') }}" 
                                       min="1" placeholder="Deixe vazio para ilimitado">
                                @error('max_transactions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Funcionalidades Incluídas</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="dashboard" id="feature_dashboard">
                                    <label class="form-check-label" for="feature_dashboard">Dashboard Completo</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="reports" id="feature_reports">
                                    <label class="form-check-label" for="feature_reports">Relatórios Avançados</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="goals" id="feature_goals">
                                    <label class="form-check-label" for="feature_goals">Metas Financeiras</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="categories" id="feature_categories">
                                    <label class="form-check-label" for="feature_categories">Categorias Personalizadas</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="api_access" id="feature_api">
                                    <label class="form-check-label" for="feature_api">Acesso à API</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="export" id="feature_export">
                                    <label class="form-check-label" for="feature_export">Exportação de Dados</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="support" id="feature_support">
                                    <label class="form-check-label" for="feature_support">Suporte Prioritário</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="team_management" id="feature_team">
                                    <label class="form-check-label" for="feature_team">Gestão de Equipe</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sort_order" class="form-label">Ordem de Exibição</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                       id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" 
                                       min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                                           id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Plano Ativo
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Criar Plano
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
