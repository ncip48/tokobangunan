<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ];

        $messages = [
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email tidak valid',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 8 karakter'
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            $response = $validator->messages();
            $response = [
                'validation' => true,
                'data' => [
                    'email' => $response->first('email'),
                    'password' => $response->first('password')
                ],
            ];
            return ResponseController::customResponse(false, $response, null, 403);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (password_verify($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->plainTextToken;
                $response = [
                    'token' => $token,
                ];
                return ResponseController::customResponse(true, 'Login berhasil', $response);
            } else {
                $response = [
                    'validation' => true,
                    'data' => [
                        'password' => 'Password salah'
                    ],
                ];
                return ResponseController::customResponse(false, $response, null, 403);
            }
        } else {
            $response = [
                'validation' => true,
                'data' => [
                    'email' => 'Email tidak terdaftar'
                ],
            ];
            return ResponseController::customResponse(false, $response, null, 403);
        }
    }

    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['token' => $token];
        return response($response, 200);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return ResponseController::customResponse(true, 'Logout berhasil', null);
    }
}
