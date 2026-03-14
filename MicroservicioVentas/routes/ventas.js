const express = require('express');
const router = express.Router();
const Venta = require('../models/Venta');

router.get('/', async (req, res) => {
    try {
        const ventas = await Venta.find();
        res.json(ventas);
    } catch (error) {
        res.status(500).json({ error: 'Error al obtener ventas' });
    }
});

router.get('/usuario/:usuario_id', async (req, res) => {
    try {
        const ventas = await Venta.find({ usuario_id: req.params.usuario_id });
        res.json(ventas);
    } catch (error) {
        res.status(500).json({ error: 'Error al obtener ventas' });
    }
});

router.get('/fecha/:fecha', async (req, res) => {
    try {
        const fecha = new Date(req.params.fecha);
        const fechaSiguiente = new Date(fecha);
        fechaSiguiente.setDate(fechaSiguiente.getDate() + 1);
        const ventas = await Venta.find({
            fecha: { $gte: fecha, $lt: fechaSiguiente }
        });
        res.json(ventas);
    } catch (error) {
        res.status(500).json({ error: 'Error al obtener ventas' });
    }
});

router.post('/', async (req, res) => {
    try {
        const venta = new Venta(req.body);
        await venta.save();
        res.status(201).json({ id: venta._id, mensaje: 'Venta registrada' });
    } catch (error) {
        res.status(500).json({ error: 'Error al registrar venta' });
    }
});

module.exports = router;