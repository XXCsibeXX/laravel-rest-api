<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @api {post} /api/login Bejelentkezés
     * @apiName Login
     * @apiGroup Auth
     * @apiDescription Email és jelszó alapján tokent ad vissza. A korábbi tokenek érvénytelenné válnak.
     *
     * @apiBody {String} email A felhasználó email címe
     * @apiBody {String} password A felhasználó jelszava
     *
     * @apiSuccess {Object} user A bejelentkezett felhasználó
     * @apiSuccess {Number} user.id Azonosító
     * @apiSuccess {String} user.name Név
     * @apiSuccess {String} user.email Email cím
     * @apiSuccess {String} token Bearer token a védett végpontokhoz
     *
     * @apiError (401) InvalidCredentials Hibás email vagy jelszó
     * @apiError (422) ValidationError Hiányzó vagy hibás mező
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }

        $user->tokens()->delete();

        $token = $user->createToken('access')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }
}