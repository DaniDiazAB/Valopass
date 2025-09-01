// proxy.js
const express = require('express');
const cors = require('cors');
const { createProxyMiddleware } = require('http-proxy-middleware');

const app = express();

// Permitir CORS desde tu frontend (ajusta el origin si es necesario)
app.use(cors({
  origin: '*', // ⚠️ para pruebas; en producción pon tu dominio
}));

/**
 * Proxy:
 * Todas las llamadas a /api/... se reenvían a la API real
 */
app.use('/api', createProxyMiddleware({
  target: 'https://vaccie.pythonanywhere.com', // API real
  changeOrigin: true,
  pathRewrite: { '^/api': '' }, // quita "/api" antes de enviar
}));

// Inicia el servidor proxy
const PORT = 3000;
app.listen(PORT, () => {
  console.log(`CORS proxy funcionando en http://localhost:${PORT}`);
  console.log(`Ejemplo: http://localhost:${PORT}/api/mmr/Dani/KKC/EU`);
});
