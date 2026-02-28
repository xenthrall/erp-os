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
    <Head title="Portal - Operación Sistémica" />

    <div
        class="min-h-screen flex flex-col bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-500"
    >
        <header class="relative z-20 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800">
            <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
                <div class="text-lg font-semibold tracking-wide">
                    <span>operación</span>
                    <span class="text-green-600 dark:text-green-400">
                        sistémica
                    </span>
                </div>

                <div class="flex items-center gap-4 text-sm">
                    <template v-if="$page.props.auth.user">
                        <Link
                            :href="dashboard()"
                            class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition"
                        >
                            Ir al Panel
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <main class="flex-1 flex items-center justify-center py-12">
            <div class="max-w-5xl mx-auto px-6 w-full">
                
                <div class="text-center mb-12">
                    <h1 class="text-3xl md:text-4xl font-bold mb-4 text-slate-800 dark:text-white">
                        Portal Corporativo y de Servicio
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                        Selecciona tu perfil para ingresar al sistema. Desde aquí puedes gestionar tus solicitudes de garantía o acceder al panel operativo de la empresa.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                    
                    <div class="bg-white dark:bg-slate-900 rounded-2xl p-8 border-2 border-transparent hover:border-green-500 dark:hover:border-green-500 shadow-sm hover:shadow-md transition-all flex flex-col h-full">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-semibold mb-2">Centro de Garantías</h2>
                        <p class="text-slate-600 dark:text-slate-400 mb-8 flex-1">
                            Acceso exclusivo para clientes y distribuidores. Ingresa para radicar nuevas solicitudes de garantía, subir evidencias y consultar el estado de tus envíos.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-3">
                            <Link
                                :href="login()"
                                class="flex-1 text-center px-4 py-2.5 rounded-lg bg-green-600 text-white font-medium hover:bg-green-700 transition"
                            >
                                Iniciar Sesión
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="register()"
                                class="flex-1 text-center px-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition"
                            >
                                Registrarse
                            </Link>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 rounded-2xl p-8 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-all flex flex-col h-full">
                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-semibold mb-2">Equipo Interno</h2>
                        <p class="text-slate-600 dark:text-slate-400 mb-8 flex-1">
                            Acceso corporativo para personal de bodega, asesores y administración. Ingresa para gestionar operaciones, reportes ER y despachos.
                        </p>
                        
                        <div class="flex flex-col gap-3">
                            <a
                                href="/erp/login"
                                class="w-full text-center px-4 py-2.5 rounded-lg bg-slate-800 dark:bg-slate-100 text-white dark:text-slate-900 font-medium hover:bg-slate-900 dark:hover:bg-white transition"
                            >
                                Acceso Operativo
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </main>

        <footer class="py-6 text-center text-sm text-slate-500 dark:text-slate-400 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            © {{ year }} Operación Sistémica · Sistema de Gestión Interna
        </footer>

    </div>
</template>