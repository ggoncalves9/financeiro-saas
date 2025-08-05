@extends('layouts.uno-app')

@section('title', 'Equipe')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Equipe</h1>
            <p class="text-muted mb-0">Gestão dos membros da equipe</p>
        </div>
        <div>
            <a href="{{ route('team.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Adicionar Membro
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card h-100">
                <div class="card-header border-0 bg-transparent">
                    <h5 class="mb-0">Membros da Equipe</h5>
                </div>
                <div class="card-body">
                    @if($teamMembers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Função</th>
                                        <th>Status</th>
                                        <th width="120">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teamMembers as $member)
                                    <tr>
                                        <td>{{ $member->member->name }}</td>
                                        <td>{{ $member->member->email }}</td>
                                        <td>{{ $member->role }}</td>
                                        <td>
                                            @if($member->is_active)
                                                <span class="badge bg-success">Ativo</span>
                                            @else
                                                <span class="badge bg-secondary">Inativo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('team.show', $member) }}" class="btn btn-sm btn-outline-primary" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('team.edit', $member) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('team.destroy', $member) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Remover">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhum membro cadastrado</h5>
                            <p class="text-muted">Adicione colaboradores para começar a gerenciar sua equipe.</p>
                            <a href="{{ route('team.create') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Adicionar Membro
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
