<template>
  <div class="carrito-container">
    <h1>Generar Orden</h1>

    <!-- Mostrar mensaje de error si ocurre alguno -->
    <div v-if="error" class="error-message">
      <p>{{ error }}</p>
    </div>

    <!-- Formulario para generar orden -->
    <form @submit.prevent="generarOrden">
      <div class="form-group">
        <label for="tipo_entrega">Tipo de entrega</label>
        <select v-model="orden.tipo_entrega" id="tipo_entrega" required>
          <option value="domicilio">Domicilio</option>
          <option value="sucursal">Sucursal</option>
        </select>
      </div>

      <div class="form-group">
        <label for="direccion">Dirección de entrega</label>
        <input v-model="orden.direccion" type="text" id="direccion" required placeholder="Dirección de entrega" />
      </div>

      <div class="form-group">
        <label for="metodo_pago">Método de pago</label>
        <input v-model="orden.metodo_pago" type="text" id="metodo_pago" required placeholder="ID del método de pago" />
      </div>

      <div class="form-group">
        <label for="productos">Productos</label>
        <textarea v-model="orden.productos" id="productos" required placeholder="Ingrese los productos en formato JSON"></textarea>
      </div>

      <div class="form-group">
        <label for="moneda">Moneda</label>
        <select v-model="orden.moneda" id="moneda" required>
          <option value="mxn">MXN</option>
          <option value="usd">USD</option>
        </select>
      </div>

      <div class="form-group">
        <label for="uso_cfdi">Uso CFDI</label>
        <input v-model="orden.uso_cfdi" type="text" id="uso_cfdi" required placeholder="ID del uso de CFDI" />
      </div>

      <!-- Resto de los parámetros -->
      <div class="form-group">
        <label for="tipo_pago">Tipo de pago</label>
        <select v-model="orden.tipo_pago">
          <option value="pue">PUE</option>
          <option value="ppd">PPD</option>
        </select>
      </div>

      <div class="form-group">
        <label for="fletera">Fletera</label>
        <input v-model="orden.fletera" type="text" id="fletera" placeholder="ID de la fletera" />
      </div>

      <div class="form-group">
        <label for="orden_compra">Orden de compra</label>
        <input v-model="orden.orden_compra" type="text" id="orden_compra" placeholder="Folio de orden de compra" />
      </div>

      <div class="form-group">
        <label for="ordenar">Ordenar</label>
        <select v-model="orden.ordenar">
          <option value="false">Cotización</option>
          <option value="true">Orden</option>
        </select>
      </div>

      <!-- Enviar -->
      <button type="submit">Generar Orden</button>
    </form>

    <!-- Mostrar el estado de carga -->
    <div v-if="loading" class="loading-message">
      <p>Generando orden...</p>
    </div>

    <!-- Mostrar el resultado -->
    <div v-if="result">
      <h2>Resultado de la Orden</h2>
      <pre>{{ result }}</pre>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';

export default {
  name: 'CarritoCompra',
  setup() {
    const orden = ref({
      tipo_entrega: 'domicilio',
      direccion: '',
      metodo_pago: '',
      productos: '',
      moneda: 'mxn',
      uso_cfdi: '',
      tipo_pago: 'pue',
      fletera: '',
      orden_compra: '',
      ordenar: false,
    });

    const loading = ref(false);
    const error = ref(null);
    const result = ref(null);

    const generarOrden = async () => {
      loading.value = true;
      error.value = null;
      result.value = null;

      // Validar los campos antes de enviar
      if (!orden.value.direccion || !orden.value.metodo_pago || !orden.value.productos) {
        error.value = "Todos los campos obligatorios deben ser completados.";
        return;
      }

      // Asegurarse de que los productos estén en formato JSON
      let productosParsed;
      try {
        productosParsed = JSON.parse(orden.value.productos);
      } catch (err) {
        error.value = "Formato de productos inválido. Asegúrate de usar JSON válido.";
        console.error("Error al parsear productos:", err);
        return; // Evita enviar la solicitud si el formato es incorrecto
      }

      // Crear objeto de la orden
      const orderData = {
        tipo_entrega: orden.value.tipo_entrega,
        direccion: orden.value.direccion,
        metodo_pago: orden.value.metodo_pago,
        productos: productosParsed,  // Enviar productos parseados
        moneda: orden.value.moneda,
        uso_cfdi: orden.value.uso_cfdi,
        tipo_pago: orden.value.tipo_pago,
        fletera: orden.value.fletera,
        orden_compra: orden.value.orden_compra,
        ordenar: orden.value.ordenar,
      };

      try {
        // Hacer el POST a la API para generar la orden
        const response = await fetch('http://127.0.0.1:8000/carrito/generar', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
          body: JSON.stringify(orderData),
        });

        if (!response.ok) {
          throw new Error('Error al generar la orden');
        }

        result.value = await response.json();  // Mostrar el resultado de la orden
      } catch (err) {
        error.value = `Hubo un error: ${err.message}`;
        console.error(err);
      } finally {
        loading.value = false;
      }
    };

    return {
      orden,
      loading,
      error,
      result,
      generarOrden,
    };
  },
};
</script>

<style scoped>
/* Estilo del formulario de carrito */
.carrito-container {
  padding: 2rem;
}

.form-group {
  margin-bottom: 1rem;
}

label {
  font-size: 1.1rem;
  margin-bottom: 0.5rem;
}

input, select, textarea {
  width: 100%;
  padding: 0.8rem;
  margin-bottom: 0.5rem;
  border-radius: 4px;
  border: 1px solid #ccc;
}

button {
  background-color: #94c424;
  color: white;
  padding: 1rem 2rem;
  border: none;
  border-radius: 30px;
  text-transform: uppercase;
  font-weight: bold;
  cursor: pointer;
}

button:hover {
  background-color: #66b2ff;
}

.loading-message {
  font-size: 1.5rem;
  color: #94c424;
}

.error-message {
  color: red;
  font-weight: bold;
}

.result {
  padding: 1rem;
  background-color: #f9f9f9;
  border-radius: 8px;
}
</style>
