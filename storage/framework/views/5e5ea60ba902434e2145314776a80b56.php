

<?php $__env->startSection('title', 'Planos'); ?>
<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2 class="mb-3"><i class="fas fa-rocket text-primary"></i> Escolha o Plano Ideal</h2>
            <p class="lead text-muted">Selecione o plano que melhor atende às suas necessidades</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100 <?php echo e($plan->name === 'Premium' ? 'border-primary shadow' : ''); ?>">
                <?php if($plan->name === 'Premium'): ?>
                <div class="badge bg-primary position-absolute top-0 start-50 translate-middle px-3 py-2">
                    <i class="fas fa-star me-1"></i> Mais Popular
                </div>
                <?php endif; ?>
                
                <div class="card-body text-center d-flex flex-column">
                    <h5 class="card-title mt-2"><?php echo e($plan->name); ?></h5>
                    <p class="text-muted small"><?php echo e($plan->description); ?></p>
                    
                    <div class="price-section my-4">
                        <?php if($plan->price == 0): ?>
                            <h3 class="text-success mb-0">Gratuito</h3>
                            <small class="text-muted">Para sempre</small>
                        <?php else: ?>
                            <h3 class="text-primary mb-0">
                                R$ <?php echo e(number_format($plan->price, 2, ',', '.')); ?>

                            </h3>
                            <small class="text-muted">
                                por <?php echo e($plan->billing_cycle === 'monthly' ? 'mês' : 'ano'); ?>

                            </small>
                        <?php endif; ?>
                    </div>

                    <ul class="list-unstyled text-start flex-grow-1">
                        <?php if($plan->features): ?>
                            <?php $__currentLoopData = $plan->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                <?php echo e($feature); ?>

                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </ul>

                    <div class="mt-auto">
                        <?php if($plan->trial_days > 0): ?>
                            <p class="small text-info mb-2">
                                <i class="fas fa-gift me-1"></i>
                                <?php echo e($plan->trial_days); ?> dias grátis
                            </p>
                        <?php endif; ?>

                        <?php if($plan->price == 0): ?>
                            <button class="btn btn-outline-success w-100" onclick="selectPlan(<?php echo e($plan->id); ?>)">
                                Começar Grátis
                            </button>
                        <?php else: ?>
                            <button class="btn <?php echo e($plan->name === 'Premium' ? 'btn-primary' : 'btn-outline-primary'); ?> w-100" 
                                    onclick="selectPlan(<?php echo e($plan->id); ?>)">
                                Assinar Agora
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Comparison Table -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h5 class="mb-0">Comparação Detalhada dos Planos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Recurso</th>
                                    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th class="text-center"><?php echo e($plan->name); ?></th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Preço</strong></td>
                                    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td class="text-center">
                                        <?php if($plan->price == 0): ?>
                                            <span class="text-success">Gratuito</span>
                                        <?php else: ?>
                                            <strong>R$ <?php echo e(number_format($plan->price, 2, ',', '.')); ?></strong>
                                            <br><small class="text-muted"><?php echo e($plan->billing_cycle === 'monthly' ? '/mês' : '/ano'); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                                <tr>
                                    <td>Transações</td>
                                    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td class="text-center">
                                        <?php if($plan->max_transactions): ?>
                                            <?php echo e($plan->max_transactions); ?> por mês
                                        <?php else: ?>
                                            <i class="fas fa-check text-success"></i> Ilimitadas
                                        <?php endif; ?>
                                    </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                                <tr>
                                    <td>Usuários</td>
                                    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td class="text-center">
                                        <?php if($plan->max_users): ?>
                                            <?php echo e($plan->max_users); ?> usuário<?php echo e($plan->max_users > 1 ? 's' : ''); ?>

                                        <?php else: ?>
                                            <i class="fas fa-check text-success"></i> Ilimitados
                                        <?php endif; ?>
                                    </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                                <tr>
                                    <td>Período de Teste</td>
                                    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td class="text-center">
                                        <?php if($plan->trial_days > 0): ?>
                                            <span class="text-info"><?php echo e($plan->trial_days); ?> dias</span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectPlan(planId) {
    <?php if(auth()->guard()->check()): ?>
        window.location.href = `/checkout/${planId}`;
    <?php else: ?>
        window.location.href = `/register?plan=${planId}`;
    <?php endif; ?>
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.uno-app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ubuntu/financeiro-saas/resources/views/plans/index.blade.php ENDPATH**/ ?>