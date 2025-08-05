@extends('layouts.uno-app')

@section('title', 'Nova Conta')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Nova Conta</h1>
                <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('accounts.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nome da Conta <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Ex: Conta Corrente, Poupança, Carteira"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Tipo da Conta <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" 
                                            name="type" 
                                            required>
                                        <option value="">Selecione o tipo...</option>
                                        <option value="checking" {{ old('type') == 'checking' ? 'selected' : '' }}>Conta Corrente</option>
                                        <option value="savings" {{ old('type') == 'savings' ? 'selected' : '' }}>Poupança</option>
                                        <option value="investment" {{ old('type') == 'investment' ? 'selected' : '' }}>Investimento</option>
                                        <option value="credit_card" {{ old('type') == 'credit_card' ? 'selected' : '' }}>Cartão de Crédito</option>
                                        <option value="cash" {{ old('type') == 'cash' ? 'selected' : '' }}>Dinheiro</option>
                                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Outro</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="balance" class="form-label">Saldo Inicial</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" 
                                               class="form-control @error('balance') is-invalid @enderror" 
                                               id="balance" 
                                               name="balance" 
                                               value="{{ old('balance', '0.00') }}" 
                                               step="0.01">
                                        @error('balance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Informe o saldo atual da conta</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bank_name" class="form-label">Banco</label>
                                    <input type="text" 
                                           class="form-control @error('bank_name') is-invalid @enderror" 
                                           id="bank_name" 
                                           name="bank_name" 
                                           value="{{ old('bank_name') }}" 
                                           placeholder="Ex: Banco do Brasil, Itaú, Nubank">
                                    @error('bank_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="account_number" class="form-label">Número da Conta</label>
                                    <input type="text" 
                                           class="form-control @error('account_number') is-invalid @enderror" 
                                           id="account_number" 
                                           name="account_number" 
                                           value="{{ old('account_number') }}" 
                                           placeholder="Ex: 12345-6">
                                    @error('account_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="agency" class="form-label">Agência</label>
                                    <input type="text" 
                                           class="form-control @error('agency') is-invalid @enderror" 
                                           id="agency" 
                                           name="agency" 
                                           value="{{ old('agency') }}" 
                                           placeholder="Ex: 1234">
                                    @error('agency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="credit_card_fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="credit_limit" class="form-label">Limite do Cartão</label>
                                        <div class="input-group">
                                            <span class="input-group-text">R$</span>
                                            <input type="number" 
                                                   class="form-control @error('credit_limit') is-invalid @enderror" 
                                                   id="credit_limit" 
                                                   name="credit_limit" 
                                                   value="{{ old('credit_limit') }}" 
                                                   step="0.01" 
                                                   min="0">
                                            @error('credit_limit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="closing_day" class="form-label">Dia do Fechamento</label>
                                        <select class="form-select @error('closing_day') is-invalid @enderror" 
                                                id="closing_day" 
                                                name="closing_day">
                                            <option value="">Selecione...</option>
                                            @for($i = 1; $i <= 31; $i++)
                                                <option value="{{ $i }}" {{ old('closing_day') == $i ? 'selected' : '' }}>
                                                    Dia {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('closing_day')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="due_day" class="form-label">Dia do Vencimento</label>
                                        <select class="form-select @error('due_day') is-invalid @enderror" 
                                                id="due_day" 
                                                name="due_day">
                                            <option value="">Selecione...</option>
                                            @for($i = 1; $i <= 31; $i++)
                                                <option value="{{ $i }}" {{ old('due_day') == $i ? 'selected' : '' }}>
                                                    Dia {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('due_day')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Informações adicionais sobre a conta...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Conta ativa
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="include_in_total" 
                                           name="include_in_total" 
                                           value="1" 
                                           {{ old('include_in_total', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="include_in_total">
                                        Incluir no patrimônio total
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('accounts.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Criar Conta
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
    const typeSelect = document.getElementById('type');
    const creditCardFields = document.getElementById('credit_card_fields');
    
    typeSelect.addEventListener('change', function() {
        if (this.value === 'credit_card') {
            creditCardFields.style.display = 'block';
        } else {
            creditCardFields.style.display = 'none';
        }
    });
    
    // Verificar valor inicial
    if (typeSelect.value === 'credit_card') {
        creditCardFields.style.display = 'block';
    }
});
</script>
@endsection
