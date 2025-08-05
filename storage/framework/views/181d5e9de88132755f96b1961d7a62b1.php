

<?php $__env->startSection('title', 'Configurações de Pagamento'); ?>
<?php $__env->startSection('page-title', 'Configurações de Pagamento - EFI Pay'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-credit-card me-2"></i>
                    Configurações EFI Pay (Gerencianet)
                </h5>
                <small class="text-muted">Configure suas credenciais da EFI Pay para processar pagamentos PIX</small>
            </div>
            <div class="card-body">
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Guia de configuração -->
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Como configurar:</h6>
                    <ol class="mb-0 small">
                        <li>Acesse o painel da EFI Pay em <a href="https://gerencianet.com.br" target="_blank">gerencianet.com.br</a></li>
                        <li>Vá em <strong>Configurações > Aplicações</strong></li>
                        <li>Crie uma aplicação e copie o Client ID e Client Secret</li>
                        <li>Baixe o certificado digital (.p12) da aplicação</li>
                        <li>Configure sua chave PIX em <strong>PIX > Chaves</strong></li>
                    </ol>
                </div>

                <form action="<?php echo e(route('admin.payment-settings.update')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="efi_client_id" class="form-label">
                                    Client ID *
                                    <i class="fas fa-info-circle" data-bs-toggle="tooltip" 
                                       title="Identificador da aplicação fornecido pela EFI"></i>
                                </label>
                                <input type="text" class="form-control <?php $__errorArgs = ['efi_client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="efi_client_id" name="efi_client_id" 
                                       value="<?php echo e(old('efi_client_id', $settings['efi_client_id'])); ?>" required
                                       placeholder="Ex: Client_Id_abc123...">
                                <?php $__errorArgs = ['efi_client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="efi_client_secret" class="form-label">
                                    Client Secret *
                                    <i class="fas fa-info-circle" data-bs-toggle="tooltip" 
                                       title="Chave secreta da aplicação fornecida pela EFI"></i>
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control <?php $__errorArgs = ['efi_client_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="efi_client_secret" name="efi_client_secret" 
                                           value="<?php echo e(old('efi_client_secret')); ?>"
                                           placeholder="<?php echo e($settings['efi_client_secret'] ? 'Configurado (digite para alterar)' : 'Client Secret da EFI'); ?>">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('efi_client_secret')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <?php $__errorArgs = ['efi_client_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="efi_pix_key" class="form-label">
                                    Chave PIX *
                                    <i class="fas fa-info-circle" data-bs-toggle="tooltip" 
                                       title="Chave PIX cadastrada na EFI (CPF, CNPJ, email, celular ou EVP)"></i>
                                </label>
                                <input type="text" class="form-control <?php $__errorArgs = ['efi_pix_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="efi_pix_key" name="efi_pix_key" 
                                       value="<?php echo e(old('efi_pix_key', $settings['efi_pix_key'])); ?>" required
                                       placeholder="Ex: seu@email.com ou 11999999999">
                                <?php $__errorArgs = ['efi_pix_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="efi_webhook_url" class="form-label">
                                    URL do Webhook
                                    <i class="fas fa-info-circle" data-bs-toggle="tooltip" 
                                       title="URL que a EFI irá chamar para notificar pagamentos"></i>
                                </label>
                                <input type="url" class="form-control <?php $__errorArgs = ['efi_webhook_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="efi_webhook_url" name="efi_webhook_url" 
                                       value="<?php echo e(old('efi_webhook_url', $settings['efi_webhook_url'])); ?>" required
                                       readonly>
                                <?php $__errorArgs = ['efi_webhook_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <div class="form-text">Configure esta URL no painel da EFI Pay</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="efi_certificate" class="form-label">
                                    Certificado Digital (.p12)
                                    <i class="fas fa-info-circle" data-bs-toggle="tooltip" 
                                       title="Arquivo de certificado baixado do painel da EFI"></i>
                                </label>
                                <input type="file" class="form-control <?php $__errorArgs = ['efi_certificate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="efi_certificate" name="efi_certificate" 
                                       accept=".p12,.pfx">
                                <?php $__errorArgs = ['efi_certificate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <?php if($settings['efi_certificate_path']): ?>
                                    <div class="form-text text-success">
                                        <i class="fas fa-check me-1"></i>
                                        Certificado configurado: <?php echo e(basename($settings['efi_certificate_path'])); ?>

                                    </div>
                                <?php else: ?>
                                    <div class="form-text text-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Nenhum certificado configurado
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ambiente</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" 
                                           id="efi_sandbox" name="efi_sandbox" value="1"
                                           <?php echo e(old('efi_sandbox', $settings['efi_sandbox']) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="efi_sandbox">
                                        Modo Sandbox (Teste)
                                        <i class="fas fa-info-circle" data-bs-toggle="tooltip" 
                                           title="Ative para usar o ambiente de testes da EFI"></i>
                                    </label>
                                </div>
                                <div class="form-text">
                                    <strong>Sandbox:</strong> Para testes (não processa pagamentos reais)<br>
                                    <strong>Produção:</strong> Para pagamentos reais
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Salvar Configurações
                        </button>
                        <button type="button" class="btn btn-outline-info" id="testConnection">
                            <i class="fas fa-plug me-2"></i>Testar Conexão
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Status da Integração -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Status da Integração
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-key fa-2x <?php echo e($settings['efi_client_id'] ? 'text-success' : 'text-danger'); ?> mb-2"></i>
                            <h6>Credenciais</h6>
                            <span class="badge <?php echo e($settings['efi_client_id'] && $settings['efi_client_secret'] ? 'bg-success' : 'bg-danger'); ?>">
                                <?php echo e($settings['efi_client_id'] && $settings['efi_client_secret'] ? 'Configurado' : 'Pendente'); ?>

                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-certificate fa-2x <?php echo e($settings['efi_certificate_path'] ? 'text-success' : 'text-danger'); ?> mb-2"></i>
                            <h6>Certificado</h6>
                            <span class="badge <?php echo e($settings['efi_certificate_path'] ? 'bg-success' : 'bg-danger'); ?>">
                                <?php echo e($settings['efi_certificate_path'] ? 'Configurado' : 'Pendente'); ?>

                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-qrcode fa-2x <?php echo e($settings['efi_pix_key'] ? 'text-success' : 'text-danger'); ?> mb-2"></i>
                            <h6>Chave PIX</h6>
                            <span class="badge <?php echo e($settings['efi_pix_key'] ? 'bg-success' : 'bg-danger'); ?>">
                                <?php echo e($settings['efi_pix_key'] ? 'Configurado' : 'Pendente'); ?>

                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-cog fa-2x <?php echo e($settings['efi_sandbox'] ? 'text-warning' : 'text-success'); ?> mb-2"></i>
                            <h6>Ambiente</h6>
                            <span class="badge <?php echo e($settings['efi_sandbox'] ? 'bg-warning' : 'bg-success'); ?>">
                                <?php echo e($settings['efi_sandbox'] ? 'Sandbox' : 'Produção'); ?>

                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = field.nextElementSibling.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

document.getElementById('testConnection').addEventListener('click', function() {
    const button = this;
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Testando...';
    
    fetch('<?php echo e(route("admin.payment-settings.test")); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        showAlert('danger', 'Erro ao testar conexão: ' + error.message);
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
});

function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const container = document.querySelector('.card-body');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) alert.remove();
    }, 5000);
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\financeiro_saas\resources\views/admin/payment-settings/index.blade.php ENDPATH**/ ?>