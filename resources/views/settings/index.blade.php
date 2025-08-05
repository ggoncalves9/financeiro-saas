@extends('layouts.uno-app')

@section('title', 'Configurações')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Configurações do Sistema</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('settings.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="timezone" class="form-label">Fuso Horário</label>
                            <select class="form-select" id="timezone" name="timezone">
                                <option value="America/Sao_Paulo">America/Sao_Paulo</option>
                                <option value="America/Fortaleza">America/Fortaleza</option>
                                <option value="America/Recife">America/Recife</option>
                                <!-- ...outras opções... -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="currency" class="form-label">Moeda Padrão</label>
                            <select class="form-select" id="currency" name="currency">
                                <option value="BRL">Real (BRL)</option>
                                <option value="USD">Dólar (USD)</option>
                                <option value="EUR">Euro (EUR)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="language" class="form-label">Idioma</label>
                            <select class="form-select" id="language" name="language">
                                <option value="pt-br">Português (Brasil)</option>
                                <option value="en">Inglês</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar Configurações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
