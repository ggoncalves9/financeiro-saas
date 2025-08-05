

<?php $__env->startSection('title', 'Gestão de Planos'); ?>
<?php $__env->startSection('page-title', 'Gestão de Planos'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Planos de Assinatura</h1>
    <a href="<?php echo e(route('admin.plans.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Novo Plano
    </a>
</div>

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

<div class="row">
    <?php $__empty_1 = true; $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100 <?php echo e(!$plan->is_active ? 'border-secondary' : ''); ?>">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?php echo e($plan->name); ?></h5>
                <div>
                    <?php if($plan->is_active): ?>
                        <span class="badge bg-success">Ativo</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Inativo</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <h2 class="text-primary"><?php echo e($plan->formatted_price); ?></h2>
                    <small class="text-muted"><?php echo e($plan->billing_cycle_label); ?></small>
                </div>

                <?php if($plan->description): ?>
                    <p class="text-muted"><?php echo e($plan->description); ?></p>
                <?php endif; ?>

                <div class="mb-3">
                    <strong>Limites:</strong>
                    <ul class="list-unstyled ms-3">
                        <?php if($plan->max_users): ?>
                            <li><i class="fas fa-users me-2"></i><?php echo e($plan->max_users); ?> usuários</li>
                        <?php else: ?>
                            <li><i class="fas fa-users me-2"></i>Usuários ilimitados</li>
                        <?php endif; ?>
                        
                        <?php if($plan->max_transactions): ?>
                            <li><i class="fas fa-exchange-alt me-2"></i><?php echo e(number_format($plan->max_transactions)); ?> transações/mês</li>
                        <?php else: ?>
                            <li><i class="fas fa-exchange-alt me-2"></i>Transações ilimitadas</li>
                        <?php endif; ?>

                        <?php if($plan->trial_days > 0): ?>
                            <li><i class="fas fa-clock me-2"></i><?php echo e($plan->trial_days); ?> dias de teste</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <?php if($plan->features && count($plan->features) > 0): ?>
                    <div class="mb-3">
                        <strong>Funcionalidades:</strong>
                        <ul class="list-unstyled ms-3">
                            <?php $__currentLoopData = $plan->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><i class="fas fa-check text-success me-2"></i><?php echo e($feature); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="text-muted small">
                    <strong><?php echo e($plan->users_count); ?></strong> usuário(s) usando este plano
                </div>
            </div>
            <div class="card-footer">
                <div class="btn-group w-100">
                    <a href="<?php echo e(route('admin.plans.edit', $plan)); ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    
                    <?php if($plan->is_active): ?>
                        <form action="<?php echo e(route('admin.plans.deactivate', $plan)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-pause"></i> Desativar
                            </button>
                        </form>
                    <?php else: ?>
                        <form action="<?php echo e(route('admin.plans.activate', $plan)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-play"></i> Ativar
                            </button>
                        </form>
                    <?php endif; ?>

                    <?php if($plan->users_count == 0): ?>
                        <form action="<?php echo e(route('admin.plans.destroy', $plan)); ?>" method="POST" class="d-inline"
                              onsubmit="return confirm('Tem certeza que deseja excluir este plano?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash"></i> Excluir
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12">
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>
            Nenhum plano cadastrado. <a href="<?php echo e(route('admin.plans.create')); ?>">Criar primeiro plano</a>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\financeiro_saas\resources\views/admin/plans/index.blade.php ENDPATH**/ ?>