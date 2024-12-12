<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductosController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        return response()->json([
            'status' => 200,
            'message' => 'Productos listados correctamente',
            'productos' => $productos,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:255',
            'precio' => 'required|numeric',
            'descripcion' => 'required',
            'cantidad_disponible' => 'required|integer',
            'imagen' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400,
            ], 400);
        }

        $rutaImagen = $request->file('imagen') ? $request->file('imagen')->store('productos', 'public') : null;

        $producto = Producto::create([
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'descripcion' => $request->descripcion,
            'cantidad_disponible' => $request->cantidad_disponible,
            'imagen' => $rutaImagen,
        ]);

        return response()->json([
            'message' => 'Producto creado exitosamente',
            'producto' => $producto,
            'status' => 201,
        ], 201);
    }

    public function show($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'message' => 'Producto no encontrado',
                'status' => 404,
            ], 404);
        }

        return response()->json([
            'message' => 'Producto encontrado',
            'producto' => $producto->toArray() + ['imagen_url' => $producto->imagen_url],
            'status' => 200,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'message' => 'Producto no encontrado',
                'status' => 404,
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|max:255',
            'precio' => 'sometimes|required|numeric',
            'descripcion' => 'sometimes|required',
            'cantidad_disponible' => 'sometimes|required|integer',
            'imagen' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400,
            ], 400);
        }

        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('productos', 'public');
            $producto->imagen = $rutaImagen;
        }

        $producto->fill($request->except('imagen'));
        $producto->save();

        return response()->json([
            'message' => 'Producto actualizado exitosamente',
            'producto' => $producto->toArray() + ['imagen_url' => $producto->imagen_url],
            'status' => 200,
        ], 200);
    }

    public function destroy($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'message' => 'Producto no encontrado',
                'status' => 404,
            ], 404);
        }

        $producto->delete();

        return response()->json([
            'message' => 'Producto eliminado exitosamente',
            'status' => 200,
        ], 200);
    }
}
