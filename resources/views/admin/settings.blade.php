
@section('content')
@extends('admin.layout')
    <h1 class="mb-4">Configurações do Sistema</h1>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Nome do App</h5>
                    <p class="card-text">{{ $settings['app_name'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-secondary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Versão</h5>
                    <p class="card-text">{{ $settings['app_version'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-dark mb-3">
                <div class="card-body">
                    <h5 class="card-title">Ambiente</h5>
                    <p class="card-text">{{ $settings['app_env'] }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Debug</h5>
                    <p class="card-text">{{ $settings['app_debug'] ? 'Ativado' : 'Desativado' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Mail Driver</h5>
                    <p class="card-text">{{ $settings['mail_driver'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Cache Driver</h5>
                    <p class="card-text">{{ $settings['cache_driver'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-light mb-3">
                <div class="card-body">
                    <h5 class="card-title">Queue Driver</h5>
                    <p class="card-text">{{ $settings['queue_driver'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
