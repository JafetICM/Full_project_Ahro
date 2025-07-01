<template>
  <div class="categorias-container">
    <h1>Categorías</h1>

    <!-- Mostrar el estado de carga -->
    <div v-if="loading" class="loading-message">
      <p>Cargando categorías...</p>
    </div>

    <!-- Mostrar categorías -->
    <div v-if="!loading && categorias.length > 0" class="categorias-list">
      <div v-for="categoria in categorias" :key="categoria.id" class="categoria-item">
        <h2>{{ categoria.nombre }}</h2>
        <p>Nivel: {{ categoria.nivel }}</p>
        
        <!-- Enlace para ver los productos de la categoría -->
        <router-link :to="{ name: 'CategoriaDetail', params: { id: categoria.id } }">
          <button class="cta">Subcategorías</button>
        </router-link>
      </div>
    </div>

    <!-- Mostrar mensaje si no hay categorías -->
    <div v-if="!loading && categorias.length === 0">
      <p>No hay categorías disponibles.</p>
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

    // Obtener las categorías desde la API
    onMounted(async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/categorias-syscom');
        const data = await response.json();
        categorias.value = data;
      } catch (error) {
        console.error('Error al obtener las categorías:', error);
      } finally {
        loading.value = false; // Al terminar, cambiamos el estado de loading
      }
    });

    return { categorias, loading };
  },
};
</script>

<style scoped>
/* Reset de estilos base */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  background: white;
  color: black;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  transition: background-color 0.3s ease, color 0.3s ease;
}

/* ====================== 
   Tema - Modo Oscuro 
   ====================== */

body.dark-mode {
  background-color: #121212;
  color: white;
}

body.dark-mode .main-welcome {
  background-color: #121212;
  color: white;
}

body.dark-mode .footer {
  background-color: #121212;
  color: white;
}

/* ======================
   Categorías Container
   ====================== */

.categorias-container {
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

.categorias-container h1 {
  font-size: 2.5rem;
  color: #4044ac;
  margin-bottom: 2rem;
  text-align: center;
  font-weight: bold;
}

.loading-message {
  text-align: center;
  font-size: 1.5rem;
  color: #94c424;
}

.categorias-list {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  justify-content: center;
}

/* Categoría Item */
.categoria-item {
  background: linear-gradient(135deg, #f9f9f9, #e9e9e9);
  padding: 1.5rem;
  border-radius: 15px;
  width: 300px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  text-align: center;
  overflow: hidden;
}

.categoria-item:hover {
  transform: translateY(-10px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.categoria-item h2 {
  font-size: 1.5rem;
  color: #4044ac;
  margin-bottom: 1rem;
  font-weight: bold;
}

.categoria-item p {
  color: #666;
  font-size: 1rem;
  margin-bottom: 1.5rem;
}

/* Botón Subcategorías */
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
  transform: translateY(-2px);
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
    font-size: 2rem;
  }

  .cta {
    font-size: 1rem;
    padding: 0.8rem 1.5rem;
  }

  .categorias-list {
    flex-direction: column;
    align-items: center;
  }
}
</style>
