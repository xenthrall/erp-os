<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

const year = new Date().getFullYear()

const logout = () => {
    router.post('cliente/logout')
}
</script>

<template>
    <Head title="Cuenta pendiente de activación - Operación Sistémica" />

    <div
        class="min-h-screen flex flex-col bg-slate-50 text-slate-900 dark:bg-zinc-950 dark:text-zinc-100 transition-colors duration-500"
    >
        <!-- Header -->
        <header class="bg-white dark:bg-zinc-950 border-b border-slate-200 dark:border-zinc-800">
            <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
                <div class="text-lg font-semibold tracking-wide">
                    <span class="dark:text-white">operación</span>
                    <span class="text-green-600 dark:text-green-400">
                        sistémica
                    </span>
                </div>
            </div>
        </header>

        <!-- Main -->
        <main class="flex-1 flex items-center justify-center py-12">
            <div class="max-w-xl w-full px-6">
                <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-md border border-slate-200 dark:border-zinc-800 p-10 text-center">

                    <!-- Icono más neutro -->
                    <div class="w-16 h-16 mx-auto mb-6 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>

                    <h1 class="text-2xl font-bold mb-4 dark:text-white">
                        Cuenta pendiente de activación
                    </h1>

                    <p class="text-slate-600 dark:text-zinc-400 mb-6">
                        Tu acceso al sistema está correctamente autenticado, 
                        pero la cuenta aún requiere activación administrativa 
                        para habilitar todas sus funcionalidades.
                    </p>

                    <div class="bg-slate-50 dark:bg-zinc-800 rounded-lg p-5 text-sm text-slate-600 dark:text-zinc-400 text-left mb-8">
                        <p class="font-medium text-slate-700 dark:text-zinc-300 mb-2">
                            ¿Qué significa esto?
                        </p>
                        <p>
                            El registro fue creado exitosamente, pero todavía no ha sido 
                            validado por el área correspondiente. Una vez completado el proceso, 
                            podrás acceder con normalidad.
                        </p>
                    </div>

                    <div class="flex flex-col gap-3">
                        <button
                            @click="logout"
                            class="w-full px-4 py-2.5 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-black font-medium hover:opacity-90 transition"
                        >
                            Cerrar sesión
                        </button>

                        <a
                            href="/"
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-medium hover:bg-slate-100 dark:hover:bg-zinc-800 transition"
                        >
                            Contactar al área administrativa
                        </a>
                    </div>

                    <p class="mt-6 text-xs text-slate-400">
                        Si consideras que esto es un error, comunícate con soporte indicando tu correo de acceso.
                    </p>

                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-6 text-center text-sm text-slate-500 dark:text-zinc-500 border-t border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-950">
            © {{ year }} Operación Sistémica · Plataforma de Gestión
        </footer>
    </div>
</template>