<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistema Multi-Servicio') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #4F46E5;
            --primary-dark: #4338CA;
            --secondary-color: #10B981;
            --text-dark: #1F2937;
            --text-light: #6B7280;
            --bg-light: #F9FAFB;
            --border-color: #E5E7EB;
        }

        body {
            font-family: 'Figtree', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 3rem;
            border-radius: 10px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .hero {
            background: white;
            border-radius: 20px;
            padding: 4rem 3rem;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            margin-bottom: 3rem;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .hero .subtitle {
            font-size: 1.25rem;
            color: var(--text-light);
            margin-bottom: 2rem;
        }

        .cta-button {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 1rem 3rem;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
        }

        .cta-button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(79, 70, 229, 0.5);
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }

        .feature-card p {
            color: var(--text-light);
            line-height: 1.6;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-light);
            font-size: 1rem;
        }

        footer {
            text-align: center;
            padding: 2rem;
            color: white;
            margin-top: 3rem;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .features {
                grid-template-columns: 1fr;
            }

            .stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-content">
                <div class="logo">{{ config('app.name', 'Sistema Multi-Servicio') }}</div>
            </div>
        </header>

        <div class="hero">
            <h1>Sistema de Gestión Multi-Servicio</h1>
            <p class="subtitle">Plataforma integral para la gestión de múltiples sistemas y servicios empresariales</p>
            <a target="_blank" href="http://localhost:8080/login" class="cta-button">Acceder al Sistema</a>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-number">API REST</div>
                <div class="stat-label">Laravel 12</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">JWT</div>
                <div class="stat-label">Autenticación Segura</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">Multi-Sistema</div>
                <div class="stat-label">Gestión Centralizada</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">Vue 3</div>
                <div class="stat-label">Frontend Moderno</div>
            </div>
        </div>

        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">🔐</div>
                <h3>Autenticación JWT</h3>
                <p>Sistema de autenticación seguro basado en JSON Web Tokens para proteger tus datos y garantizar acceso controlado.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3>Gestión de Sistemas</h3>
                <p>Administra múltiples tipos de sistemas desde una sola plataforma. Configura módulos y permisos de manera flexible.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">👥</div>
                <h3>Control de Accesos</h3>
                <p>Gestión granular de accesos de usuarios a diferentes sistemas y módulos según roles y permisos personalizados.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">⚙️</div>
                <h3>Configuración Dinámica</h3>
                <p>Sistema de configuraciones públicas y privadas por tipo de sistema, adaptable a las necesidades de tu organización.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Gestión de Recursos</h3>
                <p>Administra productos, clientes, sucursales y otros recursos empresariales de forma centralizada y eficiente.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🚀</div>
                <h3>API REST Completa</h3>
                <p>API RESTful robusta con validaciones mediante FormRequest, documentada y lista para integrar con cualquier frontend.</p>
            </div>
        </div>

        <footer>
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Sistema Multi-Servicio') }}. Desarrollado con Laravel 12 y Vue 3.</p>
        </footer>
    </div>
</body>
</html>
