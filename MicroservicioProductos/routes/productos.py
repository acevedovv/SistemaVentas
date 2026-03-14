from flask import Blueprint, jsonify, request
from services.firebase_service import db

productos_bp = Blueprint('productos', __name__)

@productos_bp.route('/', methods=['GET'])
def get_productos():
    productos = []
    docs = db.collection('productos').stream()
    for doc in docs:
        producto = doc.to_dict()
        producto['id'] = doc.id
        productos.append(producto)
    return jsonify(productos)

@productos_bp.route('/<id>', methods=['GET'])
def get_producto(id):
    doc = db.collection('productos').document(id).get()
    if not doc.exists:
        return jsonify({'error': 'Producto no encontrado'}), 404
    producto = doc.to_dict()
    producto['id'] = doc.id
    return jsonify(producto)

@productos_bp.route('/', methods=['POST'])
def create_producto():
    data = request.get_json()
    ref = db.collection('productos').add(data)
    return jsonify({'id': ref[1].id, 'mensaje': 'Producto creado'}), 201

@productos_bp.route('/verificar-stock/<id>', methods=['GET'])
def verificar_stock(id):
    cantidad = int(request.args.get('cantidad', 1))
    doc = db.collection('productos').document(id).get()
    if not doc.exists:
        return jsonify({'disponible': False, 'error': 'Producto no encontrado'}), 404
    producto = doc.to_dict()
    disponible = producto.get('stock', 0) >= cantidad
    return jsonify({'disponible': disponible, 'stock': producto.get('stock', 0)})

@productos_bp.route('/actualizar-stock/<id>', methods=['PUT'])
def actualizar_stock(id):
    data = request.get_json()
    cantidad = data.get('cantidad', 0)
    doc_ref = db.collection('productos').document(id)
    doc = doc_ref.get()
    if not doc.exists:
        return jsonify({'error': 'Producto no encontrado'}), 404
    stock_actual = doc.to_dict().get('stock', 0)
    nuevo_stock = stock_actual - cantidad
    if nuevo_stock < 0:
        return jsonify({'error': 'Stock insuficiente'}), 400
    doc_ref.update({'stock': nuevo_stock})
    return jsonify({'mensaje': 'Stock actualizado', 'stock': nuevo_stock})