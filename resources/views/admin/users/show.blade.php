@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Detalhes do Usuário</h1>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ $user->name }}</h5>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Tipo:</strong> {{ strtoupper($user->type) }}</p>
            <p><strong>CPF:</strong> {{ $user->cpf ?? '-' }}</p>
            <p><strong>CNPJ:</strong> {{ $user->cnpj ?? '-' }}</p>
            <p><strong>Empresa:</strong> {{ $user->company_name ?? '-' }}</p>
            <p><strong>Telefone:</strong> {{ $user->phone ?? '-' }}</p>
            <p><strong>Endereço:</strong> {{ $user->address ?? '-' }}</p>
            <p><strong>Cidade:</strong> {{ $user->city ?? '-' }}</p>
            <p><strong>Estado:</strong> {{ $user->state ?? '-' }}</p>
            <p><strong>Ativo:</strong> {{ $user->is_active ? 'Sim' : 'Não' }}</p>
            <p><strong>Admin:</strong> {{ $user->is_admin ? 'Sim' : 'Não' }}</p>
            <p><strong>Criado em:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    <a href="/admin/users" class="btn btn-secondary">Voltar</a>
</div>
@endsection
