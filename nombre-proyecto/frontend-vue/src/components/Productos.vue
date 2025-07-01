<template>
  <div class="productos-container">
    <h1>Productos</h1>

    <!-- Mostrar mensaje de carga -->
    <div v-if="loading" class="loading-message">
      <p>Cargando productos...</p>
    </div>

    <!-- Mostrar los productos -->
    <div v-if="productos.length > 0" class="productos-list">
      <div v-for="producto in productos" :key="producto.id" class="producto-item">
        <h2>{{ producto.nombre }}</h2>
        <p>{{ producto.descripcion }}</p>
        <button @click="verDetalle(producto.id)">Ver Detalle</button>
      </div>
    </div>

    <!-- Si no hay productos -->
    <div v-else>
      <p>No hay productos disponibles.</p>
    </div>

    <!-- Mostrar mensaje de error si ocurre alguno -->
    <div v-if="error" class="error-message">
      <p>{{ error }}</p>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router'; // Para obtener los parámetros de la URL

export default {
  name: 'ProductosTodo',
  setup() {
    const productos = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const route = useRoute();

    // Parámetros de búsqueda desde la URL
    const categoria = ref(route.query.categoria || "");
    const marca = ref(route.query.marca || "");
    const busqueda = ref(route.query.busqueda || "");

    // Función para obtener los productos filtrados
    const obtenerProductos = async () => {
      loading.value = true;
      error.value = null;

      try {
        // Crear la URL con los parámetros de búsqueda
        const url = new URL('http://127.0.0.1:8000/productos');
        if (categoria.value) url.searchParams.append('categoria', categoria.value);
        if (marca.value) url.searchParams.append('marca', marca.value);
        if (busqueda.value) url.searchParams.append('busqueda', busqueda.value);

        // Hacer la solicitud a la API
        const response = await fetch(url);
        if (!response.ok) {
          throw new Error('Error al obtener los productos');
        }
        productos.value = await response.json();
      } catch (err) {
        error.value = 'Hubo un problema al obtener los productos: ' + err.message;
        console.error(err);
      } finally {
        loading.value = false;
      }
    };

    // Ejecutar la búsqueda cuando los parámetros cambian
    watch(() => route.query, () => {
      categoria.value = route.query.categoria || "";
      marca.value = route.query.marca || "";
      busqueda.value = route.query.busqueda || "";
      obtenerProductos();
    });

    // Cargar los productos cuando el componente se monte
    onMounted(() => {
      obtenerProductos();
    });

    return { productos, loading, error };
  },
};
</script>

<style scoped>
.productos-container {
  padding: 2rem;
}

.loading-message {
  font-size: 1.5rem;
  color: #94c424;
  text-align: center;
}

.productos-list {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  justify-content: center;
}

.producto-item {
  background: #f9f9f9;
  padding: 1.5rem;
  border-radius: 8px;
  width: 300px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
  text-align: center;
}

.producto-item:hover {
  transform: translateY(-5px);
}

.producto-item h2 {
  font-size: 1.5rem;
  color: #4044ac;
}

.cta {
  background: #94c424;
  color: white;
  padding: 1rem 2rem;
  border: none;
  border-radius: 30px;
  text-transform: uppercase;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s ease;
  font-size: 1rem;
}

.cta:hover {
  background: #66B2FF;
  color: #000;
}

.error-message {
  color: red;
  font-weight: bold;
  text-align: center;
}
</style>
