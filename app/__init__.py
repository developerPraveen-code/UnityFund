from flask import Flask
from werkzeug.middleware.proxy_fix import ProxyFix

from .config import Config
from .routes import bp


def create_app() -> Flask:
    app = Flask(__name__)
    app.config.from_object(Config())

    # F5 terminates TLS and forwards the original scheme/host. ProxyFix lets
    # Flask generate HTTPS URLs while still restricting identity trust in code.
    app.wsgi_app = ProxyFix(app.wsgi_app, x_for=1, x_proto=1, x_host=1)

    app.register_blueprint(bp)

    @app.after_request
    def add_security_headers(response):
        response.headers.setdefault("X-Content-Type-Options", "nosniff")
        response.headers.setdefault("X-Frame-Options", "DENY")
        response.headers.setdefault("Referrer-Policy", "no-referrer")
        response.headers.setdefault(
            "Content-Security-Policy",
            "default-src 'self'; style-src 'self'; frame-ancestors 'none';",
        )
        return response

    return app
