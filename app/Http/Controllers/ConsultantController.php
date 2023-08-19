<?php

namespace App\Http\Controllers;

use App\Models\Consultant;
use Illuminate\Http\Request;

class ConsultantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return all the consultants in the database
        if(!auth()->user()->is_admin){
            return response()->json([
                'message' => 'Você não tem permissão para acessar essa rota'
            ], 403);
        }
        
        return Consultant::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate the request, check if the consultant already exists in the database and then create it
        if(!auth()->user()->is_admin){
            return response()->json([
                'message' => 'Você não tem permissão para acessar essa rota'
            ], 403);
        }

        $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:consultants'],
            'contato_principal' => ['required', 'string', 'max:35', 'unique:consultants'],
            'especialidade' => ['required', 'string', 'max:255'],
        ]);

        //if validation fails return the errors
        if(!$request){
            return response()->json([
                'message' => 'Something went wrong',
                'errors' => $request->errors()
            ], 400);
        }

        //create the consultant
        $consultant = Consultant::create($request->all());
        
        return response()->json([
            'message' => 'Consultor cadastrado com sucesso',
            'consultant' => $consultant
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Consultant $consultant)
    {
        //return the given consultant in the request

        if(!auth()->user()->is_admin){
            return response()->json([
                'message' => 'Você não tem permissão para acessar essa rota'
            ], 403);
        }

        return $consultant;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consultant $consultant)
    {
        //validate the request, check if the consultant already exists in the database and then create it
        if(!auth()->user()->is_admin){
            return response()->json([
                'message' => 'Você não tem permissão para acessar essa rota'
            ], 403);
        }

        $request->validate([
            'nome' => ['string', 'max:255'],
            'email' => ['email', 'unique:consultants,email'],
            'contato_principal' => ['string', 'max:35'],
            'especialidade' => ['string', 'max:255'],
        ]);
        
        //if validation fails return the errors
        if(!$request){
            return response()->json([
                'message' => 'Something went wrong',
                'errors' => $request->errors()
            ], 400);
        }

        //create the consultant
        $consultant->update([
            'nome' => isset($request->nome) ? $request->nome :  $consultant->nome,
            'email' => isset($request->email) ? $request->email : $consultant->email,
            'contato_principal' => isset($request->contato_principal) ? $request->contato_principal :  $consultant->contato_principal,
            'especialidade' => isset($request->especialidade) ? $request->especialidade :  $consultant->especialidade,
        ]);
        
        if($consultant){
            return response()->json([
                'message' => 'Consultor atualizado com sucesso',
                'consultant' => $consultant
            ], 200);
        }
        
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consultant $consultant)
    {
        //if user authenticated is admin delete the consultant
        if(!auth()->user()->is_admin){
            return response()->json([
                'message' => 'Você não tem permissão para acessar essa rota'
            ], 403);
        }

        //delete the consultant
        $consultant->delete();
    }
}
