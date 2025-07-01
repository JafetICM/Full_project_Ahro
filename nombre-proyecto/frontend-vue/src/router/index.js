// C:\Users\dell\nombre-proyecto\frontend-vue/src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import InicioSYSCOM from '../components/InicioSYSCOM.vue';
import CategoriaList from '../components/Categoria.vue';
import CategoriaDetail from '../components/CategoriaDetail.vue';
import Carrito from '../components/Carrito.vue';
import MarcaList from '../components/Marcas.vue';
import MarcaDetail from '../components/MarcaDetail.vue';
import ProductoDetail from '../components/ProductoDetail.vue';
import ProductoList from '../components/Productos.vue';
import ProductoForm from '../components/ProductSearchForm.vue';

const routes = [
  {
    path: '/',
    component: InicioSYSCOM,
  },
  {
    path: '/categorias',
    component: CategoriaList,
    name: 'categorias',
  },
  {
    path: '/categorias/:id',  // Ruta para los detalles de la categoría
    component: CategoriaDetail,
    name: 'CategoriaDetail',
  },
  {
    path: '/carrito',
    component: Carrito,
    name: 'Carrito',
  },
  {
    path: '/marcas',
    component: MarcaList,
  },
  {
    path: '/marcas/:id',  // Ruta para los detalles de la marca
    component: MarcaDetail,
    name: 'MarcaDetail',
  },
  {
    path: '/productos/:id',  // Ruta para los detalles del producto
    component: ProductoDetail,
    name: 'ProductoDetail',
  },
  {
    path: '/productos',
    component: ProductoList,
    name: 'ProductoList',
  },
  {
    path: '/productos/form',
    component: ProductoForm,
    name: 'ProductoForm',  // Esta ruta es para crear nuevos productos
  },
  {
    path: '/productos/:id/edit',
    component: ProductoForm,
    name: 'ProductoFormEdit',  // Esta ruta es para editar productos existentes
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
