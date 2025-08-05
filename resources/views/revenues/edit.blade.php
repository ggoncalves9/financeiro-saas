@extends('layouts.uno-app')

@section('title', 'Editar Receita')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Editar Receita</h1>
                <div>
                    <a href="{{ route('revenues.show', $revenue) }}" class="btn btn-info me-2">
                        <i class="fas fa-eye me-2"></i>Visualizar
                    </a>
                    <a href="{{ route('revenues.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('revenues.update', $revenue) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Descrição <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('description') is-invalid @enderror" 
                                           id="description" 
                                           name="description" 
                                           value="{{ old('description', $revenue->description) }}" 
                                           required>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Valor <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" 
                                               class="form-control @error('amount') is-invalid @enderror" 
                                               id="amount" 
                                               name="amount" 
                                               value="{{ old('amount', $revenue->amount) }}" 
                                               step="0.01" 
                                               min="0" 
                                               required>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Data de Vencimento <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('due_date') is-invalid @enderror" 
                                           id="due_date" 
                                           name="due_date" 
                                           value="{{ old('due_date', $revenue->due_date ? $revenue->due_date->format('Y-m-d') : '') }}"
                                           required>
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="account_id" class="form-label">Conta <span class="text-danger">*</span></label>
                                    <select class="form-select @error('account_id') is-invalid @enderror" 
                                            id="account_id" 
                                            name="account_id" 
                                            required>
                                        <option value="">Selecione uma conta...</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" {{ (old('account_id', $revenue->account_id) == $account->id) ? 'selected' : '' }}>
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
                                    <label for="category_id" class="form-label">Categoria <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" 
                                            name="category_id" 
                                            required>
                                        <option value="">Selecione uma categoria...</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ (old('category_id', $revenue->category_id) == $category->id) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="client_name" class="form-label">Cliente</label>
                                    <input type="text" 
                                           class="form-control @error('client_name') is-invalid @enderror" 
                                           id="client_name" 
                                           name="client_name" 
                                           value="{{ old('client_name', $revenue->client_name) }}">
                                    @error('client_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" 
                                            name="status">
                                        <option value="pending" {{ old('status', $revenue->status) == 'pending' ? 'selected' : '' }}>Pendente</option>
                                        <option value="received" {{ old('status', $revenue->status) == 'received' ? 'selected' : '' }}>Recebida</option>
                                        <option value="cancelled" {{ old('status', $revenue->status) == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="received_date" class="form-label">Data de Recebimento</label>
                                    <input type="date" 
                                           class="form-control @error('received_date') is-invalid @enderror" 
                                           id="received_date" 
                                           name="received_date" 
                                           value="{{ old('received_date', $revenue->received_date ? $revenue->received_date->format('Y-m-d') : '') }}">
                                    @error('received_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_recurring" 
                                               name="is_recurring" 
                                               value="1" 
                                               {{ old('is_recurring', $revenue->is_recurring) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_recurring">
                                            Receita recorrente
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="recurring_options" style="display: {{ old('is_recurring', $revenue->is_recurring) ? 'block' : 'none' }};">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="recurring_type" class="form-label">Frequência</label>
                                        <select class="form-select" id="recurring_type" name="recurring_type">
                                            <option value="">Selecione...</option>
                                            <option value="monthly" {{ old('recurring_type', $revenue->recurring_type) == 'monthly' ? 'selected' : '' }}>Mensal</option>
                                            <option value="yearly" {{ old('recurring_type', $revenue->recurring_type) == 'yearly' ? 'selected' : '' }}>Anual</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="recurring_until" class="form-label">Repetir até</label>
                                        <input type="date" 
                                               class="form-control" 
                                               id="recurring_until" 
                                               name="recurring_until" 
                                               value="{{ old('recurring_until', $revenue->recurring_until ? $revenue->recurring_until->format('Y-m-d') : '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Observações</label>
                            <textarea class="form-control" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3">{{ old('notes', $revenue->notes) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="attachment" class="form-label">Anexo</label>
                            @if($revenue->attachment_path)
                                <div class="mb-2">
                                    <small class="text-muted">Arquivo atual: 
                                        <a href="{{ Storage::url($revenue->attachment_path) }}" target="_blank">
                                            {{ basename($revenue->attachment_path) }}
                                        </a>
                                    </small>
                                </div>
                            @endif
                            <input type="file" 
                                   class="form-control @error('attachment') is-invalid @enderror" 
                                   id="attachment" 
                                   name="attachment" 
                                   accept=".jpg,.jpeg,.png,.pdf">
                            <div class="form-text">Formatos aceitos: JPG, PNG, PDF (máx. 2MB)</div>
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <div>
                                @if($revenue->status == 'pending')
                                    <button type="button" class="btn btn-success me-2" onclick="confirmReceive()">
                                        <i class="fas fa-check me-2"></i>Marcar como Recebida
                                    </button>
                                    <button type="button" class="btn btn-warning me-2" onclick="cancelRevenue()">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </button>
                                @endif
                            </div>
                            
                            <div class="d-flex gap-2">
                                <a href="{{ route('revenues.index') }}" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Salvar Alterações
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Forms ocultos para ações -->
<form id="confirmForm" action="{{ route('revenues.confirm', $revenue) }}" method="POST" style="display: none;">
    @csrf
</form>

<form id="cancelForm" action="{{ route('revenues.cancel', $revenue) }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isRecurringCheckbox = document.getElementById('is_recurring');
    const recurringOptions = document.getElementById('recurring_options');
    
    isRecurringCheckbox.addEventListener('change', function() {
        if (this.checked) {
            recurringOptions.style.display = 'block';
        } else {
            recurringOptions.style.display = 'none';
        }
    });
});

function confirmReceive() {
    if (confirm('Confirmar o recebimento desta receita?')) {
        document.getElementById('confirmForm').submit();
    }
}

function cancelRevenue() {
    if (confirm('Tem certeza que deseja cancelar esta receita?')) {
        document.getElementById('cancelForm').submit();
    }
}
</script>
@endsection
