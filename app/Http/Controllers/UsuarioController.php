<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    function registrarUsuario(Request $request)
    {
        $usuario = new Usuario();

        $usuario->nombre_usuario = $request->nombre_usuario;
        $usuario->clave = Hash::make($request->clave);
        $usuario->nombre_apellido = $request->nombre_apellido;
        $usuario->email = $request->email;
        $usuario->api_token = Str::random(60);

        try {
            $usuario->save();

            return response()->json($usuario, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e], 406, []);
        }
    }

    function iniciarSesion(Request $request)
    {
        try {
            $usuario = Usuario::where('nombre_usuario', $request->nombre_usuario)->first();

            if ($usuario && Hash::check($request->clave, $usuario->clave)) {
                return response()->json($usuario, 200);
            } else {
                return response()->json(['error' => 'Usuario no encontrado'], 406, []);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e], 406, []);
        }
    }

    public function perfil()
    {
        $usuario = Usuario::find(auth()->user()->id);

        return response()->json($usuario);
    }
}
