<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\Category;
use App\Models\FinanceCategory;
use Illuminate\Http\Request;

class FinanceCategoryController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate the request of finance and category
        $request->validate([
            'finance_id' => 'required|integer|exists:finances,id',
            'category_id' => 'required|integer|exists:categories,id'
        ]);

        //get the finance and category
        $finance = Finance::findOrFail($request->finance_id);
        $category = Category::findOrFail($request->category_id);
        

        //check if the finance is already in the category
        $FinanceCategory = FinanceCategory::where('finance_id', $finance->id)->where('category_id', $category->id)->first();

        if($FinanceCategory){
            return response()->json([
                'message' => 'A finança já está associada a esta categoria'
            ], 422);
        }

        //attach the finance to the category
        FinanceCategory::create([
            'finance_id' => $finance->id,
            'category_id' => $category->id
        ]);

        //return the response
        return response()->json([
            'message' => 'A categoria ' . $category->nome . ' foi associada a finança ' . $finance->nome . ' com sucesso'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function removeCategory(Category $category, Finance $finance)
    {
        //check if the category and finance received is not empty
        if(!$category || !$finance){
            return response()->json([
                'message' => 'Preencha a categoria e a finança corretamente'
            ], 404);
        } 

        //validate if the category is associated with the finance
        $FinanceCategory = FinanceCategory::where('finance_id', $finance->id)->where('category_id', $category->id)->first();

        if(!$FinanceCategory){
            return response()->json([
                'message' => 'A finança não está associada a esta categoria'
            ], 422);
        }

        //remove the category from the finance
        $FinanceCategory->delete();

        //return the response
        return response()->json([
            'message' => 'A categoria ' . $category->nome . ' foi removida da finança ' . $finance->nome . ' com sucesso'
        ], 200);
    }
}
