<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'user_id',
        'type', // 'revenue' ou 'expense'
        'name',
        'parent_id',
    ];

    // Relacionamento com usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com categoria pai
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relacionamento com subcategorias
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Scope para categorias principais (sem pai)
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    // Scope para subcategorias
    public function scopeChildren($query)
    {
        return $query->whereNotNull('parent_id');
    }

    // Verificar se é uma categoria principal
    public function isParent()
    {
        return $this->parent_id === null;
    }

    // Verificar se é uma subcategoria
    public function isChild()
    {
        return $this->parent_id !== null;
    }

    // Obter nome completo da categoria (com pai se for subcategoria)
    public function getFullNameAttribute()
    {
        if ($this->isChild() && $this->parent) {
            return $this->parent->name . ' > ' . $this->name;
        }
        return $this->name;
    }
}
