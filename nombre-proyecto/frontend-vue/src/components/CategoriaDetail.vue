<template>
  <div class="categoria-detail-container">
    <!-- Mostrar mensaje de error si ocurre alguno -->
    <div v-if="error" class="error-message">
      <p>{{ error }}</p>
    </div>

    <!-- Mostrar la categoría -->
    <div v-if="categoria.nombre">
      <h1 class="categoria-title">{{ categoria.nombre }}</h1>
      <p class="categoria-level">Nivel: {{ categoria.nivel }}</p>

      <!-- Mostrar las subcategorías -->
      <h2 class="subcategorias-title">Subcategorías</h2>
      <div v-if="categoria.subcategorias && categoria.subcategorias.length > 0">
        <ul class="subcategorias-list">
          <li v-for="subcategoria in categoria.subcategorias" :key="subcategoria.id" class="subcategoria-item">
            <a :href="`/categorias/${subcategoria.id}`">{{ subcategoria.nombre }}</a>
            <span class="subcategoria-level">(Nivel: {{ subcategoria.nivel }})</span>
          </li>
        </ul>
      </div>
      <div v-else>
        <p>No hay subcategorías disponibles para esta categoría.</p>
      </div>
    </div>

    <!-- Mostrar mensaje de carga si está en proceso -->
    <div v-if="loading" class="loading-message">
      <p>Cargando información de la categoría...</p>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';

export default {
  name: 'CategoriaDetail',
  setup() {
    const route = useRoute();
    const categoria = ref({
      subcategorias: [],
    });
    const error = ref(null);
    const loading = ref(true); // Variable para manejar el estado de carga

    onMounted(async () => {
      const id = route.params.id;
      console.log('ID de la categoría:', id);

      try {
        // Obtener los detalles de la categoría desde el backend
        const responseCategoria = await fetch(`http://127.0.0.1:8000/categorias-syscom/${id}`);
        if (!responseCategoria.ok) {
          throw new Error(`Error al obtener la categoría: ${responseCategoria.statusText}`);
        }
        categoria.value = await responseCategoria.json();
      } catch (err) {
        error.value = `Hubo un problema al obtener los datos: ${err.message}`;
        console.error(err);
      } finally {
        loading.value = false; // Cambio a false cuando la carga termina
      }
    });

    return { categoria, error, loading };
  },
};
</script>

<style scoped>
.categoria-detail-container {
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

.categoria-title {
  font-size: 2rem;
  color: #4044ac;
  font-weight: bold;
  margin-bottom: 1rem;
}

.categoria-level {
  font-size: 1rem;
  color: #666;
  margin-bottom: 2rem;
}

.subcategorias-title {
  font-size: 1.5rem;
  color: #94c424;
  margin-bottom: 1rem;
}

.subcategorias-list {
  list-style: none;
  padding: 0;
}

.subcategoria-item {
  background-color: #f9f9f9;
  padding: 1rem;
  margin-bottom: 0.8rem;
  border-radius: 8px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: background-color 0.3s ease;
}

.subcategoria-item:hover {
  background-color: #e1f4e1;
}

.subcategoria-item a {
  text-decoration: none;
  color: #4044ac;
  font-size: 1.1rem;
}

.subcategoria-level {
  font-size: 0.9rem;
  color: #666;
}

.error-message {
  color: red;
  font-weight: bold;
}

.loading-message {
  text-align: center;
  font-size: 1.5rem;
  color: #94c424;
}

/* Responsive */
@media (max-width: 768px) {
  .categoria-title {
    font-size: 1.5rem;
  }

  .subcategorias-title {
    font-size: 1.2rem;
  }

  .subcategoria-item {
    padding: 0.8rem;
  }
}
</style>
