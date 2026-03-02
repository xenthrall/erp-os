<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Plus, Eye, FileText, Clock, Trash2, Pencil, MoreVertical } from 'lucide-vue-next';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
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

            <!-- Encabezado con Fondo Atractivo -->
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 bg-gradient-to-r from-blue-900 to-indigo-800 rounded-3xl p-8 md:p-10 text-white shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3 opacity-10 pointer-events-none">
                    <svg width="400" height="400" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#FFFFFF"
                            d="M44.7,-76.4C58.8,-69.2,71.8,-59.1,81.3,-46.3C90.8,-33.5,96.8,-18,95.5,-3C94.2,12,85.6,26.5,75.4,38.5C65.2,50.5,53.4,60.1,40.1,66.9C26.8,73.8,12,77.9,-2.8,79.5C-17.6,81.1,-35.3,80.1,-48.7,72.7C-62.1,65.3,-71.4,51.4,-78.9,36.5C-86.3,21.6,-91.9,5.7,-91.1,-10.1C-90.3,-25.9,-83.1,-41.8,-71.6,-53.4C-60.1,-65,-44.3,-72.3,-29.4,-77.4C-14.4,-82.5,0.7,-85.4,15.6,-81.9C30.4,-78.4,44,-68.5,44.7,-76.4Z"
                            transform="translate(100 100)" />
                    </svg>
                </div>

                <div class="relative z-10 max-w-2xl">
                    <h1 class="text-3xl md:text-4xl font-bold mb-3 tracking-tight">Historial de Garantías</h1>
                    <p class="text-blue-100 text-sm md:text-base leading-relaxed opacity-90">
                        Consulta todo tu historial de solicitudes de garantía radicadas, verifica sus estados y
                        administra su información rápidamente.
                    </p>
                </div>

                <Link href="/customer/warranties/create"
                    class="relative z-10 shrink-0 inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white text-blue-900 font-bold hover:bg-slate-50 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    <Plus class="w-5 h-5" />
                    Radicar Garantía
                </Link>
            </div>

            <div v-if="warranties.data.length === 0"
                class="rounded-2xl border border-dashed border-slate-300 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-12 text-center mt-4">
                <FileText class="w-16 h-16 mx-auto text-slate-300 dark:text-zinc-700 mb-4" />
                <h3 class="text-xl font-bold text-slate-800 dark:text-white">No hay solicitudes radicadas</h3>
                <p class="text-slate-500 max-w-md mx-auto mt-2 mb-6">Aún no has creado ninguna solicitud de garantía.
                    Puedes iniciar una presionando el botón de radicación.</p>
                <Link href="/customer/warranties/create"
                    class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-xl transition">
                    Radicar mi primera solicitud
                </Link>
            </div>

            <!-- GRID TIPO DASHBOARD (Reemplaza tabla desktop y modo mobile) -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
                <div v-for="item in warranties.data" :key="item.id"
                    class="group relative flex flex-col p-5 rounded-2xl border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 hover:border-blue-300 dark:hover:border-blue-900/50 transition-all shadow-sm hover:shadow-md">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-zinc-800 flex flex-col justify-center items-center shrink-0 border border-slate-200 dark:border-zinc-700 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20 transition-colors">
                                <span
                                    class="text-[10px] font-bold text-slate-400 dark:text-slate-500 leading-none mb-0.5">#</span>
                                <span class="text-sm font-bold text-slate-800 dark:text-slate-200 leading-none">{{
                                    item.customer_sequence }}</span>
                            </div>
                            <div>
                                <span
                                    :class="['inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold', getStatusClasses(item.status)]">
                                    <Clock class="w-3.5 h-3.5" />
                                    {{ getStatusLabel(item.status) }}
                                </span>
                            </div>
                        </div>

                        <div class="relative z-10 flex items-center">
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <button
                                        class="p-2 -mr-2 rounded-full text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-700 dark:hover:text-slate-200 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <MoreVertical class="w-5 h-5" />
                                    </button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end" class="w-48">
                                    <DropdownMenuItem as-child>
                                        <Link :href="'/customer/warranties/' + item.id"
                                            class="cursor-pointer flex items-center gap-2 w-full">
                                            <Eye class="w-4 h-4 opacity-70" />
                                            <span>Ver detalles</span>
                                        </Link>
                                    </DropdownMenuItem>

                                    <DropdownMenuItem v-if="item.status === 'pending'" as-child>
                                        <Link :href="'/customer/warranties/' + item.id + '/edit'"
                                            class="cursor-pointer flex items-center gap-2 w-full">
                                            <Pencil class="w-4 h-4 opacity-70" />
                                            <span>Editar</span>
                                        </Link>
                                    </DropdownMenuItem>

                                    <DropdownMenuItem v-if="item.status === 'pending'" @click="confirmDelete(item)"
                                        class="text-red-600 focus:text-red-600 dark:text-red-400 focus:dark:text-red-400 focus:bg-red-50 dark:focus:bg-red-950/50 cursor-pointer">
                                        <div class="flex items-center gap-2 w-full">
                                            <Trash2 class="w-4 h-4 opacity-70" />
                                            <span>Eliminar</span>
                                        </div>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4 class="font-bold text-lg text-slate-900 dark:text-white line-clamp-1 mb-1">Modelo: {{ item.model }}
                        </h4>
                        <div class="flex items-center gap-3 text-sm text-slate-500 dark:text-zinc-400">
                            <span class="flex items-center gap-1.5">
                                <FileText class="w-4 h-4" /> Fac: {{ item.invoice_number }}
                            </span>
                            <span
                                class="flex items-center gap-1.5 before:content-['•'] before:mr-2 before:opacity-50">Cant:
                                {{ item.quantity }}</span>
                        </div>
                    </div>

                    <div
                        class="mt-auto pt-4 border-t border-slate-100 dark:border-zinc-800 flex items-center justify-between text-sm">
                        <span class="text-slate-500 dark:text-zinc-400">
                            Radicada el {{ formatDate(item.created_at) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Div vacío cuando no hay garantías (refactorizado arriba) -->
            <!-- PAGINATION -->
            <div v-if="warranties.total > 0"
                class="px-4 py-4 border-t border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-800/20 rounded-xl mt-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <span class="text-xs text-slate-500 text-center md:text-left">
                        Mostrando {{ warranties.data.length }} de {{ warranties.total }} solicitudes
                    </span>

                    <div class="flex gap-2 overflow-x-auto pb-1">
                        <Component :is="link.url ? Link : 'span'" v-for="(link, k) in warranties.links" :key="k"
                            :href="link.url" v-html="link.label"
                            class="px-3 py-1 text-xs rounded border whitespace-nowrap transition-colors" :class="[
                                link.active
                                    ? 'bg-blue-600 border-blue-600 text-white'
                                    : 'bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-slate-400',
                                !link.url
                                    ? 'opacity-50 cursor-not-allowed'
                                    : 'hover:bg-slate-100 dark:hover:bg-zinc-800'
                            ]" />
                    </div>
                </div>
            </div>
        </div>

        <!-- ===================== -->
        <!-- MODAL DE CONFIRMACIÓN -->
        <!-- ===================== -->
        <Teleport to="body">
            <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0"
                enter-to-class="opacity-100" leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <!-- Backdrop -->
                    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="cancelDelete" />

                    <!-- Modal card -->
                    <Transition enter-active-class="transition duration-200 ease-out"
                        enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95" appear>
                        <div v-if="showDeleteModal"
                            class="relative bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-md p-6 border border-slate-200 dark:border-zinc-700">
                            <!-- Icono de advertencia -->
                            <div
                                class="flex items-center justify-center w-14 h-14 mx-auto mb-4 rounded-full bg-red-100 dark:bg-red-900/30">
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
                                <button @click="cancelDelete" class="flex-1 px-4 py-2.5 rounded-lg border border-slate-200 dark:border-zinc-700 
                                           text-slate-700 dark:text-slate-300 font-medium text-sm
                                           hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors">
                                    Cancelar
                                </button>
                                <button @click="executeDelete" :disabled="deleteForm.processing" class="flex-1 px-4 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 
                                           text-white font-medium text-sm transition-colors
                                           disabled:opacity-60 disabled:cursor-not-allowed
                                           inline-flex items-center justify-center gap-2">
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