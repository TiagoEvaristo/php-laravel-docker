<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePlanRequest;


class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //returna plans where status is true
        return response()->json([
            'plans' => Plan::where('status', true)->get(),
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //check if user is admin
        if(auth()->user()->is_admin){
            //validate request
            $request->validate([
                'descricao' => ['required','string'],
                'valor' => ['required','numeric'],
                'status' => ['required','boolean']
            ]);

            //if validations fails return response
            if(!$request){
                return response()->json([
                    'message' => "Something went wrong",
                ],400);
            }

            //create plan
            $plan = Plan::create([
                'descricao' => $request->descricao,
                'valor' => $request->valor,
                'status' => $request->status,
            ]);

            //if plan is created return response
            if($plan){
                return response()->json([
                    'message' => "Plano cadastrado com sucesso",
                    "plan" => $plan,
                ],200);
            }else{
                return response()->json([
                    'message' => "Something went wrong",
                ],400);
            }
        }

        return response()->json([
            'message' => "Somente usuários administradores podem acessar essa rota",
        ],403);
            
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {
        //return the selected plan
        return response()->json([
            'plan' => $plan,
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plan $plan)
    {
       //check if the user is admin
        if(!auth()->user()->is_admin){
            return response()->json([
                'message' => "Somente usuários administradores podem acessar essa rota",
            ],403);
        }

        //validate request
        $request->validate([
            'descricao' => ['string'],
            'valor' => ['numeric'],
            'status' => ['boolean']
        ]);

        //if validations fails return response
        if(!$request){
            return response()->json([
                'message' => "Something went wrong",
                'errors' => $request->errors(), 
            ],400);
        }

        //update plan
        $plan->update([
            'descricao' => isset($request->descricao) ? $request->descricao : $plan->descricao,
            'valor' => isset($request->valor) ? $request->valor : $plan->valor,
            'status' => isset($request->status) ? $request->status : $plan->status,
        ]);

        //if plan is updated return response
        if($plan){
            return response()->json([
                'message' => "Plano atualizado com sucesso",
            ],200);
        }else{
            return response()->json([
                'message' => "Something went wrong",
            ],400);
        }            
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        //check if user is admin
        if(!auth()->user()->is_admin){
            return response()->json([
                'message' => "Somente usuários administradores podem acessar essa rota",
            ],403);
        }

        //update plan status to false
        $plan->status = false;
        $plan->save();

        //if plan is updated return response
        if($plan){
            return response()->json([
                'message' => "Plano desativado com sucesso",
            ],200); 
        }else{
            return response()->json([
                'message' => "Something went wrong",
            ],400);
        }
        
    }
}
