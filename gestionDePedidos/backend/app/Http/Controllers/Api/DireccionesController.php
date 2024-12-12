<?php
namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Direccion;

class DireccionesController extends Controller
{
    public function index()
    {
        $direcciones = Direccion::where('id_usuario', Auth::id())->get();
        return response()->json([
            'message' => 'Direcciones obtenidas con éxito',
            'direcciones' => $direcciones,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ciudad' => 'required|string|max:255',
            'estado' => 'required|string|max:255',
            'numero_casa' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:255',
            'calle' => 'required|string|max:255',
        ]);

        $direccion = Direccion::create([
            'id_usuario' => Auth::id(),
            'ciudad' => $request->ciudad,
            'estado' => $request->estado,
            'numero_casa' => $request->numero_casa,
            'codigo_postal' => $request->codigo_postal,
            'calle' => $request->calle,
        ]);

        return response()->json([
            'message' => 'Dirección creada con éxito',
            'direccion' => $direccion,
        ]);
    }

    public function destroy($id)
    {
        $direccion = Direccion::where('id', $id)
            ->where('id_usuario', Auth::id())
            ->first();

        if (!$direccion) {
            return response()->json([
                'message' => 'Dirección no encontrada o no autorizada para eliminar',
            ], 404);
        }

        $direccion->delete();

        return response()->json([
            'message' => 'Dirección eliminada con éxito',
        ]);
    }
}
