<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Plus, Eye, FileText, Clock, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    warranties: {
        data: Array<any>;
        links: Array<any>;
        total: number;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Inicio', href: '/customer/dashboard' },
    { title: 'Mis Garantías', href: '#' },
];

// --- Estado del modal de confirmación ---
const showDeleteModal = ref(false);
const warrantyToDelete = ref<{ id: number; model: string } | null>(null);

const deleteForm = useForm({});

function confirmDelete(item: { id: number; model: string }) {
    warrantyToDelete.value = item;
    showDeleteModal.value = true;
}

function cancelDelete() {
    showDeleteModal.value = false;
    warrantyToDelete.value = null;
}

function executeDelete() {
    if (!warrantyToDelete.value) return;
    deleteForm.delete(`/customer/warranties/${warrantyToDelete.value.id}`, {
        onSuccess: () => {
            showDeleteModal.value = false;
            warrantyToDelete.value = null;
        },
        onError: () => {
            showDeleteModal.value = false;
        },
    });
}

const getStatusClasses = (status: string) => {
    const map: Record<string, string> = {
        pending: 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
        approved: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
        rejected: 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
        in_review: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
    };
    return map[status?.toLowerCase()] || 'bg-slate-100 text-slate-700 dark:bg-zinc-800 dark:text-zinc-400';
};

const getStatusLabel = (status: string) => {
    const map: Record<string, string> = {
        pending: 'Pendiente',
        approved: 'Aprobada',
        rejected: 'Rechazada',
        in_review: 'En revisión',
    };
    return map[status?.toLowerCase()] || status;
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};
</script>

<template>
    <Head title="Mis Garantías" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4 md:p-8 max-w-6xl mx-auto w-full">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                        Mis Solicitudes de Garantía
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">
                        Gestiona y consulta el estado de tus radicados.
                    </p>
                </div>
                
                <Link 
                    href="/customer/warranties/create" 
                    class="inline-flex items-center justify-center gap-2 
                           bg-green-600 hover:bg-green-700 
                           text-white px-4 py-3 
                           rounded-lg transition-colors 
                           font-medium shadow-sm
                           w-full md:w-auto">
                    <Plus class="w-4 h-4" />
                    Nueva Solicitud
                </Link>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-800 shadow-sm overflow-hidden">
                
                <!-- ===================== -->
                <!-- DESKTOP TABLE -->
                <!-- ===================== -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-zinc-800/50 border-b border-slate-200 dark:border-zinc-800">
                                <th class="px-6 py-4 text-xs font-semibold uppercase text-slate-500">ID / Fecha</th>
                                <th class="px-6 py-4 text-xs font-semibold uppercase text-slate-500">Producto</th>
                                <th class="px-6 py-4 text-xs font-semibold uppercase text-slate-500 text-center">Cant.</th>
                                <th class="px-6 py-4 text-xs font-semibold uppercase text-slate-500">Estado</th>
                                <th class="px-6 py-4 text-xs font-semibold uppercase text-slate-500 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            <tr 
                                v-for="item in warranties.data" 
                                :key="item.id"
                                class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/30 transition-colors"
                            >
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-900 dark:text-white">
                                            #{{ item.id }}
                                        </span>
                                        <span class="text-xs text-slate-500">
                                            {{ formatDate(item.created_at) }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                            {{ item.model }}
                                        </span>
                                        <span class="text-xs text-slate-500">
                                            Factura: {{ item.invoice_number }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    {{ item.quantity }}
                                </td>

                                <td class="px-6 py-4">
                                    <span 
                                        :class="[
                                            'px-2.5 py-1 rounded-full text-xs font-medium inline-flex items-center gap-1.5',
                                            getStatusClasses(item.status)
                                        ]"
                                    >
                                        <Clock class="w-3 h-3" />
                                        {{ getStatusLabel(item.status) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-3">
                                        <Link 
                                            :href="'/customer/warranties/' + item.id" 
                                            class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline"
                                        >
                                            <Eye class="w-4 h-4" />
                                            Ver detalles
                                        </Link>

                                        <button
                                            v-if="item.status === 'pending'"
                                            @click="confirmDelete(item)"
                                            class="inline-flex items-center gap-1.5 text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Fila vacía desktop -->
                            <tr v-if="warranties.data.length === 0">
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <FileText class="w-10 h-10 mx-auto text-slate-300 dark:text-zinc-700 mb-3" />
                                    <p class="text-slate-500 italic">No has radicado ninguna garantía todavía.</p>
                                    <Link 
                                        href="/customer/warranties/create"
                                        class="text-blue-600 text-sm font-medium"
                                    >
                                        Radicar mi primera solicitud
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- ===================== -->
                <!-- MOBILE CARDS -->
                <!-- ===================== -->
                <div class="md:hidden divide-y divide-slate-200 dark:divide-zinc-800">
                    <div 
                        v-for="item in warranties.data" 
                        :key="item.id"
                        class="p-4 space-y-3"
                    >
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold text-slate-900 dark:text-white">
                                    #{{ item.id }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    {{ formatDate(item.created_at) }}
                                </p>
                            </div>

                            <span 
                                :class="[
                                    'px-2.5 py-1 rounded-full text-xs font-medium inline-flex items-center gap-1.5',
                                    getStatusClasses(item.status)
                                ]"
                            >
                                <Clock class="w-3 h-3" />
                                {{ getStatusLabel(item.status) }}
                            </span>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ item.model }}
                            </p>
                            <p class="text-xs text-slate-500">
                                Factura: {{ item.invoice_number }}
                            </p>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600 dark:text-slate-400">
                                Cantidad: <strong>{{ item.quantity }}</strong>
                            </span>

                            <div class="flex items-center gap-3">
                                <Link 
                                    :href="'/customer/warranties/' + item.id"
                                    class="text-sm font-medium text-blue-600 dark:text-blue-400"
                                >
                                    Ver detalles →
                                </Link>

                                <button
                                    v-if="item.status === 'pending'"
                                    @click="confirmDelete(item)"
                                    class="inline-flex items-center gap-1 text-sm font-medium text-red-600 dark:text-red-400"
                                >
                                    <Trash2 class="w-4 h-4" />
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>

                    <div 
                        v-if="warranties.data.length === 0"
                        class="p-8 text-center"
                    >
                        <FileText class="w-10 h-10 mx-auto text-slate-300 dark:text-zinc-700 mb-3" />
                        <p class="text-slate-500 italic">
                            No has radicado ninguna garantía todavía.
                        </p>
                        <Link 
                            href="/customer/warranties/create"
                            class="text-blue-600 text-sm font-medium"
                        >
                            Radicar mi primera solicitud
                        </Link>
                    </div>
                </div>

                <!-- ===================== -->
                <!-- PAGINATION -->
                <!-- ===================== -->
                <div 
                    v-if="warranties.total > 0" 
                    class="px-4 py-4 border-t border-slate-100 dark:border-zinc-800 
                           bg-slate-50/50 dark:bg-zinc-800/20"
                >
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        
                        <span class="text-xs text-slate-500 text-center md:text-left">
                            Mostrando {{ warranties.data.length }} de {{ warranties.total }} solicitudes
                        </span>

                        <div class="flex gap-2 overflow-x-auto pb-1">
                            <Component 
                                :is="link.url ? Link : 'span'"
                                v-for="(link, k) in warranties.links" 
                                :key="k"
                                :href="link.url"
                                v-html="link.label"
                                class="px-3 py-1 text-xs rounded border whitespace-nowrap transition-colors"
                                :class="[
                                    link.active 
                                        ? 'bg-blue-600 border-blue-600 text-white' 
                                        : 'bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-slate-400',
                                    !link.url 
                                        ? 'opacity-50 cursor-not-allowed' 
                                        : 'hover:bg-slate-100 dark:hover:bg-zinc-800'
                                ]"
                            />
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ===================== -->
        <!-- MODAL DE CONFIRMACIÓN -->
        <!-- ===================== -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="showDeleteModal"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                >
                    <!-- Backdrop -->
                    <div 
                        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
                        @click="cancelDelete"
                    />

                    <!-- Modal card -->
                    <Transition
                        enter-active-class="transition duration-200 ease-out"
                        enter-from-class="opacity-0 scale-95"
                        enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition duration-150 ease-in"
                        leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95"
                        appear
                    >
                        <div
                            v-if="showDeleteModal"
                            class="relative bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-md p-6 border border-slate-200 dark:border-zinc-700"
                        >
                            <!-- Icono de advertencia -->
                            <div class="flex items-center justify-center w-14 h-14 mx-auto mb-4 rounded-full bg-red-100 dark:bg-red-900/30">
                                <Trash2 class="w-7 h-7 text-red-600 dark:text-red-400" />
                            </div>

                            <h3 class="text-lg font-bold text-center text-slate-900 dark:text-white mb-1">
                                ¿Eliminar garantía?
                            </h3>
                            <p class="text-sm text-center text-slate-500 dark:text-slate-400 mb-6">
                                Vas a eliminar la garantía 
                                <span class="font-semibold text-slate-700 dark:text-slate-300">
                                    #{{ warrantyToDelete?.id }} – {{ warrantyToDelete?.model }}
                                </span>.
                                Esta acción no se puede deshacer.
                            </p>

                            <div class="flex gap-3">
                                <button
                                    @click="cancelDelete"
                                    class="flex-1 px-4 py-2.5 rounded-lg border border-slate-200 dark:border-zinc-700 
                                           text-slate-700 dark:text-slate-300 font-medium text-sm
                                           hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors"
                                >
                                    Cancelar
                                </button>
                                <button
                                    @click="executeDelete"
                                    :disabled="deleteForm.processing"
                                    class="flex-1 px-4 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 
                                           text-white font-medium text-sm transition-colors
                                           disabled:opacity-60 disabled:cursor-not-allowed
                                           inline-flex items-center justify-center gap-2"
                                >
                                    <span v-if="deleteForm.processing">Eliminando…</span>
                                    <span v-else class="inline-flex items-center gap-2">
                                        <Trash2 class="w-4 h-4" />
                                        Sí, eliminar
                                    </span>
                                </button>
                            </div>
                        </div>
                    </Transition>
                </div>
            </Transition>
        </Teleport>

    </AppLayout>
</template>