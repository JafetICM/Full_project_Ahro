<template>
  <div v-if="producto" class="producto-detail-container">
    <h1>{{ producto.titulo }}</h1>

    <div class="product-image">
      <img :src="producto.img_portada" alt="Imagen del producto" />
    </div>

    <div class="product-info">
      <p><strong>Marca:</strong> {{ producto.marca }}</p>
      <p><strong>Modelo:</strong> {{ producto.modelo }}</p>
      <p><strong>Clave SAT:</strong> {{ producto.sat_key }}</p>
      <p><strong>Precio Lista:</strong> ${{ producto.precios.precio_lista }}</p>
      <p><strong>Precio Especial:</strong> ${{ producto.precios.precio_especial }}</p>
      <p><strong>Precio con Descuento:</strong> ${{ producto.precios.precio_descuentos }}</p>
    </div>

    <div class="product-icons">
      <p><strong>Íconos:</strong></p>
      <div v-for="(icon, position) in producto.iconos" :key="position">
        <strong>{{ position }}:</strong> <img :src="icon" alt="Ícono" />
      </div>
    </div>

    <div class="product-characteristics">
      <h3>Características</h3>
      <ul>
        <li v-for="(caracteristica, index) in producto.caracteristicas" :key="index">
          {{ caracteristica }}
        </li>
      </ul>
    </div>

    <div class="product-images">
      <h3>Imágenes</h3>
      <div v-for="(imagen, index) in producto.imagenes" :key="index" class="image-item">
        <img :src="imagen.url" :alt="'Imagen ' + index" />
      </div>
    </div>

    <div class="product-description">
      <h3>Descripción</h3>
      <p>{{ producto.descripcion }}</p>
    </div>

    <div class="product-resources">
      <h3>Recursos</h3>
      <ul>
        <li v-for="(recurso, index) in producto.recursos" :key="index">
          <a :href="recurso.path" target="_blank">{{ recurso.recurso }}</a>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';

export default {
  name: 'ProductoDetail',
  setup() {
    const route = useRoute();
    const producto = ref(null);

    // Obtener el detalle del producto desde la API
    onMounted(async () => {
      const id = route.params.id;
      try {
        const response = await fetch(`http://127.0.0.1:8000/productos/${id}`);
        if (!response.ok) {
          throw new Error('Error al obtener el producto');
        }
        producto.value = await response.json();
      } catch (error) {
        console.error('Error al obtener el producto:', error);
      }
    });

    return {
      producto,
    };
  },
};
</script>

<style scoped>
.producto-detail-container {
  padding: 2rem;
}

.product-image img {
  width: 100%;
  max-width: 500px;
  height: auto;
}

.product-info p {
  font-size: 1.1rem;
}

.product-icons {
  margin-top: 1rem;
}

.product-icons img {
  width: 30px;
  height: 30px;
  margin-right: 10px;
}

.product-characteristics {
  margin-top: 2rem;
}

.product-characteristics ul {
  list-style-type: none;
}

.product-images {
  margin-top: 2rem;
}

.product-images img {
  width: 150px;
  height: auto;
  margin-right: 15px;
}

.product-description {
  margin-top: 2rem;
}

.product-resources {
  margin-top: 2rem;
}

.product-resources a {
  color: #94c424;
  text-decoration: none;
}

.product-resources a:hover {
  text-decoration: underline;
}
</style>
