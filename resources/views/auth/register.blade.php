@extends('layouts.guest')

@section('title', 'Cadastro')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-primary">Financeiro SaaS</h2>
                            <p class="text-muted">Crie sua conta</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf


                            <div class="mb-3">
                                <label class="form-label d-block mb-2">Tipo de usuário</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('type') is-invalid @enderror" type="radio" name="type" id="type_pf" value="pf" {{ old('type', 'pf') == 'pf' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="type_pf">Pessoa Física</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('type') is-invalid @enderror" type="radio" name="type" id="type_pj" value="pj" {{ old('type') == 'pj' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="type_pj">Pessoa Jurídica</label>
                                </div>
                                @error('type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="cpfField" style="display: none;">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text"
                                       class="form-control @error('cpf') is-invalid @enderror"
                                       id="cpf"
                                       name="cpf"
                                       value="{{ old('cpf') }}"
                                       maxlength="14"
                                       placeholder="000.000.000-00">
                                @error('cpf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="cnpjField" style="display: none;">
                                <label for="cnpj" class="form-label">CNPJ</label>
                                <input type="text"
                                       class="form-control @error('cnpj') is-invalid @enderror"
                                       id="cnpj"
                                       name="cnpj"
                                       value="{{ old('cnpj') }}"
                                       maxlength="18"
                                       placeholder="00.000.000/0000-00">
                                @error('cnpj')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="companyNameField" style="display: none;">
                                <label for="company_name" class="form-label">Nome da Empresa</label>
                                <input type="text"
                                       class="form-control @error('company_name') is-invalid @enderror"
                                       id="company_name"
                                       name="company_name"
                                       value="{{ old('company_name') }}"
                                       placeholder="Ex: Empresa LTDA">
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nome completo</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required 
                                       autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Senha</label>
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirmar senha</label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               required>
                                    </div>
                                </div>
                            </div>

                            <!-- Campo de tipo de usuário movido para o início e convertido em radio -->

                            <div class="mb-3 form-check">
                                <input type="checkbox" 
                                       class="form-check-input @error('terms') is-invalid @enderror" 
                                       id="terms" 
                                       name="terms" 
                                       required>
                                <label class="form-check-label" for="terms">
                                    Aceito os <a href="#" class="text-primary">termos de uso</a> e 
                                    <a href="#" class="text-primary">política de privacidade</a>
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Criar conta
                                </button>
                            </div>
                        </form>
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            function toggleFields() {
                                var pfRadio = document.getElementById('type_pf');
                                var pjRadio = document.getElementById('type_pj');
                                var cpfField = document.getElementById('cpfField');
                                var cnpjField = document.getElementById('cnpjField');
                                var companyNameField = document.getElementById('companyNameField');
                                
                                if (pfRadio.checked) {
                                    cpfField.style.display = 'block';
                                    cnpjField.style.display = 'none';
                                    companyNameField.style.display = 'none';
                                } else if (pjRadio.checked) {
                                    cpfField.style.display = 'none';
                                    cnpjField.style.display = 'block';
                                    companyNameField.style.display = 'block';
                                }
                            }
                            
                            document.getElementById('type_pf').addEventListener('change', toggleFields);
                            document.getElementById('type_pj').addEventListener('change', toggleFields);
                            toggleFields();

                            // Máscara de CPF
                            var cpfInput = document.getElementById('cpf');
                            if (cpfInput) {
                                cpfInput.addEventListener('input', function(e) {
                                    let v = cpfInput.value.replace(/\D/g, '');
                                    if (v.length > 11) v = v.slice(0, 11);
                                    let formatted = v;
                                    if (v.length > 9) formatted = v.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                                    else if (v.length > 6) formatted = v.replace(/(\d{3})(\d{3})(\d{1,3})/, '$1.$2.$3');
                                    else if (v.length > 3) formatted = v.replace(/(\d{3})(\d{1,3})/, '$1.$2');
                                    cpfInput.value = formatted;
                                });
                            }

                            // Máscara de CNPJ
                            var cnpjInput = document.getElementById('cnpj');
                            if (cnpjInput) {
                                cnpjInput.addEventListener('input', function(e) {
                                    let v = cnpjInput.value.replace(/\D/g, '');
                                    if (v.length > 14) v = v.slice(0, 14);
                                    let formatted = v;
                                    if (v.length > 12) formatted = v.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
                                    else if (v.length > 8) formatted = v.replace(/(\d{2})(\d{3})(\d{3})(\d{1,4})/, '$1.$2.$3/$4');
                                    else if (v.length > 5) formatted = v.replace(/(\d{2})(\d{3})(\d{1,3})/, '$1.$2.$3');
                                    else if (v.length > 2) formatted = v.replace(/(\d{2})(\d{1,3})/, '$1.$2');
                                    cnpjInput.value = formatted;
                                });
                            }
                        });
                        </script>

                        <div class="text-center mt-4">
                            <p class="mb-0">
                                Já tem uma conta? 
                                <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-bold">
                                    Entre aqui
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
