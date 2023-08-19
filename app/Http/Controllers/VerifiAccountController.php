<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;

class VerifiAccountController extends Controller
{
    //
    public function store($pin)
    {
        //validate pin is not empty
        if (!$pin) {
            return response()->json([
                'message' => 'Pin não encontrado'
            ], 404);
        }

        //get user by pin
        $user = User::where('pin', $pin)->first();

        //validate user is not empty
        if (!$user) {
            return response()->json([
                'message' => 'Pin não encontrado'
            ], 404);
        }

        //validate user is verified
        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'Conta já verificada'
            ], 400);
        }
        
        //validate pin is not expired
        if (Carbon::parse($user->updated_at)->addMinutes(5)->isPast()) {
            $user->delete();
            return response()->json([
                'message' => 'Pin expirado'
            ], 400);
        }

        //update user email_verified_at
        $user->email_verified_at = Carbon::now();

        //save user
        $user->save();

        //return response
        return response()->json([
            'message' => 'Conta verificada com sucesso!'
        ], 200);
    }
}
