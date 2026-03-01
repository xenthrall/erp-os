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
    <Head title="Centro de Garantías - Operación Sistémica" />

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
                
                </div>
        </header>

        <main class="flex-1 flex items-center justify-center py-12">
            <div class="max-w-2xl mx-auto px-6 w-full">
                
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-8 md:p-12 border border-slate-200 dark:border-zinc-800 shadow-xl dark:shadow-2xl flex flex-col items-center text-center transition-all">
                    
                    <div class="w-16 h-16 bg-green-100 dark:bg-zinc-800 text-green-600 dark:text-zinc-300 rounded-2xl flex items-center justify-center mb-8 border dark:border-zinc-700">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>

                    <h1 class="text-3xl md:text-4xl font-bold mb-4 text-slate-800 dark:text-white">
                        Centro de Garantías
                    </h1>
                    
                    <p v-if="$page.props.auth.user" class="text-slate-600 dark:text-zinc-400 max-w-lg mx-auto mb-10 text-lg">
                        Hola de nuevo. Ya tienes una sesión activa, puedes continuar directamente a tu panel para gestionar tus solicitudes de garantía.
                    </p>
                    <p v-else class="text-slate-600 dark:text-zinc-400 max-w-lg mx-auto mb-10 text-lg">
                        Bienvenido al portal exclusivo para clientes y distribuidores. Ingresa para radicar nuevas solicitudes, subir evidencias y consultar el estado de tus envíos.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto sm:min-w-[60%] justify-center">
                        
                        <template v-if="$page.props.auth.user">
                            <Link
                                :href="dashboard()"
                                class="flex-1 text-center px-6 py-3 rounded-xl bg-green-600 text-white font-medium hover:bg-green-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition shadow-sm"
                            >
                                Ingresar a mi cuenta
                            </Link>
                        </template>

                        <template v-else>
                            <Link
                                :href="login()"
                                class="flex-1 text-center px-6 py-3 rounded-xl bg-green-600 text-white font-medium hover:bg-green-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition shadow-sm"
                            >
                                Iniciar Sesión
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="register()"
                                class="flex-1 text-center px-6 py-3 rounded-xl border-2 border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 font-medium hover:bg-slate-50 dark:hover:bg-zinc-800 transition"
                            >
                                Registrarse
                            </Link>
                        </template>

                    </div>
                </div>

            </div>
        </main>

        <footer class="py-6 text-center text-sm text-slate-500 dark:text-zinc-500 border-t border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-950">
            © {{ year }} Operación Sistémica · Portal de Clientes
        </footer>

    </div>
</template>