<template>
  <div class="marca-detail-container">
    <!-- Mostrar mensaje de error si ocurre alguno -->
    <div v-if="error" class="error-message">
      <p>{{ error }}</p>
    </div>

    <!-- Mostrar la marca -->
    <div v-if="marca">
      <h1>{{ marca.titulo }}</h1>
      <div class="marca-logo">
        <img :src="marca.logo" alt="Logo de la marca" />
      </div>
      <p class="marca-description">{{ marca.descripcion }}</p>

      <!-- Mostrar categorías de la marca -->
      <h2>Categorías</h2>
      <div v-if="marca.categorias && marca.categorias.length > 0">
        <div v-for="categoria in marca.categorias" :key="categoria.id" class="categoria-item">
          <img :src="categoria.imagen" alt="Imagen de la categoría" class="categoria-image" />
          <div class="categoria-info">
            <h3>{{ categoria.nombre }}</h3>
            <p>Cantidad: {{ categoria.cantidad || 'No disponible' }}</p>
          </div>
        </div>
      </div>
      <div v-else>
        <p>No hay categorías disponibles para esta marca.</p>
      </div>

      <!-- Mostrar los productos de la marca -->
      <h2>Productos</h2>
      <div v-if="productos.length > 0">
        <ul>
          <li v-for="producto in productos" :key="producto.id">
            {{ producto.nombre }}
          </li>
        </ul>
      </div>
      <div v-else>
        <p>No hay productos disponibles para esta marca.</p>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router'; // Usamos vue-router para acceder al id en la URL

export default {
  name: 'MarcaDetail',
  setup() {
    const route = useRoute(); // Accedemos a los parámetros de la URL
    const marca = ref({});
    const productos = ref([]);
    const error = ref(null);

    onMounted(async () => {
      const id = route.params.id;
      await loadMarca(id);
      await loadProductos(id);
    });

    const loadMarca = async (id) => {
      try {
        const responseMarca = await fetch(`http://localhost:8000/marcas/${id}`);
        if (!responseMarca.ok) {
          throw new Error('Error al obtener la marca');
        }
        marca.value = await responseMarca.json();
      } catch (err) {
        error.value = `Hubo un problema al obtener la marca: ${err.message}`;
        console.error(err);
      }
    };

    const loadProductos = async (id) => {
      try {
        const responseProductos = await fetch(`http://127.0.0.1:8000/marcas/${id}/productos`);
        if (!responseProductos.ok) {
          throw new Error('Error al obtener los productos');
        }
        productos.value = await responseProductos.json();
      } catch (err) {
        error.value = `Hubo un problema al obtener los productos: ${err.message}`;
        console.error(err);
      }
    };

    return { marca, productos, error };
  },
};
</script>

<style scoped>
.error-message {
  color: red;
  font-weight: bold;
}

.marca-logo img {
  max-width: 200px;
  height: auto;
  margin-bottom: 1rem;
}

.marca-description {
  font-size: 1.1rem;
  color: #666;
  margin-bottom: 2rem;
}

.categoria-item {
  display: flex;
  margin-bottom: 1.5rem;
}

.categoria-image {
  width: 50px;
  height: 50px;
  object-fit: cover;
  margin-right: 1rem;
}

.categoria-info h3 {
  font-size: 1.2rem;
  color: #4044ac;
}

.categoria-info p {
  color: #666;
}

h2 {
  color: #94c424;
  font-size: 1.5rem;
  margin-bottom: 1rem;
}

ul {
  list-style-type: none;
  padding-left: 0;
}

ul li {
  background-color: #f9f9f9;
  padding: 0.5rem;
  margin: 0.5rem 0;
  border-radius: 5px;
}

ul li:hover {
  background-color: #e1f4e1;
}
</style>
