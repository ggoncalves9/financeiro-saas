
<?php $__env->startSection('title', 'Despesas'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-center bg-success text-white">
                            <div class="card-body">
                                <h5>R$ <?php echo e($summary['paid_formatted']); ?></h5>
                                <p class="mb-0">Pagas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center bg-warning">
                            <div class="card-body">
                                <h5>R$ <?php echo e($summary['pending_formatted']); ?></h5>
                                <p class="mb-0">Pendentes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center bg-danger text-white">
                            <div class="card-body">
                                <h5>R$ <?php echo e($summary['overdue_formatted']); ?></h5>
                                <p class="mb-0">Vencidas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center bg-info text-white">
                            <div class="card-body">
                                <h5>R$ <?php echo e($summary['total_formatted']); ?></h5>
                                <p class="mb-0">Total</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expenses Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Lista de Despesas</h5>
                        <a href="<?php echo e(route('expenses.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Nova Despesa
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if($expenses->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Título</th>
                                            <th>Categoria</th>
                                            <th>Valor</th>
                                            <th>Data</th>
                                            <th>Vencimento</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($expense->title); ?></td>
                                            <td><?php echo e($expense->category); ?></td>
                                            <td class="text-danger fw-bold">R$ <?php echo e(number_format($expense->amount, 2, ',', '.')); ?></td>
                                            <td><?php echo e($expense->date->format('d/m/Y')); ?></td>
                                            <td><?php echo e($expense->due_date ? $expense->due_date->format('d/m/Y') : '-'); ?></td>
                                            <td>
                                                <?php if($expense->status === 'paid'): ?>
                                                    <span class="badge bg-success">Paga</span>
                                                <?php elseif($expense->status === 'pending'): ?>
                                                    <span class="badge bg-warning">Pendente</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Cancelada</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('expenses.show', $expense)); ?>" class="btn btn-sm btn-outline-primary" title="Ver">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('expenses.edit', $expense)); ?>" class="btn btn-sm btn-outline-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="<?php echo e(route('expenses.destroy', $expense)); ?>" method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir" onclick="return confirm('Confirma a exclusão?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                <?php echo e($expenses->links()); ?>

                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-arrow-down fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">Nenhuma despesa encontrada</h5>
                                <p class="text-muted">Registre suas despesas para manter o controle financeiro.</p>
                                <a href="<?php echo e(route('expenses.create')); ?>" class="btn btn-danger">
                                    <i class="fas fa-plus me-2"></i>Nova Despesa
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <!-- Resumo Mensal -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Resumo do Mês</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-12 mb-3">
                                <div class="border-bottom pb-2">
                                    <h5 class="text-primary"><?php echo e($summary['monthly_average_formatted']); ?></h5>
                                    <small class="text-muted">Média Mensal</small>
                                </div>
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
// Auto-submit form on filter changes
document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('#category, #status');
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.uno-app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ubuntu/financeiro-saas/resources/views/expenses/index.blade.php ENDPATH**/ ?>