<template>
  <!-- c:\Users\dell\nombre-proyecto/frontend-vue/src/components/ProductSearchForm.vue -->
  <form @submit.prevent="buscarProductos" class="product-search-form">
    <select v-model="categoria" required>
      <option value="">Selecciona Categoría</option>
      <option v-for="categoria in categorias" :key="categoria.id" :value="categoria.id">{{ categoria.nombre }}</option>
    </select>

    <select v-model="marca" required>
      <option value="">Selecciona Marca</option>
      <option v-for="marca in marcas" :key="marca.id" :value="marca.id">{{ marca.nombre }}</option>
    </select>

    <input v-model="busqueda" type="text" placeholder="Buscar productos" />

    <button type="submit">Buscar</button>
  </form>
</template>

<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

export default {
  name: 'ProductSearchForm',
  setup() {
    const categoria = ref('');
    const marca = ref('');
    const busqueda = ref('');
    const router = useRouter();

    const categorias = ref([{ id: 1, nombre: 'Categoría 1' }, { id: 2, nombre: 'Categoría 2' }]); // Rellenar con datos
    const marcas = ref([{ id: 1, nombre: 'Marca 1' }, { id: 2, nombre: 'Marca 2' }]); // Rellenar con datos

    const buscarProductos = () => {
      const query = {};
      if (categoria.value) query.categoria = categoria.value;
      if (marca.value) query.marca = marca.value;
      if (busqueda.value) query.busqueda = busqueda.value;

      router.push({ path: '/productos', query });
    };

    return {
      categoria,
      marca,
      busqueda,
      categorias,
      marcas,
      buscarProductos,
    };
  },
};
</script>

<style scoped>
.product-search-form {
  display: flex;
  gap: 1rem;
}

.product-search-form select,
.product-search-form input {
  padding: 0.5rem;
  font-size: 1rem;
  border-radius: 4px;
}

.product-search-form button {
  padding: 0.5rem 1rem;
  background-color: #94c424;
  color: white;
  font-weight: bold;
  cursor: pointer;
}
</style>
