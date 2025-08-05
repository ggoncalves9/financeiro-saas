
<?php $__env->startSection('title', 'Categorias'); ?>
<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h2 class="mb-4">Categorias de Receitas e Despesas</h2>
    
    <!-- Mensagens de sucesso/erro -->
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div><?php echo e($error); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Receitas -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Receitas</h4>
                </div>
                <div class="card-body">
                    <!-- Lista de categorias principais de receitas -->
                    <?php $__currentLoopData = $parentCategories->where('type', 'revenue'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded">
                            <strong><?php echo e($category->name); ?></strong>
                            <div>
                                <?php if(method_exists($category, 'children')): ?>
                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addSubcategoryModal<?php echo e($category->id); ?>">
                                    <i class="fas fa-plus"></i> Sub
                                </button>
                                <?php endif; ?>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo e($category->id); ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="<?php echo e(route('categories.destroy', $category)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Excluir categoria?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Subcategorias -->
                        <?php if(method_exists($category, 'children') && $category->children->count() > 0): ?>
                        <div class="mt-2">
                            <?php $__currentLoopData = $category->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex justify-content-between align-items-center ps-3 py-1">
                                <span class="text-muted">↳ <?php echo e($subcategory->name); ?></span>
                                <div>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo e($subcategory->id); ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="<?php echo e(route('categories.destroy', $subcategory)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir subcategoria?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Modal para adicionar subcategoria -->
                    <div class="modal fade" id="addSubcategoryModal<?php echo e($category->id); ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form class="modal-content" method="POST" action="<?php echo e(route('categories.store')); ?>">
                                <?php echo csrf_field(); ?>
                                <div class="modal-header">
                                    <h5 class="modal-title">Adicionar Subcategoria</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="type" value="revenue">
                                    <input type="hidden" name="parent_id" value="<?php echo e($category->id); ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Categoria Principal</label>
                                        <input type="text" class="form-control" value="<?php echo e($category->name); ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nome da Subcategoria</label>
                                        <input type="text" name="name" class="form-control" placeholder="Nome da subcategoria" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Adicionar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal para editar categoria/subcategoria -->
                    <div class="modal fade" id="editModal<?php echo e($category->id); ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form class="modal-content" method="POST" action="<?php echo e(route('categories.update', $category)); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Categoria</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="text" name="name" class="form-control" value="<?php echo e($category->name); ?>" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php $__currentLoopData = $category->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <!-- Modal para editar subcategoria -->
                    <div class="modal fade" id="editModal<?php echo e($subcategory->id); ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form class="modal-content" method="POST" action="<?php echo e(route('categories.update', $subcategory)); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Subcategoria</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Categoria Principal</label>
                                        <input type="text" class="form-control" value="<?php echo e($category->name); ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nome da Subcategoria</label>
                                        <input type="text" name="name" class="form-control" value="<?php echo e($subcategory->name); ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <!-- Formulário para adicionar nova categoria principal -->
                    <div class="mt-4 pt-3 border-top">
                        <form method="POST" action="<?php echo e(route('categories.store')); ?>" class="d-flex gap-2">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="type" value="revenue">
                            <input type="text" name="name" class="form-control" placeholder="Nova categoria de receita" required>
                            <button class="btn btn-success">Adicionar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Despesas -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Despesas</h4>
                </div>
                <div class="card-body">
                    <!-- Lista de categorias principais de despesas -->
                    <?php $__currentLoopData = $parentCategories->where('type', 'expense'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded">
                            <strong><?php echo e($category->name); ?></strong>
                            <div>
                                <?php if(method_exists($category, 'children')): ?>
                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addSubcategoryModalExp<?php echo e($category->id); ?>">
                                    <i class="fas fa-plus"></i> Sub
                                </button>
                                <?php endif; ?>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModalExp<?php echo e($category->id); ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="<?php echo e(route('categories.destroy', $category)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Excluir categoria?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Subcategorias -->
                        <?php if(method_exists($category, 'children') && $category->children->count() > 0): ?>
                        <div class="mt-2">
                            <?php $__currentLoopData = $category->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex justify-content-between align-items-center ps-3 py-1">
                                <span class="text-muted">↳ <?php echo e($subcategory->name); ?></span>
                                <div>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModalExpSub<?php echo e($subcategory->id); ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="<?php echo e(route('categories.destroy', $subcategory)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir subcategoria?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Modal para adicionar subcategoria -->
                    <div class="modal fade" id="addSubcategoryModalExp<?php echo e($category->id); ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form class="modal-content" method="POST" action="<?php echo e(route('categories.store')); ?>">
                                <?php echo csrf_field(); ?>
                                <div class="modal-header">
                                    <h5 class="modal-title">Adicionar Subcategoria</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="type" value="expense">
                                    <input type="hidden" name="parent_id" value="<?php echo e($category->id); ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Categoria Principal</label>
                                        <input type="text" class="form-control" value="<?php echo e($category->name); ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nome da Subcategoria</label>
                                        <input type="text" name="name" class="form-control" placeholder="Nome da subcategoria" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Adicionar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal para editar categoria -->
                    <div class="modal fade" id="editModalExp<?php echo e($category->id); ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form class="modal-content" method="POST" action="<?php echo e(route('categories.update', $category)); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Categoria</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="text" name="name" class="form-control" value="<?php echo e($category->name); ?>" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php $__currentLoopData = $category->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <!-- Modal para editar subcategoria -->
                    <div class="modal fade" id="editModalExpSub<?php echo e($subcategory->id); ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form class="modal-content" method="POST" action="<?php echo e(route('categories.update', $subcategory)); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Subcategoria</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Categoria Principal</label>
                                        <input type="text" class="form-control" value="<?php echo e($category->name); ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nome da Subcategoria</label>
                                        <input type="text" name="name" class="form-control" value="<?php echo e($subcategory->name); ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <!-- Formulário para adicionar nova categoria principal -->
                    <div class="mt-4 pt-3 border-top">
                        <form method="POST" action="<?php echo e(route('categories.store')); ?>" class="d-flex gap-2">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="type" value="expense">
                            <input type="text" name="name" class="form-control" placeholder="Nova categoria de despesa" required>
                            <button class="btn btn-success">Adicionar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.uno-app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/ubuntu/financeiro-saas/resources/views/categories/index.blade.php ENDPATH**/ ?>