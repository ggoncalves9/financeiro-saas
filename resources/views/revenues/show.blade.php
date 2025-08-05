@extends('layouts.uno-app')

@section('title', 'Detalhes da Receita')
@section('content')
<div class="container-fluid">
    <div class="row">
{{-- Removido extends duplicado --}}

@section('title', 'Detalhes da Receita')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Detalhes da Receita</h1>
                <div>
                    <a href="{{ route('revenues.edit', $revenue) }}" class="btn btn-primary me-2">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="{{ route('revenues.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar
                    </a>
                </div>
            </div>

            <div class="row">
<div class="container-fluid">
    <div class="row g-4">
                                        <label class="form-label fw-bold">Descrição:</label>
                                        <p class="form-control-plaintext">{{ $revenue->description }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Valor:</label>
                                        <p class="form-control-plaintext text-success fs-5 fw-bold">
                                            R$ {{ number_format($revenue->amount, 2, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Data de Vencimento:</label>
                                        <p class="form-control-plaintext">{{ $revenue->due_date ? $revenue->due_date->format('d/m/Y') : '-' }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Status:</label>
                                        <p class="form-control-plaintext">
                                            @switch($revenue->status)
                                                @case('pending')
                                                    <span class="badge bg-warning text-dark">Pendente</span>
                                                    @break
                                                @case('received')
                                                    <span class="badge bg-success">Recebida</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge bg-danger">Cancelada</span>
                                                    @break
                                            @endswitch
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Conta:</label>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Conta:</label>
                                        <p class="form-control-plaintext">{{ is_object($revenue->account) && isset($revenue->account->name) ? $revenue->account->name : '-' }}</p>
                                    </div>
                                </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Categoria:</label>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Categoria:</label>
                                        <p class="form-control-plaintext">{{ is_object($revenue->category) && isset($revenue->category->name) ? $revenue->category->name : '-' }}</p>
                                    </div>
                                </div>
                                    </div>
                                </div>
                            </div>

                            @if($revenue->client_name)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Cliente:</label>
                                        <p class="form-control-plaintext">{{ $revenue->client_name }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($revenue->received_date)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Data de Recebimento:</label>
                                        <p class="form-control-plaintext text-success">{{ $revenue->received_date->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($revenue->is_recurring)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Recorrência:</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge bg-info">
                                                {{ $revenue->recurring_type == 'monthly' ? 'Mensal' : 'Anual' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                @if($revenue->recurring_until)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Repetir até:</label>
                                        <p class="form-control-plaintext">{{ $revenue->recurring_until->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif

                            @if($revenue->notes)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Observações:</label>
                                        <p class="form-control-plaintext">{{ $revenue->notes }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($revenue->attachment_path)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Anexo:</label>
                                        <p class="form-control-plaintext">
                                            <a href="{{ Storage::url($revenue->attachment_path) }}" 
                                               target="_blank" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-download me-2"></i>
                                                {{ basename($revenue->attachment_path) }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Ações</h5>
                        </div>
                        <div class="card-body">
                            @if($revenue->status == 'pending')
                                <button type="button" class="btn btn-success w-100 mb-2" onclick="confirmReceive()">
                                    <i class="fas fa-check me-2"></i>Marcar como Recebida
                                </button>
                                
                                <button type="button" class="btn btn-warning w-100 mb-2" onclick="cancelRevenue()">
                                    <i class="fas fa-times me-2"></i>Cancelar Receita
                                </button>
                            @endif

                            <button type="button" class="btn btn-outline-primary w-100 mb-2" onclick="duplicateRevenue()">
                                <i class="fas fa-copy me-2"></i>Duplicar
                            </button>

                            <hr>

                            <h6>Informações do Sistema</h6>
                            <small class="text-muted">
                                <strong>Criado em:</strong> {{ $revenue->created_at->format('d/m/Y H:i') }}<br>
                                <strong>Atualizado em:</strong> {{ $revenue->updated_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    </div>

                    @if($revenue->status == 'pending')
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Alertas</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $daysUntilDue = now()->diffInDays($revenue->due_date, false);
                            @endphp
                            
                            @if($daysUntilDue < 0)
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Vencida há {{ abs($daysUntilDue) }} dias</strong>
                                </div>
                            @elseif($daysUntilDue <= 3)
                                <div class="alert alert-warning">
                                    <i class="fas fa-clock me-2"></i>
                                    <strong>Vence em {{ $daysUntilDue }} dias</strong>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-calendar me-2"></i>
                                    <strong>Vence em {{ $daysUntilDue }} dias</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
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

<form id="duplicateForm" action="{{ route('revenues.duplicate', $revenue) }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
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

function duplicateRevenue() {
    if (confirm('Deseja criar uma cópia desta receita?')) {
        document.getElementById('duplicateForm').submit();
    }
}
</script>
@endsection
