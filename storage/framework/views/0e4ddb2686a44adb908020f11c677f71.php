

<?php $__env->startSection('title', 'Receitas'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Receitas</h1>
            <p class="text-muted mb-0">Gerencie todas as suas receitas</p>
        </div>
        <div>
            <a href="<?php echo e(route('revenues.create')); ?>" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Nova Receita
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('revenues.index')); ?>" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="<?php echo e(request('search')); ?>" placeholder="Título ou descrição">
                </div>
                
                <div class="col-md-2">
                    <label for="category" class="form-label">Categoria</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">Todas</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(request('category') === $key ? 'selected' : ''); ?>>
                                <?php echo e($label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Todos</option>
                        <option value="received" <?php echo e(request('status') === 'received' ? 'selected' : ''); ?>>Recebida</option>
                        <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pendente</option>
                        <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>Cancelada</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="date_from" class="form-label">Data Inicial</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="<?php echo e(request('date_from')); ?>">
                </div>

                <div class="col-md-2">
                    <label for="date_to" class="form-label">Data Final</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="<?php echo e(request('date_to')); ?>">
                </div>

                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Recebido</h6>
                            <h4><?php echo e($summary['received_formatted']); ?></h4>
                        </div>
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">A Receber</h6>
                            <h4><?php echo e($summary['pending_formatted']); ?></h4>
                        </div>
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Geral</h6>
                            <h4><?php echo e($summary['total_formatted']); ?></h4>
                        </div>
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Média Mensal</h6>
                            <h4><?php echo e($summary['monthly_average_formatted']); ?></h4>
                        </div>
                        <i class="fas fa-calculator fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenues Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Receitas</h5>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="exportRevenues('csv')">
                    <i class="fas fa-file-csv me-1"></i>CSV
                </button>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="exportRevenues('excel')">
                    <i class="fas fa-file-excel me-1"></i>Excel
                </button>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="exportRevenues('pdf')">
                    <i class="fas fa-file-pdf me-1"></i>PDF
                </button>
            </div>
        </div>
        <div class="card-body">
            <?php if($revenues->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <a href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'title', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])); ?>" 
                                       class="text-decoration-none text-dark">
                                        Título
                                        <?php if(request('sort') === 'title'): ?>
                                            <i class="fas fa-sort-<?php echo e(request('direction') === 'asc' ? 'up' : 'down'); ?>"></i>
                                        <?php endif; ?>
                                    </a>
                                </th>
                                <th>Categoria</th>
                                <th>
                                    <a href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'amount', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])); ?>" 
                                       class="text-decoration-none text-dark">
                                        Valor
                                        <?php if(request('sort') === 'amount'): ?>
                                            <i class="fas fa-sort-<?php echo e(request('direction') === 'asc' ? 'up' : 'down'); ?>"></i>
                                        <?php endif; ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'date', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])); ?>" 
                                       class="text-decoration-none text-dark">
                                        Data
                                        <?php if(request('sort') === 'date'): ?>
                                            <i class="fas fa-sort-<?php echo e(request('direction') === 'asc' ? 'up' : 'down'); ?>"></i>
                                        <?php endif; ?>
                                    </a>
                                </th>
                                <th>Status</th>
                                <th>Recorrente</th>
                                <th width="120">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $revenues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $revenue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?php echo e($revenue->title); ?></div>
                                    <?php if($revenue->description): ?>
                                        <div class="text-muted small"><?php echo e(Str::limit($revenue->description, 50)); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-secondary"><?php echo e($revenue->category); ?></span>
                                    <?php if($revenue->is_business): ?>
                                        <span class="badge bg-info">Empresarial</span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold text-success"><?php echo e($revenue->formatted_amount); ?></td>
                                <td><?php echo e($revenue->date->format('d/m/Y')); ?></td>
                                <td>
                                    <?php if($revenue->status === 'received'): ?>
                                        <span class="badge bg-success">Recebida</span>
                                    <?php elseif($revenue->status === 'pending'): ?>
                                        <span class="badge bg-warning">Pendente</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Cancelada</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($revenue->recurring): ?>
                                        <span class="badge bg-primary"><?php echo e(ucfirst($revenue->recurring_type)); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('revenues.show', $revenue)); ?>" 
                                           class="btn btn-sm btn-outline-primary" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('revenues.edit', $revenue)); ?>" 
                                           class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteRevenue(<?php echo e($revenue->id); ?>)" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Mostrando <?php echo e($revenues->firstItem()); ?> a <?php echo e($revenues->lastItem()); ?> 
                        de <?php echo e($revenues->total()); ?> registros
                    </div>
                    <?php echo e($revenues->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-arrow-up fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhuma receita encontrada</h5>
                    <p class="text-muted">Crie sua primeira receita para começar a controlar suas finanças.</p>
                    <a href="<?php echo e(route('revenues.create')); ?>" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Nova Receita
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir esta receita?</p>
                <p class="text-muted small">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function deleteRevenue(id) {
    const form = document.getElementById('deleteForm');
    form.action = `<?php echo e(route('revenues.index')); ?>/${id}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function exportRevenues(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('export', format);
    
    window.location.href = `<?php echo e(route('revenues.index')); ?>?${params.toString()}`;
}

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

<?php echo $__env->make('layouts.uno-app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ubuntu/financeiro-saas/resources/views/revenues/index.blade.php ENDPATH**/ ?>