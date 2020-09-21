<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    // MENGECEK UDAH LOGIN ATAU BELUM
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $category = Category::with(['parent'])->orderBy('created_at', 'DESC')->paginate(10);

        $parent = Category::getParent()->orderBy('name', 'ASC')->get();

        return view('admins.categories.index', compact('category', 'parent'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:50|unique:categories'
        ]);

        $request->request->add(['slug' => $request->name]);
    
        Category::create($request->except('_token'));
        
        return redirect(route('category.index'))->with(['success' => 'Kategori Baru Ditambahkan!']);
    }

    public function edit(Category $category)
    {
        $parent = Category::getParent()->orderBy('name', 'ASC')->get();
    
        return view('admins.categories.edit', compact('category', 'parent'));
    }

    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            'name' => 'required|string|max:50|unique:categories,name,' . $category->id
        ]);
        
        $category->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id
        ]);

        return redirect(route('category.index'))->with(['success' => 'Kategori Diperbaharui!']);
    }

    public function destroy(Category $category)
    {
        $category->withCount(['child', 'product']);
        
        if ($category->child_count == 0 && $category->product_count == 0) {
            $category->delete();
            return redirect(route('category.index'))->with(['success' => 'Kategori Dihapus!']);
        }
        
        return redirect(route('category.index'))->with(['error' => 'Kategori Ini Memiliki Anak Kategori!']);
    }
}
