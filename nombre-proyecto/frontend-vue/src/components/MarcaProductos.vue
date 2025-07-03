<template>
    <!-- C/users/dell/frontend-vue/src\components\MarcaProductos.vue-->
  <div class="marca-productos-container">
    <!-- Mostrar mensaje de error si ocurre alguno -->
    <div v-if="error" class="error-message">
      <p>{{ error }}</p>
    </div>

    <!-- Mostrar productos de la marca -->
    <div v-if="productos.length > 0">
      <h1>Productos de {{ marca.nombre }}</h1>
      <div v-for="producto in productos" :key="producto.producto_id" class="producto-item">
        <img :src="producto.img_portada" alt="Producto" class="producto-img" />
        <div class="producto-details">
          <h2>{{ producto.titulo }}</h2>
          <p><strong>Modelo:</strong> {{ producto.modelo }}</p>
          <p><strong>Cantidad disponible:</strong> {{ producto.total_existencia }}</p>
          <div class="categorias">
            <p><strong>Categorías:</strong></p>
            <ul>
              <li v-for="categoria in producto.categorías" :key="categoria.id">
                {{ categoria.nombre }}
              </li>
            </ul>
          </div>
          <div class="precio">
            <p><strong>Precio Especial:</strong> ${{ producto.precios.precio_especial }}</p>
            <p><strong>Precio Lista:</strong> ${{ producto.precios.precio_lista }}</p>
            <p><strong>Precio con Descuento:</strong> ${{ producto.precios.precio_descuento }}</p>
          </div>
          <a :href="producto.link" target="_blank" class="producto-link">Ver más</a>
        </div>
      </div>
    </div>

    <!-- Si no hay productos disponibles -->
    <div v-else>
      <p>No hay productos disponibles para esta marca.</p>
    </div>

    <!-- Loading de productos -->
    <div v-if="loading" class="loading-message">
      <p>Cargando productos...</p>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';

export default {
  name: 'MarcaProductos',
  setup() {
    const route = useRoute();
    const marca = ref({ nombre: 'Cargando...' });
    const productos = ref([]);
    const error = ref(null);
    const loading = ref(true);

    onMounted(async () => {
      const id = route.params.id;
      await loadMarca(id);
      await loadProductos(id);
    });

    const loadMarca = async (id) => {
      try {
        const response = await fetch(`http://127.0.0.1:8000/marcas/${id}`);
        if (!response.ok) {
          throw new Error('Error al obtener la marca');
        }
        marca.value = await response.json();
      } catch (err) {
        error.value = `Hubo un problema al obtener la marca: ${err.message}`;
      }
    };

    const loadProductos = async (id) => {
      try {
        const response = await fetch(`http://127.0.0.1:8000/marcas/${id}/productos`);
        if (!response.ok) {
          throw new Error('Error al obtener los productos');
        }
        const data = await response.json();
        productos.value = data.productos || [];
      } catch (err) {
        error.value = `Hubo un problema al obtener los productos: ${err.message}`;
      } finally {
        loading.value = false;
      }
    };

    return { marca, productos, error, loading };
  }
};
</script>

<style scoped>
.marca-productos-container {
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

.error-message {
  color: red;
  font-weight: bold;
  text-align: center;
}

.loading-message {
  font-size: 1.5rem;
  color: #94c424;
  text-align: center;
}

.producto-item {
  display: flex;
  border-bottom: 1px solid #ccc;
  padding: 20px;
  margin-bottom: 20px;
}

.producto-img {
  width: 150px;
  height: 150px;
  object-fit: cover;
  margin-right: 20px;
}

.producto-details {
  flex-grow: 1;
}

.producto-details h2 {
  color: #4044ac;
}

.categorias ul {
  list-style-type: none;
  padding: 0;
}

.categorias li {
  background-color: #f9f9f9;
  padding: 5px;
  margin: 5px 0;
  border-radius: 4px;
}

.precio {
  margin-top: 15px;
}

.precio p {
  font-weight: bold;
  margin: 5px 0;
}

.producto-link {
  display: inline-block;
  padding: 10px 15px;
  background-color: #94c424;
  color: white;
  border-radius: 5px;
  text-decoration: none;
  margin-top: 10px;
}

.producto-link:hover {
  background-color: #66B2FF;
}
</style>
