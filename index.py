from app import create_app

# Vercel looks for a WSGI variable named `app` in supported entrypoints.
app = create_app()
