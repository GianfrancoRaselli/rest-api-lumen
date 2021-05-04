<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use Carbon\Carbon;

class LibroController extends Controller
{
    public function buscarLibrosDelUsuario()
    {
        $libros = Libro::where('id_usuario', auth()->user()->id);

        return response()->json($libros);
    }

    public function buscarLibroDelUsuario($id)
    {
        $libro = Libro::where([['id', $id_libro], ['id_usuario', auth()->user()->id]])->first();

        return response()->json($libro);
    }

    public function guardarLibroDelUsuario(Request $request)
    {
        $libro = new Libro();

        $libro->titulo = $request->titulo;

        if ($request->hasFile('imagen')) {
            $nombreArchivoOriginal = $request->file('imagen')->getClientOriginalName();
            $nuevoNombre = Carbon::now()->timestamp . '_' . $nombreArchivoOriginal;

            $carpetaDestino = './upload/';
            $request->file('imagen')->move($carpetaDestino, $nuevoNombre);

            $libro->imagen = ltrim($carpetaDestino, '.') . $nuevoNombre;
        }

        $libro->id_usuario = auth()->user()->id;

        $libro->save();

        return response()->json($libro);
    }

    public function actualizarLibroDelUsuario(Request $request, $id)
    {
        $libro = Libro::where([['id', $id], ['id_usuario', auth()->user()->id]])->first();

        if ($libro) {
            if ($request->input('titulo')) {
                $libro->titulo = $request->input('titulo');
            }

            if ($request->hasFile('imagen')) {
                if ($libro->imagen) {
                    $rutaArchivo = base_path('public') . $libro->imagen;

                    if (file_exists($rutaArchivo)) {
                        unlink($rutaArchivo);
                    }
                }

                $nombreArchivoOriginal = $request->file('imagen')->getClientOriginalName();
                $nuevoNombre = Carbon::now()->timestamp . '_' . $nombreArchivoOriginal;

                $carpetaDestino = './upload/';
                $request->file('imagen')->move($carpetaDestino, $nuevoNombre);

                $libro->imagen = ltrim($carpetaDestino, '.') . $nuevoNombre;
            }

            $libro->save();

            return response()->json("Libro actualizado");
        } else {
            return response()->json("No existe el libro");
        }
    }

    public function eliminarLibroDelUsuario($id)
    {
        $libro = Libro::where([['id', $id], ['id_usuario', auth()->user()->id]])->first();

        if ($libro) {
            $rutaArchivo = base_path('public') . $libro->imagen;

            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }

            $libro->delete();

            return response()->json("Libro eliminado");
        } else {
            return response()->json("No existe el libro");
        }
    }
}
