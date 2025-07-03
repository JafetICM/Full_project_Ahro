<template>
  <!-- c:\Users\dell\nombre-proyecto/frontend-vue/src/App.vue -->
  <div>
    <!-- Navbar -->
    <nav class="navbar">
      <router-link to="/" class="logo">SYSCOM Catalog</router-link>
      <ul class="menu">
        <li class="dropdown" @mouseover="showSubcategories" @mouseleave="hideSubcategories">
          <a href="#">Categorías</a>
          <div class="dropdown-content" v-show="isSubcategoriesVisible">
            <!-- Columna izquierda - Categorías principales -->
            <div class="categories-sidebar">
              <div
                v-for="categoria in categorias"
                :key="categoria.id"
                class="category-item"
                :class="{ active: selectedCategoryId === categoria.id }"
                @mouseover="selectCategory(categoria)"
              >
                <span class="category-name">{{ categoria.nombre }}</span>
                <span class="arrow-icon">›</span>
              </div>
            </div>

            <!-- Columna central - Subcategorías -->
            <div class="subcategories-content" v-if="selectedCategory && Array.isArray(selectedCategory.subcategorias)">
              <div class="content-header">
                <h3>{{ selectedCategory.nombre }}</h3>
              </div>
              <div class="subcategories-list">
                <div
                  v-for="subcategoria in selectedCategory.subcategorias"
                  :key="subcategoria.id"
                  class="subcategory-item"
                  @mouseover="selectSubcategory(subcategoria)"
                >
                  <a :href="`/categorias/${subcategoria.id}`" class="subcategory-link">
                    {{ subcategoria.nombre }}
                  </a>
                  <!-- Sub-subcategorías (se despliegan al pasar el cursor sobre una subcategoría) -->
                  <div
                    class="sub-subcategories-content"
                    v-if="selectedSubcategory && selectedSubcategory.id === subcategoria.id && Array.isArray(subcategoria.subcategorias)"
                  >
                    <div class="sub-subcategories-list">
                      <div
                        v-for="subSub in subcategoria.subcategorias"
                        :key="subSub.id"
                        class="sub-subcategory-item"
                      >
                        <a :href="`/categorias/${subSub.id}`" class="sub-subcategory-link">
                          {{ subSub.nombre }}
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </li>

        <li><router-link to="/productos">Productos</router-link></li>
        <li><router-link to="/marcas">Marcas</router-link></li>
      </ul>

      <!-- Búsqueda -->
      <div class="search-container">
        <form @submit.prevent="buscarProductos">
          <input v-model="busqueda" type="text" placeholder="Buscar productos" />
          <button type="submit">Buscar</button>
        </form>
      </div>

      <!-- Modo oscuro -->
      <div class="theme-switch">
        <button @click="toggleTheme" aria-label="Alternar tema">
          <span v-if="isDarkMode">🌙</span>
          <span v-else>☀️</span>
        </button>
      </div>

      <!-- Carrito -->
      <div class="cart-icon">
        <router-link to="/carrito">
          <img src="./assets/images/cart-icon.png" alt="Carrito" class="cart-img" />
        </router-link>
      </div>
    </nav>

    <!-- Vista principal -->
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
    const isSubcategoriesVisible = ref(false);
    const selectedCategory = ref(null);
    const selectedSubcategory = ref(null);
    const selectedCategoryId = ref(null);
    const router = useRouter();

    // Alternar tema
    const toggleTheme = () => {
      isDarkMode.value = !isDarkMode.value;
      document.body.classList.toggle('dark-mode', isDarkMode.value);
      localStorage.setItem('theme', isDarkMode.value ? 'dark' : 'light');
    };

    // Cargar categorías principales
    onMounted(async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/categorias-syscom');
        const data = await response.json();
        categorias.value = data.map(cat => ({ ...cat, subcategorias: [] }));
        loading.value = false;
      } catch (error) {
        console.error('Error al cargar categorías:', error);
      }
    });

    // Buscar productos
    const buscarProductos = () => {
      if (busqueda.value) {
        router.push({ path: '/productos', query: { busqueda: busqueda.value } });
      }
    };

    // Seleccionar categoría principal
    const selectCategory = async (categoria) => {
      selectedCategoryId.value = categoria.id;
      selectedCategory.value = categoria;
      selectedSubcategory.value = null;

      // Cargar subcategorías si no están cargadas
      if (!categoria.subcategorias || categoria.subcategorias.length === 0) {
        await fetchSubcategorias(categoria.id);
      }
    };

    // Seleccionar subcategoría
    const selectSubcategory = async (subcategoria) => {
      selectedSubcategory.value = subcategoria;

      // Cargar sub-subcategorías si no están cargadas
      if (!subcategoria.subcategorias) {
        await fetchSubcategorias(subcategoria.id, selectedCategory.value.id);
      }
    };

    // Fetch subcategorías (mantiene la lógica original)
    const fetchSubcategorias = async (id, parentId = null) => {
      try {
        const response = await fetch(`http://127.0.0.1:8000/categorias-syscom/${id}`);
        const data = await response.json();

        if (parentId) {
          const parent = categorias.value.find(cat =>
            cat.subcategorias?.some(sub => sub.id === parentId)
          );
          if (parent) {
            const sub = parent.subcategorias.find(s => s.id === id);
            if (sub) sub.subcategorias = data.subcategorias || [];
          }
        } else {
          const categoria = categorias.value.find(cat => cat.id === id);
          if (categoria) categoria.subcategorias = data.subcategorias || [];
        }
      } catch (err) {
        console.error('Error al cargar subcategorías:', err);
      }
    };

    const showSubcategories = () => {
      isSubcategoriesVisible.value = true;
    };

    const hideSubcategories = () => {
      isSubcategoriesVisible.value = false;
      selectedCategory.value = null;
      selectedSubcategory.value = null;
      selectedCategoryId.value = null;
    };

    return {
      isDarkMode,
      toggleTheme,
      categorias,
      busqueda,
      loading,
      buscarProductos,
      isSubcategoriesVisible,
      showSubcategories,
      hideSubcategories,
      fetchSubcategorias,
      selectedCategory,
      selectedSubcategory,
      selectedCategoryId,
      selectCategory,
      selectSubcategory
    };
  }
};
</script>

<style scoped>

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

.logo {
  font-size: 1.4rem;
  color: #4044ac;
  font-weight: bold;

  text-transform: uppercase;
  text-decoration: none;
}

.menu {
  list-style: none;
  display: flex;
  gap: 1.5rem;
  flex: 1;
  justify-content: center;
  
}

.menu li {
  position: relative;
}

.menu li a {
  text-decoration: none;
  color: black;
  font-size: 0.95rem;
}

.menu li a:hover {
  color: #66B2FF;
}

/* Megamenú estilo Steren */
.dropdown-content {
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  max-width: 800px;
  width: 80vw;
  background-color: white;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
  display: flex;
  z-index: 999;
  min-height: 450px;
  border-top: 3px solid #94c424;
  border-radius: 0 0 8px 8px;
}

/* Columna izquierda - Categorías principales */
.categories-sidebar {
  flex: 0 0 240px;
  background-color: #f8f9fa;
  border-right: 1px solid #e9ecef;
  padding: 0;
  overflow-y: auto;
  max-height: 450px;
}

.category-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 20px;
  cursor: pointer;
  border-bottom: 1px solid #e9ecef;
  transition: all 0.2s ease;
}

.category-item:hover,
.category-item.active {
  background-color: #4044ac;
  color: white;
}

.category-name {
  font-size: 0.9rem;
  font-weight: 500;
  text-transform: uppercase;
  flex-grow: 1;
}

.arrow-icon {
  font-size: 1.2rem;
  color: #94c424;
  font-weight: bold;
}

.category-item:hover .arrow-icon,
.category-item.active .arrow-icon {
  color: white;
}

/* Columna central - Subcategorías */
.subcategories-content {
  flex: 1;
  padding: 15px;
  border-right: 1px solid #e9ecef;
  background-color: white;
  overflow-y: auto;
  max-height: 450px;
}

.content-header {
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 2px solid #94c424;
}

.content-header h3 {
  margin: 0;
  color: #4044ac;
  font-size: 1.1rem;
  text-transform: uppercase;
  font-weight: 600;
}

.subcategories-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.subcategory-item {
  padding: 8px 12px;
  border-radius: 4px;
  transition: background-color 0.2s ease;
}

.subcategory-item:hover {
  background-color: #f8f9fa;
}

.subcategory-link {
  text-decoration: none;
  color: #2c5aa0;
  font-size: 0.9rem;
  display: block;
}

.subcategory-link:hover {
  color: #4044ac;
  font-weight: 500;
}

/* Columna derecha - Sub-subcategorías */
.sub-subcategories-content {
  flex: 1;
  padding: 15px;
  background-color: white;
  overflow-y: auto;
  max-height: 450px;
}

.content-header h4 {
  margin: 0;
  color: #4044ac;
  font-size: 1rem;
  font-weight: 600;
}

.sub-subcategories-list {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.sub-subcategory-item {
  padding: 6px 12px;
  border-radius: 4px;
  transition: background-color 0.2s ease;
}

.sub-subcategory-item:hover {
  background-color: #f8f9fa;
}

.sub-subcategory-link {
  text-decoration: none;
  color: #5a6c7d;
  font-size: 0.85rem;
  display: block;
}

.sub-subcategory-link:hover {
  color: #4044ac;
}

/* Resto de estilos originales */
.search-container input {
  padding: 0.5rem;
  font-size: 0.9rem;
  border-radius: 4px;
  border: 1px solid #ccc;
}

.search-container button {
  padding: 0.5rem 1rem;
  background-color: #4044ac;
  color: white;
  font-weight: bold;
  cursor: pointer;
  border: none;
  border-radius: 4px;
  margin-left: 5px;
}

.search-container button:hover {
  background-color: #333a8a;
}

.theme-switch button {
  background: transparent;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
}

.cart-icon {
  margin-left: 1rem;
}

.cart-img {
  width: 28px;
  height: 28px;
  cursor: pointer;
}

.content {
  margin-top: 80px;
}

/* Responsive */
@media (max-width: 1024px) {
  .dropdown-content {
    width: 75vw;
    max-width: 700px;
    min-height: 400px;
  }
  
  .categories-sidebar {
    flex: 0 0 200px;
  }
}

@media (max-width: 768px) {
  .dropdown-content {
    flex-direction: column;
    width: 90vw;
    max-width: 500px;
    min-height: auto;
    max-height: 70vh;
    overflow-y: auto;
  }
  
  .categories-sidebar,
  .subcategories-content,
  .sub-subcategories-content {
    flex: none;
    width: 100%;
    max-height: none;
  }
}
</style>
