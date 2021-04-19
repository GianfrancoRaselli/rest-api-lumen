<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;

use Carbon\Carbon;

class LibroController extends Controller
{
    public function index() {
        $libros = Libro::all();

        return response()->json($libros);
    }

    public function ver($id) {
        $libro = Libro::find($id);

        return response()->json($libro);
    }

    public function guardar(Request $request) {
        $libro = new Libro();

        $libro->titulo = $request->titulo;

        if ($request->hasFile('imagen')) {
            $nombreArchivoOriginal = $request->file('imagen')->getClientOriginalName();
            $nuevoNombre = Carbon::now()->timestamp . '_' . $nombreArchivoOriginal;

            $carpetaDestino = './upload/';
            $request->file('imagen')->move($carpetaDestino, $nuevoNombre);

            $libro->imagen = ltrim($carpetaDestino, '.') . $nuevoNombre;
        }

        $libro->save();

        return response()->json($libro);
    }

    public function actualizar(Request $request, $id) {
        $libro = Libro::find($id);

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

    public function eliminar($id) {
        $libro = Libro::find($id);

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
