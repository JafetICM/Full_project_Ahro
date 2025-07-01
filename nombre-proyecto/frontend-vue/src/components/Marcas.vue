<template>
  <!--frontend-vue/src/components/Marcas.vue-->
  <div class="marcas-container">
    <h1>Marcas</h1>

    <!-- Mostrar el estado de carga -->
    <div v-if="loading" class="loading-message">
      <p>Cargando marcas...</p>
    </div>

    <!-- Mostrar el mensaje de error si ocurre alguno -->
    <div v-if="error" class="error-message">
      <p>{{ error }}</p>
    </div>

    <!-- Mostrar marcas -->
    <div v-if="marcas.length > 0" class="marcas-list">
      <div v-for="marca in marcas" :key="marca.id" class="marca-item">
        <h2>{{ marca.nombre }}</h2>
        <router-link :to="{ name: 'MarcaDetail', params: { id: marca.id } }">
          <button class="cta">Ver productos</button>
        </router-link>
      </div>
    </div>

    <!-- Si no hay marcas -->
    <div v-else>
      <p>No hay marcas disponibles.</p>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';

export default {
  name: 'MarcaList',
  setup() {
    const marcas = ref([]);
    const loading = ref(true);
    const error = ref(null);

    // Obtener las marcas desde la API
    onMounted(async () => {
    try {
      const response = await fetch('http://127.0.0.1:8000/marcas');
      if (!response.ok) {
        throw new Error(`Error al obtener las marcas: ${response.statusText}`);
      }
      const data = await response.json();
      marcas.value = data;
    } catch (error) {
      error.value = `Hubo un problema al obtener las marcas: ${error.message}`;  // Aquí puedes capturar el error
      console.error(error);
    } finally {
      loading.value = false;
    }
  });

    return { marcas, loading, error };
  },
};
</script>

<style scoped>
.marcas-container {
  padding: 2rem;
}

.loading-message {
  font-size: 1.5rem;
  color: #94c424;
  text-align: center;
}

.error-message {
  color: red;
  font-weight: bold;
  text-align: center;
}

.marcas-list {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  justify-content: center;
}

.marca-item {
  background: #f9f9f9;
  padding: 1.5rem;
  border-radius: 8px;
  width: 300px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
  text-align: center;
}

.marca-item:hover {
  transform: translateY(-5px);
}

.marca-item h2 {
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

/* Mejora la visibilidad en el botón al pasar el mouse */
.cta:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(102, 178, 255, 0.5);
}

</style>
