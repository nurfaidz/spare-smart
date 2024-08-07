<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->has('email')) {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        } else {
            $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);
        }

        $password = $request->get('password');

        $user = $request->has('email')
            ? \App\Models\User::where('email', $request->get('email'))->first()
            : \App\Models\User::where('username', $request->get('username'))->first();

        if ($user == null || !Hash::check($password, $user->password)) {
            return response()->apiError(422, 'Kredensial yang Anda masukkan salah.', [
                'email' => ['Kredensial yang Anda masukkan salah.'],
                'username' => ['Kredensial yang Anda masukkan salah.'],
            ]);
        }

        return response()->apiSuccess([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user,
        ]);
    }
}
