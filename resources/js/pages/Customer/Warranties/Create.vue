<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { ArrowLeft, Save } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Inicio', href: '/customer/dashboard' },
    { title: 'Mis Garantías', href: '/customer/warranties' }, 
    { title: 'Nueva Solicitud', href: '#' },
];

const form = useForm({
    invoice_number: '',
    purchase_date: '',
    damage_date: '',
    model: '',
    internal_code: '',
    quantity: 1,
    shipping_city: '',
    shipping_address: '',
    failure_description: '',
});

const submit = () => {
    form.post('/customer/warranties', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            // opcional: mostrar toast de éxito aquí
        },
    });
};
</script>

<template>
    <Head title="Nueva Solicitud de Garantía" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4 md:p-8 max-w-4xl mx-auto w-full pb-32 md:pb-8">

            <!-- Header -->
            <div class="flex items-start gap-4">
                <Link 
                    href="/customer/warranties" 
                    class="mt-1 p-2 rounded-full hover:bg-slate-100 dark:hover:bg-zinc-800 transition text-slate-600 dark:text-slate-300"
                    aria-label="Volver"
                >
                    <ArrowLeft class="w-5 h-5" />
                </Link>

                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                        Radicar Nueva Garantía
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                        Por favor completa los datos requeridos para procesar tu solicitud.
                    </p>
                </div>
            </div>

            <!-- Card / Form -->
            <form 
                @submit.prevent="submit"
                class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800 p-5 md:p-8 shadow-md space-y-6"
                aria-labelledby="form-title"
            >
                <!-- Sección: Producto -->
                <section aria-labelledby="producto-title" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 id="producto-title" class="text-sm font-semibold uppercase tracking-wide text-slate-500">
                            Información del producto
                        </h2>
                        <span class="text-xs text-slate-400">Campos obligatorios *</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Número de factura -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                Número de Factura <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.invoice_number"
                                type="text"
                                required
                                placeholder="Ej. 2023-000123"
                                class="w-full mt-2 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white placeholder-slate-400 shadow-sm focus:outline-none focus:ring-4 focus:ring-green-200 dark:focus:ring-green-900 transition"
                            />
                            <span v-if="form.errors.invoice_number" class="text-xs text-red-500 mt-1">{{ form.errors.invoice_number }}</span>
                        </div>

                        <!-- Modelo -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                Modelo del Producto <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.model"
                                type="text"
                                required
                                placeholder="Ej. Barra LED X123"
                                class="w-full mt-2 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white placeholder-slate-400 shadow-sm focus:outline-none focus:ring-4 focus:ring-green-200 dark:focus:ring-green-900 transition"
                            />
                            <span v-if="form.errors.model" class="text-xs text-red-500 mt-1">{{ form.errors.model }}</span>
                        </div>

                        <!-- Fecha de compra -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                Fecha de Compra <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.purchase_date"
                                type="date"
                                required
                                class="w-full mt-2 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl px-3 py-2 text-slate-900 dark:text-white shadow-sm focus:outline-none focus:ring-4 focus:ring-green-200 dark:focus:ring-green-900 transition"
                            />
                            <span v-if="form.errors.purchase_date" class="text-xs text-red-500 mt-1">{{ form.errors.purchase_date }}</span>
                        </div>

                        <!-- Fecha del daño -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                Fecha del Daño <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.damage_date"
                                type="date"
                                required
                                class="w-full mt-2 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl px-3 py-2 text-slate-900 dark:text-white shadow-sm focus:outline-none focus:ring-4 focus:ring-green-200 dark:focus:ring-green-900 transition"
                            />
                            <span v-if="form.errors.damage_date" class="text-xs text-red-500 mt-1">{{ form.errors.damage_date }}</span>
                        </div>

                        <!-- Cantidad -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                Cantidad <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.quantity"
                                type="number"
                                min="1"
                                required
                                class="w-full mt-2 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white shadow-sm focus:outline-none focus:ring-4 focus:ring-green-200 dark:focus:ring-green-900 transition"
                            />
                            <span v-if="form.errors.quantity" class="text-xs text-red-500 mt-1">{{ form.errors.quantity }}</span>
                        </div>

                        <!-- Código interno -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                Código Interno <span class="text-slate-400 font-normal">(Opcional)</span>
                            </label>
                            <input
                                v-model="form.internal_code"
                                type="text"
                                placeholder="Ej. SKU-00123"
                                class="w-full mt-2 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white shadow-sm focus:outline-none focus:ring-4 focus:ring-green-200 dark:focus:ring-green-900 transition"
                            />
                            <span v-if="form.errors.internal_code" class="text-xs text-red-500 mt-1">{{ form.errors.internal_code }}</span>
                        </div>
                    </div>
                </section>

                <!-- Sección: Envío -->
                <section aria-labelledby="envio-title" class="space-y-4">
                    <h3 id="envio-title" class="text-sm font-semibold uppercase tracking-wide text-slate-500">
                        Información de envío
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Ciudad de Envío <span class="text-red-500">*</span></label>
                            <input
                                v-model="form.shipping_city"
                                type="text"
                                placeholder="Ej. Bogotá"
                                required
                                class="w-full mt-2 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white shadow-sm focus:outline-none focus:ring-4 focus:ring-green-200 dark:focus:ring-green-900 transition"
                            />
                            <span v-if="form.errors.shipping_city" class="text-xs text-red-500 mt-1">{{ form.errors.shipping_city }}</span>
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Dirección de Envío <span class="text-red-500">*</span></label>
                            <input
                                v-model="form.shipping_address"
                                type="text"
                                placeholder="Calle 123 #45-67"
                                required
                                class="w-full mt-2 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white shadow-sm focus:outline-none focus:ring-4 focus:ring-green-200 dark:focus:ring-green-900 transition"
                            />
                            <span v-if="form.errors.shipping_address" class="text-xs text-red-500 mt-1">{{ form.errors.shipping_address }}</span>
                        </div>
                    </div>
                </section>

                <!-- Sección: Descripción -->
                <section class="space-y-2">
                    <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Descripción detallada de la falla <span class="text-red-500">*</span></label>
                    <textarea
                        v-model="form.failure_description"
                        rows="5"
                        required
                        placeholder="Describe con detalle qué sucede, cuándo comenzó, y si ocurre siempre o de forma intermitente."
                        class="w-full mt-2 bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white placeholder-slate-400 shadow-sm focus:outline-none focus:ring-4 focus:ring-green-200 dark:focus:ring-green-900 transition resize-y"
                    ></textarea>
                    <span v-if="form.errors.failure_description" class="text-xs text-red-500 mt-1">{{ form.errors.failure_description }}</span>
                </section>

                <!-- Acciones Desktop -->
                <div class="hidden md:flex items-center justify-end gap-4 pt-2 border-t border-slate-100 dark:border-zinc-800 mt-2">
                    <Link
                        href="/customer/warranties"
                        class="inline-flex items-center justify-center px-5 py-2 rounded-lg text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 transition"
                    >
                        Cancelar
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 disabled:opacity-70 disabled:cursor-not-allowed transition shadow"
                    >
                        <Save v-if="!form.processing" class="w-4 h-4" />
                        {{ form.processing ? 'Enviando...' : 'Enviar Solicitud' }}
                    </button>
                </div>
            </form>

            <!-- Sticky Footer Mobile (acciones) -->
            <div class="fixed bottom-0 left-0 right-0 md:hidden bg-white dark:bg-zinc-900 border-t border-slate-200 dark:border-zinc-800 p-3 shadow-lg">
                <div class="max-w-4xl mx-auto flex gap-3">
                    <Link
                        href="/customer/warranties"
                        class="w-1/2 text-center py-3 rounded-lg border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-slate-300 bg-white dark:bg-zinc-900 hover:bg-slate-50 dark:hover:bg-zinc-800 transition"
                    >
                        Cancelar
                    </Link>

                    <button
                        type="button"
                        @click="submit"
                        :disabled="form.processing"
                        class="w-1/2 flex justify-center items-center gap-2 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 disabled:opacity-70 disabled:cursor-not-allowed transition"
                    >
                        <Save v-if="!form.processing" class="w-4 h-4" />
                        {{ form.processing ? 'Enviando...' : 'Enviar' }}
                    </button>
                </div>
            </div>

        </div>
    </AppLayout>
</template>

