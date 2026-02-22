<style>
    /* Solo afecta la pantalla de login */
    .fi-simple-header .fi-logo {
        height: 3rem !important;
    }



    /* Overlay modo oscuro */
    .dark .fi-simple-layout {
        position: relative;
    }

    .dark .fi-simple-layout::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(9, 9, 11, 0.65);
        backdrop-filter: blur(2px);
        z-index: 0;
    }

    /* Overlay modo claro */
    .fi-simple-layout::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.55);
        z-index: 0;
    }

    /* Asegurar que el contenido esté encima */
    .fi-simple-main {
        position: relative;
        z-index: 1;
    }

    /* ================================
       2. Card Login (Glass moderno)
    ================================= */

    /* Modo oscuro */
    .dark .fi-simple-main {
        background-color: rgba(15, 23, 42, 0.75) !important;
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(0, 230, 118, 0.35) !important;
        border-radius: 14px;
        box-shadow:
            0 0 25px rgba(0, 230, 118, 0.15),
            inset 0 0 10px rgba(0, 230, 118, 0.08) !important;
    }

    .dark .fi-simple-main {
        background: linear-gradient(135deg,
                rgba(15, 23, 42, 0.85),
                rgba(30, 41, 59, 0.75)) !important;

        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);

        border: 1px solid rgba(0, 230, 118, 0.25) !important;

        box-shadow:
            0 0 40px rgba(0, 230, 118, 0.12),
            0 20px 60px rgba(0, 0, 0, 0.6);
    }

    /* Modo claro */
    .fi-simple-main {
        background-color: rgba(255, 255, 255, 0.75) !important;
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        border-radius: 14px;
        box-shadow:
            0 10px 30px rgba(0, 0, 0, 0.08) !important;
    }

    /* ================================
       3. Títulos
    ================================= */

    .fi-simple-header h1 {
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .dark .fi-simple-header h1 {
        color: #ffffff !important;
    }

    .fi-simple-header h1 {
        color: #111827 !important;
    }

    /* ================================
       4. Botón principal más elegante
    ================================= */
    .dark .fi-input {
        background-color: rgba(30, 41, 59, 0.6) !important;
        border: 1px solid rgba(255, 255, 255, 0.06) !important;
        color: #ffffff !important;
    }

    .dark .fi-input:focus {
        border-color: #00e676 !important;
        box-shadow: 0 0 0 2px rgba(0, 230, 118, 0.25) !important;
    }

    .fi-btn-color-primary {
        transition: all 0.3s ease;
        border-radius: 10px !important;
    }

    .dark .fi-btn-color-primary {
        box-shadow: 0 0 12px rgba(0, 230, 118, 0.25) !important;
    }

    .dark .fi-btn-color-primary:hover {
        box-shadow: 0 0 20px rgba(0, 230, 118, 0.45) !important;
    }

    .fi-btn-color-primary:hover {
        transform: translateY(-2px);
    }

    /* ================================
   5. Botón volver mejorado
   ================================ */

    .btn-volver-inicio {
        position: fixed;
        top: 1rem;
        left: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1rem;
        border-radius: 999px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        z-index: 50;
    }

    /* Dark */
    .dark .btn-volver-inicio {
        background: rgba(15, 23, 42, 0.6);
        color: #a1a1aa;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* Light */
    .btn-volver-inicio {
        background: rgba(255, 255, 255, 0.6);
        color: #374151;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .btn-volver-inicio:hover {
        color: #00e676;
        transform: translateX(-4px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }

    .btn-volver-inicio svg {
        width: 1rem;
        height: 1rem;
    }

    .fi-simple-main {
        animation: fadeInLogin 0.6s ease forwards;
    }

    @keyframes fadeInLogin {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
<a href="/" class="btn-volver-inicio">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
    </svg>
    Volver
</a>
