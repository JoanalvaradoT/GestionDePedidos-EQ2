<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidosController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::all();

        return response()->json(['data' => $pedidos], 200);
    }

    public function show($id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        return response()->json(['data' => $pedido], 200);
    }

    public function store(Request $request)
{
    $request->validate([
        'id_usuario' => 'required|exists:usuarios,id_usuario',
        'id_producto' => 'required|exists:productos,id_producto',
        'cantidad' => 'required|integer|min:1',
    ]);

    $producto = Producto::find($request->id_producto);

    if (!$producto || $producto->cantidad_disponible < $request->cantidad) {
        return response()->json(['message' => 'Producto no disponible o cantidad insuficiente'], 400);
    }
    $pedido = Pedido::create($request->all());

    $producto->update([
        'cantidad_disponible' => $producto->cantidad_disponible - $request->cantidad,
    ]);

    return response()->json($pedido, 201);
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'Producto' => 'string',
            'cantidad_producto' => 'string',
            'direccion' => 'string',
        ]);

        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        $request->validate([
            'id_usuario' => 'sometimes|exists:usuarios,id',
        ]);

        $pedido->update($request->all());

        return response()->json(['message' => 'Pedido actualizado con éxito', 'data' => $pedido], 200);
    }

    public function destroy($id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        $pedido->delete();

        return response()->json(['message' => 'Pedido eliminado con éxito'], 200);
    }
}
