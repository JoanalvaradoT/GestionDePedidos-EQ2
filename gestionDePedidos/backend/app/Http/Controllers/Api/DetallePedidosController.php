<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetallePedido;
use Illuminate\Http\Request;

class DetallePedidosController extends Controller
{
    public function index()
    {
        $detalles = DetallePedido::with(['pedido', 'producto'])->get();
        return response()->json($detalles, 200);
    }

    public function show($id)
    {
        $detalle = DetallePedido::with(['pedido', 'producto'])->find($id);
        if (!$detalle) {
            return response()->json(['message' => 'Detalle no encontrado'], 404);
        }
        return response()->json($detalle, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pedido' => 'required|exists:pedidos,id_pedido',
            'id_producto' => 'required|exists:productos,id_producto',
            'cantidad' => 'required|string',
            'Total' => 'required|string',
        ]);

        $detalle = DetallePedido::create($request->all());
        return response()->json($detalle, 201);
    }

    public function update(Request $request, $id)
    {
        $detalle = DetallePedido::find($id);
        if (!$detalle) {
            return response()->json(['message' => 'Detalle no encontrado'], 404);
        }

        $request->validate([
            'id_pedido' => 'sometimes|exists:pedidos,id_pedido',
            'id_producto' => 'sometimes|exists:productos,id_producto',
            'cantidad' => 'sometimes|string',
            'Total' => 'sometimes|string',
        ]);

        $detalle->update($request->all());
        return response()->json($detalle, 200);
    }

    public function destroy($id)
    {
        $detalle = DetallePedido::find($id);
        if (!$detalle) {
            return response()->json(['message' => 'Detalle no encontrado'], 404);
        }

        $detalle->delete();
        return response()->json(['message' => 'Detalle eliminado'], 200);
    }
}
