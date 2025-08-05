@extends('layouts.uno-app')

@section('title', 'Fluxo de Caixa')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-water me-2"></i> Fluxo de Caixa</h5>
        </div>
        <div class="card-body">
            <p>Relatório de entradas e saídas de caixa do período.</p>
            @if(!empty($cashFlow))
                <div class="table-responsive mt-4">
                    <table class="table table-bordered">
                        <thead class="table-info">
                            <tr>
                                <th>Mês</th>
                                <th>Receitas</th>
                                <th>Despesas</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cashFlow as $row)
                            <tr>
                                <td>{{ $row['month'] }}</td>
                                <td class="text-success">R$ {{ number_format($row['revenues'], 2, ',', '.') }}</td>
                                <td class="text-danger">R$ {{ number_format($row['expenses'], 2, ',', '.') }}</td>
                                <td class="fw-bold {{ $row['net'] >= 0 ? 'text-success' : 'text-danger' }}">R$ {{ number_format($row['net'], 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="mt-4">Nenhum dado de fluxo de caixa encontrado para o período.</p>
            @endif
        </div>
    </div>
</div>
@endsection
