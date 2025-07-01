<template>
  <div>
    <!-- Navbar siempre visible en todas las páginas -->
    <nav class="navbar">
      <!-- Logo que redirige al inicio -->
      <router-link to="/" class="logo">SYSCOM Catalog</router-link>

      <!-- Menú de botones centrados -->
      <ul class="menu">
        <!-- Menú desplegable de Categorías -->
        <li class="dropdown">
          <a href="#">Categorías</a>
          <div class="dropdown-content">
            <div v-if="categorias.length === 0 && !loading">Cargando...</div>
            <div v-for="categoria in categorias" :key="categoria.id" class="category-item">
              <a :href="`/categorias/${categoria.id}`"> </a>
              <!-- Subcategorías -->
              <div class="subcategories">
                <a v-for="subcategoria in categoria.subcategorias" :key="subcategoria.id" :href="`/categorias/${subcategoria.id}`">
                  {{ subcategoria.nombre }}
                </a>
              </div>
            </div>
          </div>
        </li>
        <li><router-link to="/productos">Productos</router-link></li>
        <li><router-link to="/marcas">Marcas</router-link></li>
      </ul>

      <!-- Formulario de búsqueda de productos dentro del navbar -->
      <div class="search-container">
        <form @submit.prevent="buscarProductos">
          <input v-model="busqueda" type="text" placeholder="Buscar productos" />
          <button type="submit">Buscar</button>
        </form>
      </div>

      <!-- Botón para alternar el tema -->
      <div class="theme-switch">
        <button @click="toggleTheme" aria-label="Alternar tema">
          <span v-if="isDarkMode">🌙</span>
          <span v-else>☀️</span>
        </button>
      </div>

      <!-- Ícono del carrito -->
      <div class="cart-icon">
        <router-link to="/carrito">
          <img src="./assets/images/cart-icon.png" alt="Carrito" class="cart-img" />
        </router-link>
      </div>
    </nav>

    <!-- Aquí se renderizará el contenido de las rutas -->
    <main class="content">
      <router-view />
    </main>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

export default {
  name: 'App',
  setup() {
    const isDarkMode = ref(false);
    const categorias = ref([]);
    const busqueda = ref('');
    const loading = ref(true);
    const router = useRouter();

    // Alternar entre modo claro y oscuro
    const toggleTheme = () => {
      isDarkMode.value = !isDarkMode.value;
      if (isDarkMode.value) {
        document.body.classList.add('dark-mode');
        localStorage.setItem('theme', 'dark');
      } else {
        document.body.classList.remove('dark-mode');
        localStorage.setItem('theme', 'light');
      }
    };

    // Cargar las categorías desde la API cuando se monta el componente
    onMounted(async () => {
      try {
        const responseCategorias = await fetch('http://127.0.0.1:8000/categorias-syscom');
        const dataCategorias = await responseCategorias.json();
        categorias.value = dataCategorias;
        loading.value = false;
      } catch (error) {
        console.error('Error al obtener las categorías:', error);
      }
    });

    // Función de búsqueda
    const buscarProductos = () => {
      const query = {};
      if (busqueda.value) query.busqueda = busqueda.value;
      router.push({ path: '/productos', query });
    };

    // Cargar el tema guardado al montar el componente
    onMounted(() => {
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'dark') {
        isDarkMode.value = true;
        document.body.classList.add('dark-mode');
      } else {
        isDarkMode.value = false;
        document.body.classList.remove('dark-mode');
      }
    });

    return { isDarkMode, toggleTheme, categorias, busqueda, loading, buscarProductos };
  },
};
</script>

<style scoped>
/* Estilos del navbar */
.navbar {
  background-color: #94c424;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  position: fixed;
  width: 100%;
  top: 0;
  z-index: 1000;
  border-bottom: 1px solid #ccc;
}

.navbar .logo {
  font-size: 1.5rem;
  color: #4044ac;
  font-weight: bold;
  letter-spacing: 2px;
  text-transform: uppercase;
  text-decoration: none;
}

.navbar ul {
  list-style: none;
  display: flex;
  gap: 1.5rem;
  justify-content: center;
  flex: 1;
}

.navbar ul li {
  position: relative;
}

.navbar ul li a {
  text-decoration: none;
  color: black;
  text-transform: capitalize;
  font-size: 1rem;
  transition: color 0.3s ease;
}

.navbar ul li a:hover {
  color: #66B2FF;
}

/* Estilos para el formulario de búsqueda */
.search-container {
  display: flex;
  gap: 1rem;
}

.search-container input {
  padding: 0.5rem;
  font-size: 1rem;
  border-radius: 4px;
}

.search-container button {
  padding: 0.5rem 1rem;
  background-color: #94c424;
  color: white;
  font-weight: bold;
  cursor: pointer;
}

.navbar .theme-switch button {
  background: transparent;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
}

.navbar .theme-switch button:hover {
  color: #66B2FF;
}

/* Estilo para el carrito */
.cart-icon {
  margin-left: auto;
}

.cart-img {
  width: 30px;
  height: 30px;
  cursor: pointer;
}

.content {
  margin-top: 80px;
}
</style>
