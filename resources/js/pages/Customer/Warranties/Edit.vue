<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { ArrowLeft, Save } from 'lucide-vue-next';

interface Warranty {
    id: number;
    invoice_number: string;
    purchase_date: string;
    damage_date: string;
    model: string;
    internal_code?: string;
    quantity: number;
    shipping_city: string;
    shipping_address: string;
    failure_description: string;
}

const props = defineProps<{ warranty: Warranty }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Inicio', href: '/customer/dashboard' },
    { title: 'Mis Garantías', href: '/customer/warranties' },
    { title: `Garantía #${props.warranty.id}`, href: `/customer/warranties/${props.warranty.id}` },
    { title: 'Editar', href: '#' },
];

// Normaliza las fechas de ISO a YYYY-MM-DD para el input type="date"
const toDateInput = (val: string | null) => {
    if (!val) return '';
    return new Date(val).toISOString().slice(0, 10);
};

const form = useForm({
    invoice_number: props.warranty.invoice_number ?? '',
    purchase_date: toDateInput(props.warranty.purchase_date),
    damage_date: toDateInput(props.warranty.damage_date),
    model: props.warranty.model ?? '',
    internal_code: props.warranty.internal_code ?? '',
    quantity: props.warranty.quantity ?? 1,
    shipping_city: props.warranty.shipping_city ?? '',
    shipping_address: props.warranty.shipping_address ?? '',
    failure_description: props.warranty.failure_description ?? '',
});

const submit = () => {
    form.put(`/customer/warranties/${props.warranty.id}`, {
        preserveScroll: true,
    });
};
</script>

<template>

    <Head :title="`Editar Garantía #${warranty.id}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4 md:p-8 max-w-4xl mx-auto w-full pb-32 md:pb-8">

            <!-- Header Premium -->
            <div
                class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-gradient-to-r from-blue-900 to-indigo-800 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3 opacity-10 pointer-events-none">
                    <svg width="300" height="300" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#FFFFFF"
                            d="M44.7,-76.4C58.8,-69.2,71.8,-59.1,81.3,-46.3C90.8,-33.5,96.8,-18,95.5,-3C94.2,12,85.6,26.5,75.4,38.5C65.2,50.5,53.4,60.1,40.1,66.9C26.8,73.8,12,77.9,-2.8,79.5C-17.6,81.1,-35.3,80.1,-48.7,72.7C-62.1,65.3,-71.4,51.4,-78.9,36.5C-86.3,21.6,-91.9,5.7,-91.1,-10.1C-90.3,-25.9,-83.1,-41.8,-71.6,-53.4C-60.1,-65,-44.3,-72.3,-29.4,-77.4C-14.4,-82.5,0.7,-85.4,15.6,-81.9C30.4,-78.4,44,-68.5,44.7,-76.4Z"
                            transform="translate(100 100)" />
                    </svg>
                </div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-2">
                        <Link :href="`/customer/warranties/${warranty.id}`"
                            class="p-2 rounded-full hover:bg-white/20 transition text-white" aria-label="Volver">
                            <ArrowLeft class="w-5 h-5" />
                        </Link>
                        <h1 class="text-3xl font-bold tracking-tight">Editar Garantía <span
                                class="text-blue-200 opacity-80 text-2xl">#{{ warranty.id }}</span></h1>
                    </div>
                    <p class="text-blue-100 text-sm md:text-base ml-12 opacity-90 max-w-xl">
                        Modifica los datos de tu solicitud. Solo puedes editar información mientras se encuentre en
                        estado <strong class="text-white">Pendiente</strong>.
                    </p>
                </div>
            </div>

            <!-- Formulario -->
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Sección: Producto -->
                <section aria-labelledby="producto-title"
                    class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800 p-6 md:p-8 shadow-sm space-y-6">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 dark:border-zinc-800 pb-4">
                        <h2 id="producto-title"
                            class="text-base font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                            <span
                                class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">1</span>
                            Información del Producto
                        </h2>
                        <span class="text-xs text-slate-400">Campos obligatorios marcados con <span
                                class="text-red-500">*</span></span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Número de factura -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                Número de Factura <span class="text-red-500">*</span>
                            </label>
                            <input v-model="form.invoice_number" type="text" required placeholder="Ej. 2023-000123"
                                class="w-full bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                :class="{ 'border-red-400': form.errors.invoice_number }" />
                            <span v-if="form.errors.invoice_number" class="text-xs text-red-500 mt-1">
                                {{ form.errors.invoice_number }}
                            </span>
                        </div>

                        <!-- Modelo -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                Modelo del Producto <span class="text-red-500">*</span>
                            </label>
                            <input v-model="form.model" type="text" required placeholder="Ej. Barra LED X123"
                                class="w-full bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                :class="{ 'border-red-400': form.errors.model }" />
                            <span v-if="form.errors.model" class="text-xs text-red-500 mt-1">
                                {{ form.errors.model }}
                            </span>
                        </div>

                        <!-- Fecha de compra -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                Fecha de Compra <span class="text-red-500">*</span>
                            </label>
                            <input v-model="form.purchase_date" type="date" required
                                class="w-full bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                :class="{ 'border-red-400': form.errors.purchase_date }" />
                            <span v-if="form.errors.purchase_date" class="text-xs text-red-500 mt-1">
                                {{ form.errors.purchase_date }}
                            </span>
                        </div>

                        <!-- Fecha del daño -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                Fecha del Daño <span class="text-red-500">*</span>
                            </label>
                            <input v-model="form.damage_date" type="date" required
                                class="w-full bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                :class="{ 'border-red-400': form.errors.damage_date }" />
                            <span v-if="form.errors.damage_date" class="text-xs text-red-500 mt-1">
                                {{ form.errors.damage_date }}
                            </span>
                        </div>

                        <!-- Cantidad -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                Cantidad <span class="text-red-500">*</span>
                            </label>
                            <input v-model="form.quantity" type="number" min="1" required
                                class="w-full bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                :class="{ 'border-red-400': form.errors.quantity }" />
                            <span v-if="form.errors.quantity" class="text-xs text-red-500 mt-1">
                                {{ form.errors.quantity }}
                            </span>
                        </div>

                        <!-- Código interno -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                Código Interno <span class="text-slate-400 font-normal">(Opcional)</span>
                            </label>
                            <input v-model="form.internal_code" type="text" placeholder="Ej. SKU-00123"
                                class="w-full bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" />
                        </div>
                    </div>
                </section>

                <!-- Sección: Descripción -->
                <section aria-labelledby="desc-title"
                    class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800 p-6 md:p-8 shadow-sm space-y-6">
                    <div class="border-b border-slate-100 dark:border-zinc-800 pb-4">
                        <h2 id="desc-title"
                            class="text-base font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                            <span
                                class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">2</span>
                            Detalles de la Falla
                        </h2>
                    </div>

                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Descripción detallada
                            <span class="text-red-500">*</span></label>
                        <textarea v-model="form.failure_description" rows="5" required
                            placeholder="Describe con detalle qué sucede, cuándo comenzó, y si ocurre siempre o de forma intermitente."
                            class="w-full bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-y"
                            :class="{ 'border-red-400': form.errors.failure_description }"></textarea>
                        <span v-if="form.errors.failure_description" class="text-xs text-red-500 mt-1">{{
                            form.errors.failure_description }}</span>
                    </div>
                </section>

                <!-- Sección: Envío -->
                <section aria-labelledby="envio-title"
                    class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800 p-6 md:p-8 shadow-sm space-y-6">
                    <div class="border-b border-slate-100 dark:border-zinc-800 pb-4">
                        <h2 id="envio-title"
                            class="text-base font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                            <span
                                class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">3</span>
                            Datos de Envío
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Ciudad de
                                Destino <span class="text-red-500">*</span></label>
                            <input v-model="form.shipping_city" type="text" placeholder="Ej. Bogotá" required
                                class="w-full bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                :class="{ 'border-red-400': form.errors.shipping_city }" />
                            <span v-if="form.errors.shipping_city" class="text-xs text-red-500 mt-1">
                                {{ form.errors.shipping_city }}
                            </span>
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Dirección
                                Completa <span class="text-red-500">*</span></label>
                            <input v-model="form.shipping_address" type="text" placeholder="Calle 123 #45-67" required
                                class="w-full bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                :class="{ 'border-red-400': form.errors.shipping_address }" />
                            <span v-if="form.errors.shipping_address" class="text-xs text-red-500 mt-1">
                                {{ form.errors.shipping_address }}
                            </span>
                        </div>
                    </div>
                </section>

                <!-- Acciones Desktop -->
                <div class="hidden md:flex items-center justify-end gap-4 mt-8">
                    <Link :href="`/customer/warranties/${warranty.id}`"
                        class="inline-flex items-center justify-center px-6 py-3 rounded-xl font-medium text-slate-700 dark:text-slate-300 hover:bg-white dark:hover:bg-zinc-800 transition">
                        Cancelar
                    </Link>

                    <button type="submit" :disabled="form.processing"
                        class="inline-flex items-center gap-2 bg-blue-600 text-white font-medium px-8 py-3 rounded-xl hover:bg-blue-700 disabled:opacity-70 disabled:cursor-not-allowed transition shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        <Save v-if="!form.processing" class="w-5 h-5" />
                        {{ form.processing ? 'Guardando...' : 'Guardar cambios' }}
                    </button>
                </div>
            </form>

            <!-- Sticky Footer Mobile -->
            <div
                class="fixed bottom-0 left-0 right-0 md:hidden bg-white dark:bg-zinc-900 border-t border-slate-200 dark:border-zinc-800 p-3 shadow-lg">
                <div class="max-w-4xl mx-auto flex gap-3">
                    <Link :href="`/customer/warranties/${warranty.id}`"
                        class="w-1/2 text-center py-3 rounded-lg border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-slate-300 bg-white dark:bg-zinc-900 hover:bg-slate-50 dark:hover:bg-zinc-800 transition">
                        Cancelar
                    </Link>

                    <button type="button" @click="submit" :disabled="form.processing"
                        class="w-1/2 flex justify-center items-center gap-2 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 disabled:opacity-70 disabled:cursor-not-allowed transition">
                        <Save v-if="!form.processing" class="w-4 h-4" />
                        {{ form.processing ? 'Guardando...' : 'Guardar' }}
                    </button>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
