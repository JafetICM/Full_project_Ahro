<template>
  <!-- c:\Users\dell\nombre-proyecto/frontend-vue/src/components/Categorias.vue -->
  <div class="categorias-container">
    <h1>Categorías</h1>

    <!-- Mostrar el estado de carga -->
    <div v-if="loading" class="loading-message">
      <div class="spinner"></div>
      <p>Cargando categorías...</p>
    </div>

    <!-- Mostrar categorías y subcategorías -->
    <div v-if="!loading && categorias.length > 0" class="categorias-list">
      <div class="categorias-columns">
        <!-- Columna de categorías -->
        <div class="main-categories">
          <ul>
            <li v-for="categoria in categorias" :key="categoria.id" class="categoria-item" @click="setActiveCategory(categoria)">
              <h2>{{ categoria.nombre }}</h2>
              <p>Nivel: {{ categoria.nivel }}</p>
              <button class="cta">Subcategorías</button>
            </li>
          </ul>
        </div>

        <!-- Columna de subcategorías (a la derecha) -->
        <div class="subcategorias-column" v-if="activeCategory">
          <h2>{{ activeCategory.nombre }} - Subcategorías</h2>
          <div v-if="activeCategory.subcategorias && activeCategory.subcategorias.length > 0">
            <div class="subcategorias-grid">
              <div v-for="subcategoria in activeCategory.subcategorias" :key="subcategoria.id" class="subcategory-item">
                <router-link :to="`/categorias/${subcategoria.id}`" class="subcategory-link">
                  <div class="subcategory-card">
                    <h3>{{ subcategoria.nombre }}</h3>
                    <p class="subcategoria-level">(Nivel: {{ subcategoria.nivel }})</p>
                  </div>
                </router-link>
              </div>
            </div>
          </div>
          <div v-else>
            <p>No hay subcategorías disponibles para esta categoría.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Mostrar mensaje si no hay categorías -->
    <div v-if="!loading && categorias.length === 0">
      <p>No hay categorías disponibles.</p>
    </div>

    <!-- Mostrar mensaje de error si ocurre alguno -->
    <div v-if="error" class="error-message">
      <p>{{ error }}</p>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';

export default {
  name: 'CategoriaList',
  setup() {
    const categorias = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const activeCategory = ref(null); // Para manejar la categoría activa

    // Obtener las categorías desde la API
    onMounted(async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/categorias-syscom');
        const data = await response.json();
        categorias.value = data;
      } catch (err) {
        error.value = `Hubo un problema al obtener las categorías: ${err.message}`;
        console.error(err);
      } finally {
        loading.value = false;
      }
    });

    // Función para establecer la categoría activa y mostrar subcategorías
    const setActiveCategory = async (categoria) => {
      activeCategory.value = categoria;
      try {
        // Obtener las subcategorías de la categoría seleccionada
        const responseSubcategorias = await fetch(`http://127.0.0.1:8000/categorias-syscom/${categoria.id}`);
        const dataSubcategorias = await responseSubcategorias.json();
        activeCategory.value.subcategorias = dataSubcategorias.subcategorias || [];
      } catch (err) {
        error.value = `Hubo un problema al obtener las subcategorías: ${err.message}`;
        console.error(err);
      }
    };

    return { categorias, loading, activeCategory, setActiveCategory, error };
  },
};
</script>

<style scoped>
/* Spinner */
.spinner {
  border: 8px solid #f3f3f3;
  border-top: 8px solid #94c424;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Contenedor de categorías */
.categorias-container {
  padding: 1.5rem;
  max-width: 1000px;
  margin: 0 auto;
}

.categorias-container h1 {
  font-size: 2rem;
  color: #4044ac;
  margin-bottom: 1rem;
  text-align: center;
  font-weight: bold;
}

/* Mensaje de carga */
.loading-message {
  text-align: center;
  font-size: 1.2rem;
  color: #94c424;
}

/* Contenedor de columnas */
.categorias-columns {
  display: flex;
  gap: 1rem;
  justify-content: space-between;
}

.main-categories {
  flex: 1;
  padding: 0.5rem;
  border-right: 1px solid #eee;
}

.subcategorias-column {
  flex: 1;
  padding: 0.5rem;
}

.categoria-item {
  padding: 0.8rem;
  background-color: #f9f9f9;
  border-radius: 6px;
  margin-bottom: 1rem;
  cursor: pointer;
  font-size: 0.9rem;
}

.categoria-item:hover {
  background-color: #f1f1f1;
}

.categoria-item h2 {
  color: #4044ac;
  margin-bottom: 0.4rem;
  font-size: 1.2rem;
}

.categoria-item p {
  color: #666;
  margin-bottom: 1rem;
}

.cta {
  background: #94c424;
  color: white;
  padding: 0.6rem 1.2rem;
  border: none;
  border-radius: 25px;
  text-transform: uppercase;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s ease;
  font-size: 0.9rem;
}

.cta:hover {
  background: #66B2FF;
  color: #000;
}

/* Subcategorías */
.subcategorias-column h2 {
  font-size: 1.4rem;
  color: #4044ac;
  margin-bottom: 1rem;
}

.subcategorias-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
  gap: 0.8rem;
}

.subcategory-item {
  padding: 0.8rem;
  color: #666;
  text-decoration: none;
  background-color: #f9f9f9;
  border-radius: 6px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  text-align: center;
  font-size: 0.9rem;
}

.subcategory-item:hover {
  background-color: #e1f4e1;
  color: #4044ac;
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.subcategory-item h3 {
  font-size: 1rem;
  color: #4044ac;
  margin-bottom: 0.3rem;
}

.subcategoria-level {
  font-size: 0.8rem;
  color: #666;
}

/* Estilos para el modo oscuro */
body.dark-mode .categoria-item {
  background: #333;
  color: white;
}

body.dark-mode .cta {
  background: #66B2FF;
}

/* Responsividad */
@media (max-width: 768px) {
  .categorias-container h1 {
    font-size: 1.8rem;
  }

  .cta {
    font-size: 0.9rem;
    padding: 0.6rem 1.2rem;
  }

  .categorias-columns {
    flex-direction: column;
  }

  .subcategorias-column {
    margin-top: 2rem;
  }
}
</style>
