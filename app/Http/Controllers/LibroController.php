<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use Exception;
use Carbon\Carbon;

class LibroController extends Controller
{
    public function buscarLibrosDelUsuario()
    {
        try {
            $libros = Libro::where('id_usuario', auth()->user()->id)->get();

            return response()->json($libros);
        } catch (Exception $e) {
            return response()->json(['error' => $e], 406, []);
        }
    }

    public function buscarLibroDelUsuario($id)
    {
        try {
            $libro = Libro::where([['id', $id], ['id_usuario', auth()->user()->id]])->first();

            return response()->json($libro);
        } catch (Exception $e) {
            return response()->json(['error' => $e], 406, []);
        }
    }

    public function guardarLibroDelUsuario(Request $request)
    {
        try {
            $libro = new Libro();

            $libro->titulo = $request->titulo;
            $libro->imagen = $request->imagen;
            $libro->id_usuario = auth()->user()->id;

            $libro->save();

            return response()->json($libro);
        } catch (Exception $e) {
            return response()->json(['error' => $e], 406, []);
        }
    }

    public function actualizarLibroDelUsuario(Request $request, $id)
    {
        try {
            $libro = Libro::where([['id', $id], ['id_usuario', auth()->user()->id]])->first();

            if ($libro) {
                if ($request->input('titulo')) {
                    $libro->titulo = $request->input('titulo');
                }

                if ($request->input('imagen')) {
                    $libro->imagen = $request->input('imagen');
                }

                $libro->save();

                return response()->json($libro);
            } else {
                return response()->json("No existe el libro");
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e], 406, []);
        }
    }

    public function eliminarLibroDelUsuario($id)
    {
        try {
            $libro = Libro::where([['id', $id], ['id_usuario', auth()->user()->id]])->first();

            if ($libro) {
                $libro->delete();

                return response()->json("Libro eliminado");
            } else {
                return response()->json("No existe el libro");
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e], 406, []);
        }
    }
}
