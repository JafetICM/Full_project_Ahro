<template>
  <!--c:\Users\dell\nombre-proyecto/frontend-vue/src/components/MarcaDetail.vue-->
  <div class="marca-detail-container">
    <!-- Mostrar mensaje de error si ocurre alguno -->
    <div v-if="error" class="error-message">
      <p>{{ error }}</p>
    </div>

    <!-- Mostrar la marca -->
    <h1>{{ marca.nombre }}</h1>

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
      const responseMarca = await fetch(`http://localhost:8000/marcas/${id}`);
      if (!responseMarca.ok) {
        throw new Error('Error al obtener la marca');
      }
      marca.value = await responseMarca.json();
    };

    const loadProductos = async (id) => {
      const responseProductos = await fetch(`http://127.0.0.1:8000/marcas/${id}/productos`);
      if (!responseProductos.ok) {
        throw new Error('Error al obtener los productos');
      }
      productos.value = await responseProductos.json();
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
</style>
