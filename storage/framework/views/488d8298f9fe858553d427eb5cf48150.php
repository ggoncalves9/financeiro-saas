

<?php $__env->startSection('title', 'Gerenciar Assinaturas'); ?>
<?php $__env->startSection('page-title', 'Assinaturas'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0">Lista de Assinaturas</h6>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-arrow-left"></i> Voltar ao Dashboard
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuário</th>
                                <th>Plano</th>
                                <th>Status</th>
                                <th>Valor</th>
                                <th>Criado em</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($subscription->id); ?></td>
                                <td>
                                    <?php if($subscription->user): ?>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                    <span class="text-white fw-bold"><?php echo e(strtoupper(substr($subscription->user->name, 0, 1))); ?></span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-bold"><?php echo e($subscription->user->name); ?></div>
                                                <small class="text-muted"><?php echo e($subscription->user->email); ?></small>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">Usuário não encontrado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?php echo e($subscription->plan ?? $subscription->stripe_price ?? 'N/A'); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo e($subscription->status === 'active' ? 'success' : ($subscription->status === 'trialing' ? 'warning' : 'danger')); ?>">
                                        <?php echo e(ucfirst($subscription->status)); ?>

                                    </span>
                                </td>
                                <td>R$ <?php echo e(number_format($subscription->amount ?? 0, 2, ',', '.')); ?></td>
                                <td><?php echo e($subscription->created_at->format('d/m/Y H:i')); ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" title="Ver detalhes">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if($subscription->status !== 'active'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-success subscription-activate" 
                                                    data-id="<?php echo e($subscription->id); ?>" title="Ativar">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        <?php endif; ?>
                                        <?php if($subscription->status === 'active'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger subscription-cancel" 
                                                    data-id="<?php echo e($subscription->id); ?>" title="Cancelar">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>Nenhuma assinatura encontrada.</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    <?php echo e($subscriptions->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Subscription actions
document.querySelectorAll('.subscription-activate').forEach(button => {
    button.addEventListener('click', function() {
        const subscriptionId = this.dataset.id;
        
        if (confirm('Tem certeza que deseja ativar esta assinatura?')) {
            fetch(`/admin/subscriptions/${subscriptionId}/activate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erro ao ativar assinatura');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao ativar assinatura');
            });
        }
    });
});

document.querySelectorAll('.subscription-cancel').forEach(button => {
    button.addEventListener('click', function() {
        const subscriptionId = this.dataset.id;
        
        if (confirm('Tem certeza que deseja cancelar esta assinatura?')) {
            fetch(`/admin/subscriptions/${subscriptionId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erro ao cancelar assinatura');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao cancelar assinatura');
            });
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\financeiro_saas\resources\views/admin/subscriptions/index.blade.php ENDPATH**/ ?>