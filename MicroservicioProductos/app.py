from flask import Flask
from dotenv import load_dotenv
import os

load_dotenv()

app = Flask(__name__)

from routes.productos import productos_bp
app.register_blueprint(productos_bp, url_prefix='/productos')

if __name__ == '__main__':
    port = int(os.getenv('FLASK_PORT', 5000))
    app.run(debug=True, port=port)