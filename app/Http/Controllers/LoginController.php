<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function handleLogin(Request $request)
    {
        $postData = $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $postData['email'])->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.wrong_password')],
            ]);
        }

        $token = $user->createToken('web_app')->plainTextToken;

        return response([
            'token' => $token,
            'user_name' => $user->name,
        ], Response::HTTP_OK);
    }
}
