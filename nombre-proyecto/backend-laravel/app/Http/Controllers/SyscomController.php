<?php
// C:\Users\dell\nombre-proyecto\backend-laravel\app\Http\Controllers\SyscomController.php
namespace App\Http\Controllers;

use App\Services\SyscomService;
use Illuminate\Http\Request;

class SyscomController extends Controller
{
    protected $syscom;

    // Constructor donde se inyecta el servicio SyscomService
    public function __construct(SyscomService $syscom)
    {
        $this->syscom = $syscom;
    }

    // Obtener todas las categorías
    public function categorias()
    {
        try {
            $categorias = $this->syscom->getCategorias(); // Llamar al servicio para obtener categorías
            return response()->json($categorias)
                             ->header('Access-Control-Allow-Origin', '*');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener las categorías', 'message' => $e->getMessage()], 500);
        }
    }

    // Obtener categoría por ID
    public function categoriaPorId($id)
    {
        try {
            $categoria = $this->syscom->getCategoriaPorId($id); // Llamar al servicio para obtener la categoría por ID
            return response()->json($categoria)
                             ->header('Access-Control-Allow-Origin', '*');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener la categoría', 'message' => $e->getMessage()], 500);
        }
    }

    // Obtener productos de una categoría
    public function obtenerProductosPorCategoria($id)
    {
        try {
            $productos = $this->syscom->getProductosPorCategoria($id); // Llamar al servicio para obtener los productos por ID de categoría
            return response()->json($productos)
                             ->header('Access-Control-Allow-Origin', '*');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los productos', 'message' => $e->getMessage()], 500);
        }
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////
    //Controles del carrito.
    // ----------------------------
    // Direcciones.
    // ----------------------------

    public function obtenerDirecciones()
    {
        return response()->json($this->syscom->obtenerDirecciones());
    }

    public function insertarDireccion(Request $request)
    {
        return response()->json($this->syscom->insertarDireccion($request->all()));
    }

    public function actualizarDireccion(Request $request, $id)
    {
        return response()->json($this->syscom->actualizarDireccion($id, $request->all()));
    }

    public function eliminarDireccion($id)
    {
        return response()->json($this->syscom->eliminarDireccion($id));
    }

    // ----------------------------
    // Extras (paises, estados, colonias, etc.)
    // ----------------------------

    public function obtenerPaises()
    {
        return response()->json($this->syscom->obtenerPaises());
    }

    public function obtenerEstados($cp)
    {
        return response()->json($this->syscom->obtenerEstados($cp));
    }

    public function obtenerColonias($cp)
    {
        return response()->json($this->syscom->obtenerColonias($cp));
    }

    public function formasPago()
    {
        return response()->json($this->syscom->obtenerFormasPago());
    }

    public function obtenerFleteras()
    {
        return response()->json($this->syscom->obtenerFleteras());
    }

    public function obtenerSucursales()
    {
        return response()->json($this->syscom->obtenerSucursales());
    }

    public function obtenerCFDI()
    {
        return response()->json($this->syscom->obtenerCFDI());
    }

    // ----------------------------
    // Las Facturas..
    // ----------------------------
    public function obtenerFacturas()
    {
        return response()->json($this->syscom->obtenerFacturas());
    }

    public function obtenerFacturaPorId($id)
    {
        return response()->json($this->syscom->obtenerFacturaPorId($id));
    }

    // ----------------------------
    // Las Marcas
    // ----------------------------

    public function obtenerMarcas()
    {
        return response()->json($this->syscom->obtenerMarcas());
    }

    public function obtenerMarcaPorId($id)
    {
        return response()->json($this->syscom->obtenerMarcaPorId($id));
    }

    public function obtenerProductosDeMarca($id)
    {
        return response()->json($this->syscom->obtenerProductosDeMarca($id));
    }

    // ----------------------------
    // Los Productos
    // ----------------------------
    public function obtenerProductoPorId($id)
    {
        return response()->json($this->syscom->obtenerProductoPorId($id));
    }

    public function obtenerRelacionados($id)
    {
        return response()->json($this->syscom->obtenerProductosRelacionados($id));
    }

    public function obtenerAccesorios($id)
    {
        return response()->json($this->syscom->obtenerAccesoriosDelProducto($id));
    }

    public function buscarProductos(Request $request)
    {
        return response()->json($this->syscom->buscarProductos($request->all()));
    }

    // ----------------------------
    // Tipos de cambio
    // ----------------------------

    public function tipoCambio()
    {
        return response()->json($this->syscom->obtenerTipoCambio());
    }
}
