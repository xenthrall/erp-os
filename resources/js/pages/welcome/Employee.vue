<script setup lang="ts">
import { Head } from '@inertiajs/vue3'

const year = new Date().getFullYear()
</script>

<template>
    <Head title="Inducción ERP - Operación Sistémica" />

    <div
        class="min-h-screen flex flex-col bg-slate-50 text-slate-900 dark:bg-zinc-950 dark:text-zinc-100 transition-colors duration-500"
    >
        <header class="relative z-20 bg-white dark:bg-zinc-950 border-b border-slate-200 dark:border-zinc-800">
            <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
                <div class="text-lg font-semibold tracking-wide">
                    <span class="dark:text-white">operación</span>
                    <span class="text-green-600 dark:text-green-400">
                        sistémica
                    </span>
                </div>
                <div class="text-sm font-medium text-slate-500 dark:text-zinc-400">
                    Portal de Equipo
                </div>
            </div>
        </header>

        <main class="flex-1 py-12 px-6">
            <div class="max-w-6xl mx-auto">
                
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <div class="inline-flex items-center justify-center px-4 py-1.5 mb-6 rounded-full bg-green-100 dark:bg-zinc-900 border border-green-200 dark:border-zinc-800 text-green-700 dark:text-green-400 text-sm font-medium">
                        Guía de Introducción al Sistema
                    </div>
                    <h1 class="text-3xl md:text-5xl font-bold mb-6 text-slate-800 dark:text-white leading-tight">
                        ¿Qué es nuestro nuevo <br>
                        <span class="text-green-600 dark:text-green-400">sistema ERP?</span>
                    </h1>
                    <p class="text-slate-600 dark:text-zinc-400 text-lg">
                        Un ERP (Planificación de Recursos Empresariales) es una herramienta de trabajo interna. Su objetivo es conectar áreas específicas, como atención al cliente y bodega, para que el flujo de garantías y despachos sea mucho más organizado, documentado y fácil de gestionar en tu día a día.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
                    
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm">
                        <div class="w-12 h-12 bg-blue-50 dark:bg-zinc-800 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center mb-4 border border-blue-100 dark:border-zinc-700">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold mb-2 dark:text-white">Si eres Asesor</h3>
                        <p class="text-sm text-slate-600 dark:text-zinc-400">
                            En este módulo recibirás las solicitudes de garantía que los clientes envían desde la página web. Tu labor aquí será revisar la información, validar las evidencias y aprobar o rechazar el caso.
                        </p>
                    </div>

                    <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm">
                        <div class="w-12 h-12 bg-amber-50 dark:bg-zinc-800 text-amber-600 dark:text-amber-400 rounded-xl flex items-center justify-center mb-4 border border-amber-100 dark:border-zinc-700">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold mb-2 dark:text-white">Si estás en Bodega</h3>
                        <p class="text-sm text-slate-600 dark:text-zinc-400">
                            El sistema te notificará cuando una garantía sea aprobada. Aquí podrás confirmar que los reemplazos (como las barras LED) ya fueron debidamente alistados y despachados al cliente.
                        </p>
                    </div>

                    <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm">
                        <div class="w-12 h-12 bg-rose-50 dark:bg-zinc-800 text-rose-600 dark:text-rose-400 rounded-xl flex items-center justify-center mb-4 border border-rose-100 dark:border-zinc-700">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold mb-2 dark:text-white">Reportes de Error (ER)</h3>
                        <p class="text-sm text-slate-600 dark:text-zinc-400">
                            Contarás con un panel personal para revisar tu historial operativo. Si se registra algún error en un proceso, lo verás aquí detallado para ayudarte a corregirlo y mejorar.
                        </p>
                    </div>

                    <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-slate-200 dark:border-zinc-800 shadow-sm">
                        <div class="w-12 h-12 bg-purple-50 dark:bg-zinc-800 text-purple-600 dark:text-purple-400 rounded-xl flex items-center justify-center mb-4 border border-purple-100 dark:border-zinc-700">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold mb-2 dark:text-white">Vistas por Rol</h3>
                        <p class="text-sm text-slate-600 dark:text-zinc-400">
                            No te preocupes por ver información que no necesitas. El ERP está configurado para mostrarte únicamente las pantallas y opciones correspondientes a tu cargo.
                        </p>
                    </div>

                </div>

                <div class="flex justify-center">
                    <div class="bg-white dark:bg-zinc-900 p-8 rounded-3xl border border-slate-200 dark:border-zinc-800 shadow-xl text-center max-w-md w-full">
                        <h2 class="text-2xl font-bold mb-2 dark:text-white">Ingresa al ERP</h2>
                        <p class="text-slate-600 dark:text-zinc-400 mb-6 text-sm">
                            Utiliza las credenciales asignadas por administración para iniciar tu sesión.
                        </p>
                        <a
                            href="/erp"
                            class="block w-full text-center px-8 py-3.5 rounded-xl bg-green-600 text-white font-medium hover:bg-green-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition shadow-sm text-lg"
                        >
                            Ir a la pantalla de Login
                        </a>
                    </div>
                </div>

            </div>
        </main>

        <footer class="py-6 text-center text-sm text-slate-500 dark:text-zinc-500 border-t border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-950">
            © {{ year }} Operación Sistémica · Uso Exclusivo del Personal
        </footer>

    </div>
</template>