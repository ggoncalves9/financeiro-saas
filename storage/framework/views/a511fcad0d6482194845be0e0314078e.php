

<?php $__env->startSection('title', 'Contas'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Contas Bancárias</h1>
            <p class="text-muted mb-0">Gerencie suas contas e saldos</p>
        </div>
        <div>
            <a href="<?php echo e(route('accounts.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nova Conta
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total de Contas</h6>
                            <h4><?php echo e($stats['total_accounts']); ?></h4>
                        </div>
                        <i class="fas fa-university fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Saldo Total</h6>
                            <h4><?php echo e($stats['total_balance_formatted']); ?></h4>
                        </div>
                        <i class="fas fa-wallet fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Contas Correntes</h6>
                            <h4><?php echo e($stats['checking_accounts']); ?></h4>
                        </div>
                        <i class="fas fa-credit-card fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Poupanças</h6>
                            <h4><?php echo e($stats['savings_accounts']); ?></h4>
                        </div>
                        <i class="fas fa-piggy-bank fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Accounts Grid -->
    <?php if($accounts->count() > 0): ?>
        <div class="row">
            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-6 col-xl-4 mb-4">
                <div class="card account-card h-100 <?php echo e($account->is_active ? 'border-success' : 'border-secondary'); ?>">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <?php if($account->type === 'checking'): ?>
                                    <i class="fas fa-university fa-2x text-primary"></i>
                                <?php elseif($account->type === 'savings'): ?>
                                    <i class="fas fa-piggy-bank fa-2x text-success"></i>
                                <?php elseif($account->type === 'investment'): ?>
                                    <i class="fas fa-chart-line fa-2x text-warning"></i>
                                <?php elseif($account->type === 'credit_card'): ?>
                                    <i class="fas fa-credit-card fa-2x text-danger"></i>
                                <?php else: ?>
                                    <i class="fas fa-wallet fa-2x text-info"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold"><?php echo e($account->name); ?></h6>
                                <?php if($account->bank): ?>
                                    <small class="text-muted"><?php echo e($account->bank); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo e(route('accounts.show', $account)); ?>">
                                    <i class="fas fa-eye me-2"></i>Ver Extrato
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('accounts.edit', $account)); ?>">
                                    <i class="fas fa-edit me-2"></i>Editar
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="updateBalance(<?php echo e($account->id); ?>)">
                                    <i class="fas fa-sync me-2"></i>Atualizar Saldo
                                </a></li>
                                <?php if($account->is_active): ?>
                                    <li><a class="dropdown-item text-warning" href="#" onclick="deactivateAccount(<?php echo e($account->id); ?>)">
                                        <i class="fas fa-pause me-2"></i>Desativar
                                    </a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item text-success" href="#" onclick="activateAccount(<?php echo e($account->id); ?>)">
                                        <i class="fas fa-play me-2"></i>Ativar
                                    </a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteAccount(<?php echo e($account->id); ?>)">
                                    <i class="fas fa-trash me-2"></i>Excluir
                                </a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Account Balance -->
                        <div class="text-center mb-3">
                            <div class="small text-muted mb-1">Saldo Atual</div>
                            <h3 class="mb-0 <?php echo e($account->balance >= 0 ? 'text-success' : 'text-danger'); ?>">
                                <?php echo e($account->formatted_balance); ?>

                            </h3>
                            <small class="text-muted">
                                Atualizado em <?php echo e($account->updated_at->format('d/m/Y H:i')); ?>

                            </small>
                        </div>

                        <!-- Account Details -->
                        <div class="row text-center small">
                            <div class="col-6">
                                <div class="border-end">
                                    <div class="text-muted">Tipo</div>
                                    <div class="fw-bold">
                                        <?php if($account->type === 'checking'): ?>
                                            Conta Corrente
                                        <?php elseif($account->type === 'savings'): ?>
                                            Poupança
                                        <?php elseif($account->type === 'investment'): ?>
                                            Investimento
                                        <?php elseif($account->type === 'credit_card'): ?>
                                            Cartão de Crédito
                                        <?php else: ?>
                                            Outro
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted">Status</div>
                                <div class="fw-bold">
                                    <?php if($account->is_active): ?>
                                        <span class="text-success">Ativa</span>
                                    <?php else: ?>
                                        <span class="text-secondary">Inativa</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <?php if($account->account_number): ?>
                            <div class="mt-3 text-center">
                                <small class="text-muted">
                                    Conta: <?php echo e($account->masked_account_number); ?>

                                </small>
                            </div>
                        <?php endif; ?>

                        <!-- Recent Transactions Preview -->
                        <?php if($account->recentTransactions && $account->recentTransactions->count() > 0): ?>
                            <hr>
                            <div class="small">
                                <div class="text-muted mb-2 fw-bold">Últimas Movimentações</div>
                                <?php $__currentLoopData = $account->recentTransactions->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><?php echo e(Str::limit($transaction->description, 20)); ?></span>
                                        <span class="<?php echo e($transaction->amount >= 0 ? 'text-success' : 'text-danger'); ?>">
                                            <?php echo e($transaction->formatted_amount); ?>

                                        </span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <div class="text-center mt-2">
                                    <a href="<?php echo e(route('accounts.show', $account)); ?>" class="btn btn-sm btn-outline-primary">
                                        Ver Extrato Completo
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Quick Actions -->
                        <?php if($account->is_active): ?>
                            <hr>
                            <div class="d-grid gap-2">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-success" onclick="addTransaction(<?php echo e($account->id); ?>, 'deposit')">
                                        <i class="fas fa-plus me-1"></i>Depósito
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="addTransaction(<?php echo e($account->id); ?>, 'withdrawal')">
                                        <i class="fas fa-minus me-1"></i>Saque
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            <?php echo e($accounts->links()); ?>

        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-university fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Nenhuma conta cadastrada</h5>
                <p class="text-muted">Adicione suas contas bancárias para controlar seus saldos.</p>
                <a href="<?php echo e(route('accounts.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Adicionar Primeira Conta
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Update Balance Modal -->
<div class="modal fade" id="balanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Atualizar Saldo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="balanceForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="balance" class="form-label">Novo Saldo</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" class="form-control" id="balance" name="balance" 
                                   step="0.01" required>
                        </div>
                        <div class="form-text">Informe o saldo atual da conta conforme seu extrato bancário.</div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Observações (opcional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2" 
                                  placeholder="Ex: Atualização via extrato bancário"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Transaction Modal -->
<div class="modal fade" id="transactionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionModalTitle">Nova Transação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="transactionForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <input type="hidden" id="transaction_type" name="type">
                    
                    <div class="mb-3">
                        <label for="transaction_amount" class="form-label">Valor</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" class="form-control" id="transaction_amount" name="amount" 
                                   step="0.01" min="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="transaction_description" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="transaction_description" name="description" 
                               required placeholder="Ex: Depósito salário">
                    </div>
                    <div class="mb-3">
                        <label for="transaction_date" class="form-label">Data</label>
                        <input type="date" class="form-control" id="transaction_date" name="date" 
                               value="<?php echo e(date('Y-m-d')); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
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
                <p>Tem certeza que deseja excluir esta conta?</p>
                <p class="text-muted small">Esta ação não pode ser desfeita e todo o histórico será perdido.</p>
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
function updateBalance(accountId) {
    const form = document.getElementById('balanceForm');
    form.action = `/accounts/${accountId}/update-balance`;
    
    const modal = new bootstrap.Modal(document.getElementById('balanceModal'));
    modal.show();
}

function addTransaction(accountId, type) {
    const form = document.getElementById('transactionForm');
    const modal = new bootstrap.Modal(document.getElementById('transactionModal'));
    const title = document.getElementById('transactionModalTitle');
    
    form.action = `/accounts/${accountId}/transactions`;
    document.getElementById('transaction_type').value = type;
    
    if (type === 'deposit') {
        title.textContent = 'Novo Depósito';
    } else {
        title.textContent = 'Novo Saque';
    }
    
    // Clear form
    document.getElementById('transaction_amount').value = '';
    document.getElementById('transaction_description').value = '';
    document.getElementById('transaction_date').value = new Date().toISOString().split('T')[0];
    
    modal.show();
}

function activateAccount(accountId) {
    if (confirm('Tem certeza que deseja ativar esta conta?')) {
        fetch(`/accounts/${accountId}/activate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {
            location.reload();
        });
    }
}

function deactivateAccount(accountId) {
    if (confirm('Tem certeza que deseja desativar esta conta?')) {
        fetch(`/accounts/${accountId}/deactivate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {
            location.reload();
        });
    }
}

function deleteAccount(accountId) {
    const form = document.getElementById('deleteForm');
    form.action = `/accounts/${accountId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.uno-app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ubuntu/financeiro-saas/resources/views/accounts/index.blade.php ENDPATH**/ ?>