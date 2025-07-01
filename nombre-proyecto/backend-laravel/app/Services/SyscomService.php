<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SyscomService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        // Definir la URL base del servicio y el token de autenticación
        $this->baseUrl = config('services.syscom.url');
        $this->token = $this->obtenerToken();
    }

    // Obtiene el token de acceso y lo cachea por 30 días (ajusta según la expiración real)
    private function obtenerToken()
    {
        return Cache::remember('syscom_token', 60 * 60 * 24 * 30, function () {
            $response = Http::asForm()->post('https://developers.syscom.mx/oauth/token', [
                'client_id' => config('services.syscom.client_id'),
                'client_secret' => config('services.syscom.client_secret'),
                'grant_type' => 'client_credentials',
            ]);

            if ($response->successful()) {
                return $response->json()['access_token'];
            }

            throw new \Exception('No se pudo obtener el token de SYSCOM');
        });
    }

    // Obtener todas las categorías
    public function getCategorias()
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/categorias")
            ->json();
    }

    // Obtener una categoría por su ID
    public function getCategoriaPorId($id)
    {
        $response = Http::withToken($this->token)
            ->get("{$this->baseUrl}/categorias/{$id}");

        if ($response->successful()) {
            return $response->json();
        }

        return ['error' => 'Error al obtener la categoría', 'status' => $response->status()];
    }

    // Obtener los productos por categoría
    public function getProductosPorCategoria($id)
    {
        $response = Http::withToken($this->token)
            ->get("{$this->baseUrl}/productos?categoria_id={$id}");

        if ($response->successful()) {
            return $response->json();
        }

        return ['error' => 'Error al obtener los productos', 'status' => $response->status()];
    }

    // POST para generar un pedido
    public function generarPedido()
    {
        return Http::withToken($this->token)
            ->post("{$this->baseUrl}/carrito/generar")
            ->json();
    }

    // Obtener direcciones
    public function obtenerDirecciones()
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/carrito/direcciones")
            ->json();
    }

    // Insertar una nueva dirección
    public function insertarDireccion($data)
    {
        return Http::withToken($this->token)
            ->post("{$this->baseUrl}/carrito/direcciones", $data)
            ->json();
    }

    // Actualizar una dirección
    public function actualizarDireccion($id, $data)
    {
        return Http::withToken($this->token)
            ->post("{$this->baseUrl}/carrito/direcciones/{$id}", $data)
            ->json();
    }

    // Eliminar una dirección
    public function eliminarDireccion($id)
    {
        return Http::withToken($this->token)
            ->delete("{$this->baseUrl}/carrito/direcciones/{$id}")
            ->json();
    }

    // Obtener países registrados
    public function obtenerPaises()
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/carrito/paises")
            ->json();
    }

    // Obtener todos los estados de un país por código postal
    public function obtenerEstados($codigoPostal)
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/carrito/estados/{$codigoPostal}")
            ->json();
    }

    // Obtener colonias por código postal
    public function obtenerColonias($codigoPostal)
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/carrito/colonias/{$codigoPostal}")
            ->json();
    }

    // Obtener formas de pago
    public function obtenerFormasPago()
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/carrito/pago")
            ->json();
    }

    // Obtener fleteras
    public function obtenerFleteras()
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/carrito/fleteras")
            ->json();
    }

    // Obtener sucursales
    public function obtenerSucursales()
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/carrito/sucursales")
            ->json();
    }

    // Obtener CFDI
    public function obtenerCFDI()
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/carrito/cfdi")
            ->json();
    }

    // Obtener todas las marcas
    public function obtenerMarcas()
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/marcas")
            ->json();
    }

    // Obtener una marca por ID
    public function obtenerMarcaPorId($id)
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/marcas/{$id}")
            ->json();
    }

    // Obtener productos por marca
    public function obtenerProductosDeMarca($id)
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/marcas/{$id}/productos")
            ->json();
    }

    // Obtener producto por ID
    public function obtenerProductoPorId($id)
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/productos/{$id}")
            ->json();
    }

    // Obtener productos relacionados a un producto
    public function obtenerProductosRelacionados($id)
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/productos/{$id}/relacionados")
            ->json();
    }

    // Obtener accesorios de un producto
    public function obtenerAccesoriosDelProducto($id)
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/productos/{$id}/accesorios")
            ->json();
    }

    // Buscar productos con filtros
    public function buscarProductos($filtros = [])
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/productos", $filtros)
            ->json();
    }

    // Obtener tipo de cambio
    public function obtenerTipoCambio()
    {
        return Http::withToken($this->token)
            ->get("{$this->baseUrl}/tipocambio")
            ->json();
    }
}
