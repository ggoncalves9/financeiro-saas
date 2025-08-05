

<?php $__env->startSection('title', 'Usuários'); ?>
<?php $__env->startSection('page-title', 'Gerenciamento de Usuários'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h4>Usuários Cadastrados</h4>
        <p class="text-muted">Gerencie todos os usuários do sistema</p>
    </div>
    <div class="col-md-6 text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filtersModal">
            <i class="fas fa-filter me-2"></i>Filtros
        </button>
    </div>
</div>

<!-- Filters Card -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.users.index')); ?>" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="<?php echo e(request('search')); ?>" placeholder="Nome, email ou empresa">
            </div>
            <div class="col-md-2">
                <label for="type" class="form-label">Tipo</label>
                <select class="form-select" id="type" name="type">
                    <option value="">Todos</option>
                    <option value="pf" <?php echo e(request('type') === 'pf' ? 'selected' : ''); ?>>Pessoa Física</option>
                    <option value="pj" <?php echo e(request('type') === 'pj' ? 'selected' : ''); ?>>Pessoa Jurídica</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Todos</option>
                    <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Ativo</option>
                    <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inativo</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="subscription_status" class="form-label">Assinatura</label>
                <select class="form-select" id="subscription_status" name="subscription_status">
                    <option value="">Todas</option>
                    <option value="active" <?php echo e(request('subscription_status') === 'active' ? 'selected' : ''); ?>>Ativa</option>
                    <option value="trialing" <?php echo e(request('subscription_status') === 'trialing' ? 'selected' : ''); ?>>Teste</option>
                    <option value="cancelled" <?php echo e(request('subscription_status') === 'cancelled' ? 'selected' : ''); ?>>Cancelada</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i>
                </button>
                <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header">
        <h6 class="m-0">Lista de Usuários (<?php echo e($users->total()); ?> encontrados)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Tipo</th>
                        <th>Status</th>
                        <th>Assinatura</th>
                        <th>Último Login</th>
                        <th>Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="text-white fw-bold"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-bold"><?php echo e($user->name); ?></div>
                                    <div class="text-muted small"><?php echo e($user->email); ?></div>
                                    <?php if($user->company_name): ?>
                                        <div class="text-muted small"><?php echo e($user->company_name); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo e($user->type === 'pj' ? 'primary' : 'secondary'); ?>">
                                <?php echo e(strtoupper($user->type)); ?>

                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-<?php echo e($user->is_active ? 'success' : 'danger'); ?> me-2">
                                    <?php echo e($user->is_active ? 'Ativo' : 'Inativo'); ?>

                                </span>
                                <?php if($user->is_admin): ?>
                                    <span class="badge bg-warning">Admin</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <?php if($user->subscription): ?>
                                <span class="badge bg-<?php echo e($user->subscription->status === 'active' ? 'success' : ($user->subscription->status === 'trialing' ? 'warning' : 'danger')); ?>">
                                    <?php echo e(ucfirst($user->subscription->status)); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-muted">Sem assinatura</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo e($user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca'); ?>

                        </td>
                        <td><?php echo e($user->created_at->format('d/m/Y')); ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(route('admin.users.show', $user)); ?>" class="btn btn-sm btn-outline-primary" title="Ver detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-<?php echo e($user->is_active ? 'danger' : 'success'); ?>" 
                                        onclick="toggleUserStatus(<?php echo e($user->id); ?>, <?php echo e($user->is_active ? 'false' : 'true'); ?>)"
                                        title="<?php echo e($user->is_active ? 'Desativar' : 'Ativar'); ?>">
                                    <i class="fas fa-<?php echo e($user->is_active ? 'user-times' : 'user-check'); ?>"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Nenhum usuário encontrado</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($users->hasPages()): ?>
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($users->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal para confirmar mudança de status -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Ação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="statusMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="statusForm" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="is_active" id="statusValue">
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleUserStatus(userId, newStatus) {
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    const form = document.getElementById('statusForm');
    const message = document.getElementById('statusMessage');
    const statusValue = document.getElementById('statusValue');
    
    form.action = `/admin/users/${userId}/status`;
    statusValue.value = newStatus;
    
    if (newStatus === 'true') {
        message.textContent = 'Tem certeza que deseja ATIVAR este usuário?';
    } else {
        message.textContent = 'Tem certeza que deseja DESATIVAR este usuário?';
    }
    
    modal.show();
}

// Auto-submit form on filter changes
document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('#type, #status, #subscription_status');
    
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\financeiro_saas\resources\views/admin/users/index.blade.php ENDPATH**/ ?>