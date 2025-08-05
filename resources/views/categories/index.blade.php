@extends('layouts.uno-app')
@section('title', 'Categorias')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Categorias de Receitas e Despesas</h2>
    
    <!-- Mensagens de sucesso/erro -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="row">
        <!-- Receitas -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Receitas</h4>
                </div>
                <div class="card-body">
                    <!-- Lista de categorias principais de receitas -->
                    @foreach($parentCategories->where('type', 'revenue') as $category)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded">
                            <strong>{{ $category->name }}</strong>
                            <div>
                                @if(method_exists($category, 'children'))
                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addSubcategoryModal{{ $category->id }}">
                                    <i class="fas fa-plus"></i> Sub
                                </button>
                                @endif
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Excluir categoria?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Subcategorias -->
                        @if(method_exists($category, 'children') && $category->children->count() > 0)
                        <div class="mt-2">
                            @foreach($category->children as $subcategory)
                            <div class="d-flex justify-content-between align-items-center ps-3 py-1">
                                <span class="text-muted">↳ {{ $subcategory->name }}</span>
                                <div>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $subcategory->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('categories.destroy', $subcategory) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir subcategoria?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <!-- Modal para adicionar subcategoria -->
                    <div class="modal fade" id="addSubcategoryModal{{ $category->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form class="modal-content" method="POST" action="{{ route('categories.store') }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Adicionar Subcategoria</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="type" value="revenue">
                                    <input type="hidden" name="parent_id" value="{{ $category->id }}">
                                    <div class="mb-3">
                                        <label class="form-label">Categoria Principal</label>
                                        <input type="text" class="form-control" value="{{ $category->name }}" readonly>
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
                    <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form class="modal-content" method="POST" action="{{ route('categories.update', $category) }}">
                                @csrf @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Categoria</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @foreach($category->children as $subcategory)
                    <!-- Modal para editar subcategoria -->
                    <div class="modal fade" id="editModal{{ $subcategory->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form class="modal-content" method="POST" action="{{ route('categories.update', $subcategory) }}">
                                @csrf @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Subcategoria</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Categoria Principal</label>
                                        <input type="text" class="form-control" value="{{ $category->name }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nome da Subcategoria</label>
                                        <input type="text" name="name" class="form-control" value="{{ $subcategory->name }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                    @endforeach

                    <!-- Formulário para adicionar nova categoria principal -->
                    <div class="mt-4 pt-3 border-top">
                        <form method="POST" action="{{ route('categories.store') }}" class="d-flex gap-2">
                            @csrf
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
                    @foreach($parentCategories->where('type', 'expense') as $category)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded">
                            <strong>{{ $category->name }}</strong>
                            <div>
                                @if(method_exists($category, 'children'))
                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addSubcategoryModalExp{{ $category->id }}">
                                    <i class="fas fa-plus"></i> Sub
                                </button>
                                @endif
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModalExp{{ $category->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Excluir categoria?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Subcategorias -->
                        @if(method_exists($category, 'children') && $category->children->count() > 0)
                        <div class="mt-2">
                            @foreach($category->children as $subcategory)
                            <div class="d-flex justify-content-between align-items-center ps-3 py-1">
                                <span class="text-muted">↳ {{ $subcategory->name }}</span>
                                <div>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModalExpSub{{ $subcategory->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('categories.destroy', $subcategory) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir subcategoria?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <!-- Modal para adicionar subcategoria -->
                    <div class="modal fade" id="addSubcategoryModalExp{{ $category->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form class="modal-content" method="POST" action="{{ route('categories.store') }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Adicionar Subcategoria</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="type" value="expense">
                                    <input type="hidden" name="parent_id" value="{{ $category->id }}">
                                    <div class="mb-3">
                                        <label class="form-label">Categoria Principal</label>
                                        <input type="text" class="form-control" value="{{ $category->name }}" readonly>
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
                    <div class="modal fade" id="editModalExp{{ $category->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form class="modal-content" method="POST" action="{{ route('categories.update', $category) }}">
                                @csrf @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Categoria</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @foreach($category->children as $subcategory)
                    <!-- Modal para editar subcategoria -->
                    <div class="modal fade" id="editModalExpSub{{ $subcategory->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form class="modal-content" method="POST" action="{{ route('categories.update', $subcategory) }}">
                                @csrf @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Subcategoria</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Categoria Principal</label>
                                        <input type="text" class="form-control" value="{{ $category->name }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nome da Subcategoria</label>
                                        <input type="text" name="name" class="form-control" value="{{ $subcategory->name }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                    @endforeach

                    <!-- Formulário para adicionar nova categoria principal -->
                    <div class="mt-4 pt-3 border-top">
                        <form method="POST" action="{{ route('categories.store') }}" class="d-flex gap-2">
                            @csrf
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
@endsection
