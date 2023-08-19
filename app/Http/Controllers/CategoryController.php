<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return all the categories

        return Category::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate the received data
        $request->validate([
            'nome' => ['required', 'max:255'],
            'descricao' => ['required'],
        ]);

        //if the user is admin create the category
        if(auth()->user()->is_admin){
            $category = Category::create($request->all());
            return $category;
        }else{
            return response()->json(['error' => 'Somente administradores podem criar categorias'], 401);
        }

        return response()->json([
            'message' => 'Something went wrong',
            'errors' => $request->errors()
        ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //return the category selected
        return $category;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //validate the received data
        $request->validate([
            'nome' => ['max:255'],
        ]);

        //if the user is admin update the category

        
        if(auth()->user()->is_admin){
            $category = Category::where('id', $category->id)->update([
                'nome' => $request->nome ?? $category->nome,
                'descricao' => $request->descricao ?? $category->descricao,
            ]);

            if($category){
                return response()->json([
                    'message' => 'Categoria atualizada com sucesso',
                ], 200);
            }
        }else{
            return response()->json(['error' => 'Somente administradores podem criar categorias'], 401);
        }

        return response()->json([
            'message' => 'Something went wrong',
            'errors' => $request->errors()
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //if the user is admin delete the category

        if(auth()->user()->is_admin){
            $category->delete();
            return response()->json([
                'message' => 'Categoria deletada com sucesso',
            ], 200);
        }

        return response()->json([
            'message' => 'Somente administradores podem excluir categorias',
        ], 403);
    }
}
