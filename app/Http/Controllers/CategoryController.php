<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        try {
            // Verificar se a coluna parent_id existe
            $hasParentId = Schema::hasColumn('categories', 'parent_id');
            
            if ($hasParentId) {
                // Usar nova estrutura com subcategorias
                $parentCategories = Category::where(function($q) use ($user) {
                    $q->whereNull('user_id')->orWhere('user_id', $user->id);
                })
                ->whereNull('parent_id')
                ->with('children')
                ->orderBy('type')
                ->orderBy('name')
                ->get();

                $allParentCategories = Category::where(function($q) use ($user) {
                    $q->whereNull('user_id')->orWhere('user_id', $user->id);
                })
                ->whereNull('parent_id')
                ->orderBy('type')
                ->orderBy('name')
                ->get();
            } else {
                // Usar estrutura antiga sem subcategorias
                $categories = Category::where(function($q) use ($user) {
                    $q->whereNull('user_id')->orWhere('user_id', $user->id);
                })->orderBy('type')->orderBy('name')->get();
                
                $parentCategories = $categories;
                $allParentCategories = $categories;
            }

            return view('categories.index', compact('parentCategories', 'allParentCategories'));
            
        } catch (\Exception $e) {
            // Fallback para estrutura antiga se houver qualquer erro
            $categories = Category::where(function($q) use ($user) {
                $q->whereNull('user_id')->orWhere('user_id', $user->id);
            })->orderBy('type')->orderBy('name')->get();
            
            $parentCategories = $categories;
            $allParentCategories = $categories;
            
            return view('categories.index', compact('parentCategories', 'allParentCategories'));
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:50',
            'type' => 'required|in:revenue,expense',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Se for uma subcategoria, verificar se a categoria pai é do mesmo tipo
        if ($request->parent_id) {
            $parentCategory = Category::find($request->parent_id);
            if ($parentCategory->type !== $request->type) {
                return back()->withErrors(['parent_id' => 'A subcategoria deve ter o mesmo tipo da categoria pai.']);
            }
        }

        Category::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoria criada com sucesso!');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);
        $category->update(['name' => $request->name]);
        return redirect()->route('categories.index')->with('success', 'Categoria atualizada!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Categoria excluída!');
    }
}
