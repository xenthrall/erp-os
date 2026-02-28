<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { dashboard, login, register } from '@/routes'

withDefaults(
    defineProps<{
        canRegister: boolean
    }>(),
    {
        canRegister: true,
    },
)

const year = new Date().getFullYear()
</script>

<template>
    <Head title="Operación Sistémica" />

    <div
        class="min-h-screen flex flex-col
               bg-white text-slate-900
               dark:bg-slate-950 dark:text-slate-100
               transition-colors duration-500"
    >

        <!-- NAVBAR -->
        <header
            class="relative z-20 border-b
                   border-slate-200 dark:border-slate-800"
        >
            <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">

                <!-- Logo / Marca -->
                <div class="text-lg font-semibold tracking-wide">
                    <span>operación</span>
                    <span class="text-green-600 dark:text-green-400">
                        sistémica
                    </span>
                </div>

                <!-- Navegación -->
                <div class="flex items-center gap-4 text-sm">

                    <template v-if="$page.props.auth.user">
                        <Link
                            :href="dashboard()"
                            class="px-4 py-2 rounded-lg
                                   bg-green-600 text-white
                                   hover:bg-green-700
                                   transition"
                        >
                            Ir al Dashboard
                        </Link>
                    </template>

                    <template v-else>
                        <Link
                            :href="login()"
                            class="px-4 py-2 rounded-lg
                                   hover:bg-slate-100
                                   dark:hover:bg-slate-800
                                   transition"
                        >
                            Iniciar sesión
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="register()"
                            class="px-4 py-2 rounded-lg border
                                   border-green-600
                                   text-green-600
                                   dark:text-green-400
                                   hover:bg-green-600 hover:text-white
                                   transition"
                        >
                            Registrarse
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- HERO -->
        <main class="flex-1 flex items-center">
            <div class="max-w-7xl mx-auto px-6 py-20 w-full">

                <div class="grid lg:grid-cols-2 gap-16 items-center">

                    <!-- Texto principal -->
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-6">
                            Plataforma Integral de Gestión Empresarial
                        </h1>

                        <p class="text-lg text-slate-600 dark:text-slate-400 mb-8">
                            Centraliza la gestión de garantías, recursos humanos
                            y procesos administrativos en un solo sistema
                            robusto, seguro y escalable.
                        </p>

                        <div class="flex flex-wrap gap-4">
                            <Link
                                :href="login()"
                                class="px-6 py-3 rounded-lg
                                       bg-green-600 text-white
                                       hover:bg-green-700
                                       shadow-lg shadow-green-600/20
                                       transition"
                            >
                                Portal de Clientes
                            </Link>

                            <a
                                href="/erp/login"
                                class="px-6 py-3 rounded-lg border
                                       border-slate-300 dark:border-slate-700
                                       hover:bg-slate-100
                                       dark:hover:bg-slate-800
                                       transition"
                            >
                                Acceso ERP Empresarial
                            </a>
                        </div>
                    </div>

                    <!-- Panel visual / Tarjetas -->
                    <div class="grid gap-6">

                        <div
                            class="p-6 rounded-2xl border
                                   border-slate-200 dark:border-slate-800
                                   bg-slate-50 dark:bg-slate-900
                                   shadow-sm"
                        >
                            <h3 class="font-semibold mb-2 text-green-600 dark:text-green-400">
                                Gestión de Garantías
                            </h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                Seguimiento en tiempo real del estado de solicitudes
                                y control completo del flujo operativo.
                            </p>
                        </div>

                        <div
                            class="p-6 rounded-2xl border
                                   border-slate-200 dark:border-slate-800
                                   bg-slate-50 dark:bg-slate-900
                                   shadow-sm"
                        >
                            <h3 class="font-semibold mb-2 text-green-600 dark:text-green-400">
                                Recursos Humanos
                            </h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                Administración de personal, control documental
                                y estructura organizacional integrada.
                            </p>
                        </div>

                        <div
                            class="p-6 rounded-2xl border
                                   border-slate-200 dark:border-slate-800
                                   bg-slate-50 dark:bg-slate-900
                                   shadow-sm"
                        >
                            <h3 class="font-semibold mb-2 text-green-600 dark:text-green-400">
                                Control Administrativo
                            </h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                Procesos internos optimizados para mejorar
                                la eficiencia y la trazabilidad empresarial.
                            </p>
                        </div>

                    </div>

                </div>
            </div>
        </main>

        <!-- FOOTER -->
        <footer
            class="border-t border-slate-200 dark:border-slate-800
                   py-6 text-center text-sm
                   text-slate-500 dark:text-slate-400"
        >
            © {{ year }} Operación Sistémica · Plataforma Corporativa
        </footer>

    </div>
</template>