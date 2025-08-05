@extends('layouts.uno-app')

@section('title', 'Nova Meta')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Nova Meta Financeira</h1>
                <a href="{{ route('goals.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('goals.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nome da Meta <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Ex: Reserva de Emergência, Viagem"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="target_amount" class="form-label">Valor da Meta <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" 
                                               class="form-control @error('target_amount') is-invalid @enderror" 
                                               id="target_amount" 
                                               name="target_amount" 
                                               value="{{ old('target_amount') }}" 
                                               step="0.01" 
                                               min="0.01" 
                                               required>
                                        @error('target_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="target_date" class="form-label">Data da Meta <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('target_date') is-invalid @enderror" 
                                           id="target_date" 
                                           name="target_date" 
                                           value="{{ old('target_date') }}" 
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           required>
                                    @error('target_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="account_id" class="form-label">Conta para Guardar o Dinheiro <span class="text-danger">*</span></label>
                                    <select class="form-select @error('account_id') is-invalid @enderror" 
                                            id="account_id" 
                                            name="account_id" 
                                            required>
                                        <option value="">Selecione uma conta...</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                                {{ $account->name }} ({{ $account->type }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('account_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Prioridade <span class="text-danger">*</span></label>
                                    <select class="form-select @error('priority') is-invalid @enderror" 
                                            id="priority" 
                                            name="priority" 
                                            required>
                                        <option value="">Selecione a prioridade...</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Alta</option>
                                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Média</option>
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Baixa</option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Descreva sua meta e o que ela representa para você...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-robot me-2 text-primary"></i>Contribuição Automática
                                </h6>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="auto_contribution" 
                                           name="auto_contribution" 
                                           value="1" 
                                           {{ old('auto_contribution') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auto_contribution">
                                        Ativar contribuição automática
                                    </label>
                                    <div class="form-text">
                                        O sistema irá sugerir valores automáticos para atingir sua meta.
                                    </div>
                                </div>

                                <div id="auto_contribution_options" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="contribution_amount" class="form-label">Valor da Contribuição</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">R$</span>
                                                    <input type="number" 
                                                           class="form-control" 
                                                           id="contribution_amount" 
                                                           name="contribution_amount" 
                                                           value="{{ old('contribution_amount') }}" 
                                                           step="0.01" 
                                                           min="0">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="contribution_frequency" class="form-label">Frequência</label>
                                                <select class="form-select" id="contribution_frequency" name="contribution_frequency">
                                                    <option value="">Selecione...</option>
                                                    <option value="weekly" {{ old('contribution_frequency') == 'weekly' ? 'selected' : '' }}>Semanal</option>
                                                    <option value="monthly" {{ old('contribution_frequency') == 'monthly' ? 'selected' : '' }}>Mensal</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('goals.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-bullseye me-2"></i>Criar Meta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const autoContributionCheckbox = document.getElementById('auto_contribution');
    const autoContributionOptions = document.getElementById('auto_contribution_options');
    const targetAmount = document.getElementById('target_amount');
    const targetDate = document.getElementById('target_date');
    const contributionAmount = document.getElementById('contribution_amount');
    const contributionFrequency = document.getElementById('contribution_frequency');
    
    autoContributionCheckbox.addEventListener('change', function() {
        if (this.checked) {
            autoContributionOptions.style.display = 'block';
            calculateSuggestedContribution();
        } else {
            autoContributionOptions.style.display = 'none';
        }
    });
    
    // Calcular sugestão automática
    function calculateSuggestedContribution() {
        const amount = parseFloat(targetAmount.value);
        const date = new Date(targetDate.value);
        const today = new Date();
        
        if (amount && date > today) {
            const diffInMonths = Math.ceil((date - today) / (1000 * 60 * 60 * 24 * 30));
            const monthlyContribution = amount / diffInMonths;
            
            contributionAmount.value = monthlyContribution.toFixed(2);
            contributionFrequency.value = 'monthly';
        }
    }
    
    targetAmount.addEventListener('change', calculateSuggestedContribution);
    targetDate.addEventListener('change', calculateSuggestedContribution);
    
    // Verificar se já estava marcado
    if (autoContributionCheckbox.checked) {
        autoContributionOptions.style.display = 'block';
    }
});
</script>
@endsection
