const express = require('express');
const mongoose = require('mongoose');
const dotenv = require('dotenv');

dotenv.config();

const app = express();
app.use(express.json());

mongoose.connect(process.env.MONGODB_URI)
    .then(() => console.log('Conectado a MongoDB'))
    .catch(err => console.error('Error conectando a MongoDB:', err));

const ventasRouter = require('./routes/ventas');
app.use('/ventas', ventasRouter);

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Microservicio de ventas corriendo en puerto ${PORT}`);
});