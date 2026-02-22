<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Operación Sistémica</title>
    @vite('resources/js/app.ts')
</head>

<body class="min-h-screen bg-slate-950 text-white flex items-center justify-center relative overflow-hidden">

    <!-- Fondo con gradiente glow -->
    <div class="absolute inset-0">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-green-500/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-emerald-400/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Contenedor principal -->
    <div class="relative z-10 text-center max-w-xl px-6">

        <!-- Logo / Marca -->
        <h1 class="text-5xl font-bold tracking-wide mb-4">
            <span class="text-white">operación</span>
            <span class="text-green-500"> sistémica</span>
        </h1>

        <p class="text-slate-400 mb-10 text-sm tracking-widest uppercase">
            Centro de Control Administrativo
        </p>

        <!-- Card Glass -->
        <div class="bg-slate-900/60 backdrop-blur-xl border border-white/10 
                    rounded-2xl p-10 shadow-2xl">

            <div class="flex flex-col sm:flex-row gap-6 justify-center">

                <!-- Panel Principal -->
                <a href="{{ route('login') }}"
                   class="group px-8 py-4 rounded-xl bg-gradient-to-r from-green-500 to-emerald-500
                          text-slate-900 font-semibold transition duration-300
                          hover:scale-105 hover:shadow-[0_0_30px_rgba(34,197,94,0.5)]">

                    Ingresar al Panel
                </a>

                <!-- Panel Admin -->
                <a href="{{ url('/admin/login') }}"
                   class="px-8 py-4 rounded-xl border border-green-500/40 
                          text-green-400 font-semibold transition duration-300
                          hover:bg-green-500 hover:text-slate-900
                          hover:shadow-[0_0_25px_rgba(34,197,94,0.4)]">

                    Terminal Admin
                </a>

            </div>

        </div>

        <!-- Footer técnico -->
        <div class="mt-8 text-xs text-slate-500 tracking-wider">
            Sistema Interno · Acceso Restringido
        </div>

    </div>

</body>
</html>