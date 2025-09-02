const express = require('express');
const cors = require('cors');
const { createProxyMiddleware } = require('http-proxy-middleware');

const app = express();

app.use(cors({
  origin: '*', 
}));

app.use('/api', createProxyMiddleware({
  target: 'https://vaccie.pythonanywhere.com',
  changeOrigin: true,
  pathRewrite: { '^/api': '' }, 
}));

const PORT = 3000;
app.listen(PORT, () => {
  console.log(`CORS proxy funcionando en http://localhost:${PORT}`);
  console.log(`Ejemplo: http://localhost:${PORT}/api/mmr/Dani/KKC/EU`);
});
