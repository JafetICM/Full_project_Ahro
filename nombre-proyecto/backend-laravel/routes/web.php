<?php
// c:\Users\dell\nombre-proyecto/backend-laravel\routes\web.php
use App\Http\Controllers\SyscomController;

//Rutas de las categorías.
Route::get('/categorias-syscom', [SyscomController::class, 'categorias']);
Route::get('/categorias-syscom/{id}', [SyscomController::class, 'categoriaPorId']);

//Rutas del carrito;.
Route::prefix('carrito')->group(function () {
    Route::get('/', [SyscomController::class, 'verCarrito']);
    Route::post('/agregar', [SyscomController::class, 'agregarProducto']);
    Route::delete('/eliminar', [SyscomController::class, 'eliminarProducto']);
//Direcciones
    Route::post('/generar', [SyscomController::class, 'generarPedido']);
    Route::get('/direcciones', [SyscomController::class, 'obtenerDirecciones']);
    Route::post('/direcciones', [SyscomController::class, 'insertarDireccion']);
    Route::post('/direcciones/{id}', [SyscomController::class, 'actualizarDireccion']);
    Route::delete('/direcciones/{id}', [SyscomController::class, 'eliminarDireccion']);
//Paises
    Route::get('/paises', [SyscomController::class, 'obtenerPaises']);
    Route::get('/estados/{cp}', [SyscomController::class, 'obtenerEstados']);
    Route::get('/colonias/{cp}', [SyscomController::class, 'obtenerColonias']);
    Route::get('/pago', [SyscomController::class, 'formasPago']);
    Route::get('/fleteras', [SyscomController::class, 'obtenerFleteras']);
    Route::get('/sucursales', [SyscomController::class, 'obtenerSucursales']);
    Route::get('/cfdi', [SyscomController::class, 'obtenerCFDI']);
});

//Rutas de las facuras.
    Route::get('/facturas', [SyscomController::class, 'obtenerFacturas']);
    Route::get('/facturas/{id}', [SyscomController::class, 'obtenerFacturaPorId']);

//Rutas de las marcas.
    Route::get('/marcas', [SyscomController::class, 'obtenerMarcas']);
    Route::get('/marcas/{id}', [SyscomController::class, 'obtenerMarcaPorId']);
    Route::get('/marcas/{id}/productos', [SyscomController::class, 'obtenerProductosDeMarca']);

//Rutas de los productos.
    Route::get('/productos/{id}', [SyscomController::class, 'obtenerProductoPorId']);
    Route::get('/productos/{id}/relacionados', [SyscomController::class, 'obtenerRelacionados']);
    Route::get('/productos/{id}/accesorios', [SyscomController::class, 'obtenerAccesorios']);
    Route::get('/productos', [SyscomController::class, 'buscarProductos'])->name('buscarProductos');

//Rutas de los Tipos de cambio.
    Route::get('/tipocambio', [SyscomController::class, 'tipoCambio']);
