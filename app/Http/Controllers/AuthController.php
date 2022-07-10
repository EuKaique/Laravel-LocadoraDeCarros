<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credenciais = $request->all(['email', 'password']);

        $token = auth('api')->attempt($credenciais);

        if($token){
            return response()->json(['token' => $token], 200);
        }else{
            return response()->json(['erro' => 'Usuário ou senha inválida'], 403);
        }

        $bearer = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTY1NzQ2MzgxOCwiZXhwIjoxNjU3NDY3NDE4LCJuYmYiOjE2NTc0NjM4MTgsImp0aSI6Ik95ODY4dXYya1lTT0x6VDQiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.gZcUc4MFgcz0FaomfobN2AeLuGsg6FboB_ENQiITy_I';
    }

    public function logout()
    {
        return 'Logout';
    }

    public function refresh()
    {
        return 'Refresh';
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
