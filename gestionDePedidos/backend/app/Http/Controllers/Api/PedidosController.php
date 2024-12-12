<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;

class PedidosController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with('usuario')->get();
        return response()->json($pedidos, 200);
    }

    public function show($id)
    {
        $pedido = Pedido::with('usuario')->find($id);
        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }
        return response()->json($pedido, 200);
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
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        $request->validate([
            'id_usuario' => 'sometimes|exists:usuarios,id_usuario',
        ]);

        $pedido->update($request->all());
        return response()->json($pedido, 200);
    }

    public function destroy($id)
    {
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        $pedido->delete();
        return response()->json(['message' => 'Pedido eliminado'], 200);
    }
}
