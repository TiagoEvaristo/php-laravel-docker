<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);

        $userAttempt = auth()->attempt($attributes);

        if (!$userAttempt){
            return response()->json([
                'Unauthorized' => 'Seu e-mail, cpf ou senha estão incorretos'
            ], 401);
        }
        
        $token = $request->user()->createToken('auth_token')->plainTextToken;

        return response()->json([
            'Authenticated' => 'Usuário autenticado com sucesso',
            'user' => auth()->user(),
            'token' => $token
        ],200);     
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'Logout' => 'Usuário deslogado com sucesso'
        ],200);
    }

    public function show(Request $request)
    {

        return response()->json([
            'Authenticated' => 'Usuário autenticado com sucesso',
            'User' => auth()->user(),
        ],200);

    }
}
