<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    function registrarUsuario(Request $request)
    {
        try {
            $usuario = new Usuario();

            $usuario->nombre_usuario = $request->nombre_usuario;
            $usuario->clave = Hash::make($request->clave);
            $usuario->nombre_apellido = $request->nombre_apellido;
            $usuario->email = $request->email;
            $usuario->api_token = Str::random(60);

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
        } catch (Exception $e) {
            return response()->json(['error' => $e], 406, []);
        }
    }

    public function perfil()
    {
        try {
            $usuario = Usuario::find(auth()->user()->id);

            return response()->json($usuario);
        } catch (Exception $e) {
            return response()->json(['error' => $e], 406, []);
        }
    }
}
