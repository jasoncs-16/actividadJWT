<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Recogemos los datos del formulario (email y password)
        $credentials = $request->only('email', 'password');
        // 2. Intentamos autenticar. 'attempt' revisa la DB y, si es ok, genera el TOKEN
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'No autorizado. Datos incorrectos.'], 401);
        }
        // 3. Si todo va bien, devolvemos el token al cliente (Postman/Frontend)
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => Auth::guard('api')->user() // Opcional: enviamos losdatos del usuario
        ]);
    }

    public function me()
    {
        // Retorna los datos del usuario que "lleva" el token
        return response()->json(Auth::guard('api')->user());
    }
}