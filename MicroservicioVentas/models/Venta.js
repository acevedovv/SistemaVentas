const mongoose = require('mongoose');

const ventaSchema = new mongoose.Schema({
    usuario_id: { type: Number, required: true },
    producto_id: { type: String, required: true },
    nombre_producto: { type: String, required: true },
    cantidad: { type: Number, required: true },
    precio_unitario: { type: Number, required: true },
    total: { type: Number, required: true },
    fecha: { type: Date, default: Date.now }
});

module.exports = mongoose.model('Venta', ventaSchema);