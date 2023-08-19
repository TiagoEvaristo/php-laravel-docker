<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;



class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email já verificado'
            ],200);
        }

        Mail::to($request->user()->email)->send(new VerifyEmail($request->user()->id));

        return response()->json([
            'message' => 'Email enviado com sucesso'
        ],200);
    }
}
