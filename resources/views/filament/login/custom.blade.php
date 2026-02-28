<style>
    /* ================================
       1. Layout y Fondos (Overlay)
    ================================= */
    
    /* Ajuste del logo */
    .fi-simple-header .fi-logo {
        height: 3.5rem !important;
        margin-bottom: 0.5rem;
    }

    /* Overlay general para dar profundidad */
    .fi-simple-layout {
        position: relative;
    }

    /* Modo oscuro - Fondo */
    .dark .fi-simple-layout::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.7); /* slate-900 con opacidad */
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 0;
    }

    /* Modo claro - Fondo */
    .fi-simple-layout::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(248, 250, 252, 0.6); /* slate-50 con opacidad */
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 0;
    }

    /* Asegurar que el contenido del login esté encima del overlay */
    .fi-simple-main {
        position: relative;
        z-index: 1;
        animation: fadeInLogin 0.6s ease-out forwards;
    }

    /* ================================
       2. Card Login (Glass Corporativo)
    ================================= */

    /* Modo oscuro - Tarjeta */
    .dark .fi-simple-main {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.85), rgba(15, 23, 42, 0.9)) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        /* Verde corporativo (green-600: rgb(22, 163, 74)) */
        border: 1px solid rgba(22, 163, 74, 0.3) !important;
        border-radius: 16px;
        box-shadow:
            0 0 30px rgba(22, 163, 74, 0.1),
            0 20px 40px rgba(0, 0, 0, 0.5),
            inset 0 0 10px rgba(22, 163, 74, 0.05) !important;
    }

    /* Modo claro - Tarjeta */
    .fi-simple-main {
        background-color: rgba(255, 255, 255, 0.85) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(22, 163, 74, 0.15) !important;
        border-radius: 16px;
        box-shadow:
            0 10px 30px rgba(0, 0, 0, 0.05),
            0 0 20px rgba(22, 163, 74, 0.05) !important;
    }

    /* ================================
       3. Títulos y Textos
    ================================= */

    .fi-simple-header h1 {
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 700;
    }

    .dark .fi-simple-header h1 {
        color: #ffffff !important;
    }

    .fi-simple-header h1 {
        color: #1e293b !important; /* slate-800 */
    }

    /* ================================
       4. Inputs y Botón Principal
    ================================= */

    /* Inputs Modo Oscuro */
    .dark .fi-input {
        background-color: rgba(15, 23, 42, 0.5) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #ffffff !important;
        transition: all 0.3s ease;
    }

    .dark .fi-input:focus {
        border-color: #16a34a !important; /* Verde corporativo */
        box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.2) !important;
    }

    /* Inputs Modo Claro */
    .fi-input:focus {
        border-color: #16a34a !important;
        box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.15) !important;
    }

    /* Botón Principal (Primary) */
    .fi-btn-color-primary {
        background-color: #16a34a !important; /* Forzar verde institucional */
        transition: all 0.3s ease !important;
        border-radius: 8px !important;
        font-weight: 600;
    }

    .fi-btn-color-primary:hover {
        background-color: #15803d !important; /* Un verde un poco más oscuro al pasar el mouse */
        transform: translateY(-1px);
    }

    .dark .fi-btn-color-primary {
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25) !important;
    }

    .dark .fi-btn-color-primary:hover {
        box-shadow: 0 6px 16px rgba(22, 163, 74, 0.4) !important;
    }

    /* ================================
       5. Botón Volver (Back to Portal)
    ================================= */

    .btn-volver-inicio {
        position: fixed;
        top: 1.5rem;
        left: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.2rem;
        border-radius: 999px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        z-index: 50;
    }

    /* Dark Mode - Botón Volver */
    .dark .btn-volver-inicio {
        background: rgba(30, 41, 59, 0.7);
        color: #cbd5e1;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Light Mode - Botón Volver */
    .btn-volver-inicio {
        background: rgba(255, 255, 255, 0.8);
        color: #475569;
        border: 1px solid rgba(0, 0, 0, 0.1);
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .btn-volver-inicio:hover {
        color: #16a34a; /* Hover en verde corporativo */
        border-color: #16a34a;
        transform: translateX(-4px);
    }

    .btn-volver-inicio svg {
        width: 1.1rem;
        height: 1.1rem;
        transition: transform 0.3s ease;
    }

    .btn-volver-inicio:hover svg {
        transform: translateX(-2px);
    }

    /* ================================
       6. Animaciones
    ================================= */
    @keyframes fadeInLogin {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<a href="/" class="btn-volver-inicio">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
    </svg>
    Portal
</a>