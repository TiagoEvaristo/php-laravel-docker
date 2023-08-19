<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return all Finances with their categories
        
        return Finance::where('user_id', Auth::user()->id)->with('finance_categories.category')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //create an item in the finances table

        
        //validade the request
        $request->validate([
            'tipo' => ['required', 'string', 'max:255', 'in:receita,despesa'],
            'nome' => ['required','string', 'max:255'],
            'descricao' => ['required','string', 'max:255'],
            'valor' => ['required', 'numeric'],
            'data' => ['required', 'date'],
            'status' => ['required', 'boolean'], //true para pago e false para não pago
        ]);

        //if validation fails return response with errors
        if (!$request) {
            return response()->json([
                'message' => 'Something went wrong',
                'errors' => $request->errors(),
            ], 400);
        }

        //create the item
        $finance = Finance::create([
            'tipo' => $request->tipo,
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'valor' => $request->valor,
            'data' => $request->data,
            'status' => $request->status,
            'user_id' => $request->user()->id,
        ]);

        //if finance is created, return a success message, if not return error message
        if (!$finance) {
            return response()->json([
                'message' => 'Something went wrong',
            ], 400);
        }

        //return a success message
        return response()->json([
            'message' => 'Item criado com sucesso',
            'finance' => $finance,
        ], 201);
        

        
    }

    /**
     * Display the specified resource.
     */
    public function show(Finance $finance)
    {
        //find the finance by id

        return $finance->load('finance_categories.category');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Finance $finance)
    {
        //update the finance
        
        if(auth()->user()->id != $finance->user_id){
            return response()->json([
                'message' => 'Você não tem permissão para atualizar este item',
            ], 403);
        }

        //validar request
        $atributos = $request->validate([
            'tipo' => ['string', 'max:255', 'in:receita,despesa'],
            'nome' => ['string', 'max:255'],
            'descricao' => ['string', 'max:255'],
            'valor' => ['numeric'],
            'data' => ['date'],
            'status' => ['boolean'], //true para pago e false para não pago
        ]);

        //if validation fails return response with errors
        if (!$request) {
            return response()->json([
                'message' => 'Erro ao atualizar o item',
                'errors' => $request->errors(),
            ], 400);
        }

        //update the finance
        $finance->update([
            'tipo' => isset($atributos['tipo']) ? $atributos['tipo'] : $finance->tipo,
            'nome' => isset($atributos['nome']) ? $atributos['nome'] : $finance->nome,
            'descricao' => isset($atributos['descricao']) ? $atributos['descricao'] : $finance->descricao,
            'valor' => isset($atributos['valor']) ? $atributos['valor'] : $finance->valor,
            'data' => isset($atributos['data']) ? $atributos['data'] : $finance->data,	
            'status' => isset($atributos['status']) ? $atributos['status'] : $finance->status,
        ]);

        //return response
        if ($finance){
            return response()->json([
                'message' => 'Item atualizado com sucesso',
                'finance' => $finance,
            ], 200);
        }

        //return if error
        return response()->json([
            'message' => 'Erro ao atualizar o item, tente novamente mais tarde',
        ], 400);
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Finance $finance)
    {
        //check if the user is the owner of the finance

        if(auth()->user()->id != $finance->user_id){
            return response()->json([
                'message' => 'Você não tem permissão para deletar este item',
            ], 403);
        }

        //delete the finance
        $finance->delete();

        //if finance is deleted return response
        if (!$finance) {
            return response()->json([
                'message' => 'Erro ao deletar o item',
            ], 400);
        }

        return response()->json([
            'message' => 'Item deletado com sucesso',
        ], 200);
    }
}
