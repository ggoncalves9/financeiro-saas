@extends('layouts.uno-app')

@section('title', 'Progresso de Metas')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-bullseye me-2"></i> Progresso de Metas</h5>
        </div>
        <div class="card-body">
            <p>Relatório de acompanhamento do progresso das metas financeiras.</p>
            @if($goals->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Título</th>
                            <th>Valor Atual</th>
                            <th>Valor Alvo</th>
                            <th>Progresso (%)</th>
                            <th>Dias Restantes</th>
                            <th>Valor Mensal Necessário</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($goals as $goal)
                        <tr>
                            <td>{{ $goal->title }}</td>
                            <td>R$ {{ number_format($goal->current_amount, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($goal->target_amount, 2, ',', '.') }}</td>
                            <td>{{ number_format($goal->progress_percentage, 1) }}%</td>
                            <td>{{ $goal->days_remaining >= 0 ? $goal->days_remaining : 'Vencida' }}</td>
                            <td>R$ {{ number_format($goal->monthly_required, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="alert alert-warning">Nenhuma meta cadastrada.</div>
            @endif
        </div>
    </div>
</div>
@endsection
