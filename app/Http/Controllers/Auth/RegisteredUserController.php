<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use App\Mail\VerifyEmail;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {   

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'cpf' => ['required', 'max:11', 'unique:users']
        ]);

        //if validation fails return response
        if (!$request) {
            return response()->json([
                'message' => 'Erro ao criar usuário',
                'errors' => $request->errors()
            ], 400);
        }

        $pin = rand(100000, 999999);
        Mail::to($request->email)->send(new VerifyEmail($pin));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'password' => Hash::make($request->password),
            'pin' => $pin,
        ]);

        event(new Registered($user));

        Auth::login($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuário ' . $user->name . ' criado com sucesso! Verifique seu email para ativar sua conta.',
            'token' => $token
        ],201);   
    }

    public function resendVerifyEmail(){
        $pin = rand(100000, 999999);
        Mail::to(Auth::user()->email)->send(new VerifyEmail($pin));

        //update user pin
        User::find(Auth::user()->id)->update([
            'pin' => $pin,
        ]);

        //return response
        return response()->json([
            'message' => 'Email de verificação reenviado com sucesso!'
        ], 200);
    }

    public function update(Request $request){
        try {
            //code...
            $user = auth()->user();

            $request->validate([
                'name' => ['string', 'max:255'],
                'email' => ['string', 'email', 'max:255', 'unique:'.User::class],
                'cpf' => ['size:11', 'unique:users'], 
                'perfil_investidor' => ['string', 'max:255', 'in:Moderado,moderado,Conservador,conservador,Agressivo,agressivo'],
            ]);

            User::find($user->id)->update([
                'name' => isset($request->name) ? $request->name : $user->name,
                'email' => isset($request->email) ? $request->email : $user->email,
                'cpf' => isset($request->cpf) ? $request->cpf : $user->cpf,
                'perfil_investidor' => isset($request->perfil_investidor) ? $request->perfil_investidor : $user->perfil_investidor,
                'plan_id' => isset($request->plan_id) ? $request->plan_id : $user->plan_id,
            ]);

            return response()->json([
                'message' => 'Usuário ' . $user->name . ' atualizado com sucesso!',
            ],200);

        } catch (\Exception $e) {
            //throw $th;
            return response()->json([
                'mesages' => $e->getMessage(),
            ],400);
        }
        
        
    }

    public function applyPlan(Request $request){
        try {
            //code...
            $user = auth()->user();

            $request->validate([
                'plan_id' => ['integer', 'exists:plans,id'],
            ]);

            User::find($user->id)->update([
                'plano_ativo' => isset($request->plan_id) ? true : false,
                'plan_id' => isset($request->plan_id) ? $request->plan_id : null,
            ]);

            return response()->json([
                'message' => 'Plano aplicado com sucesso!',
            ],200);

        } catch (\Exception $e) {
            //throw $th;
            return response()->json([
                'mesages' => $e->getMessage(),
            ],400);
        }
        
        
    }
}
