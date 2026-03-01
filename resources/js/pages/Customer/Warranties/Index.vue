<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Plus, Eye, FileText, Clock } from 'lucide-vue-next';

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

                                <td class="px-6 py-4 text-right">
                                    <Link 
                                        :href="'/customer/warranties/' + item.id" 
                                        class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline"
                                    >
                                        <Eye class="w-4 h-4" />
                                        Ver detalles
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

                            <Link 
                                :href="'/customer/warranties/' + item.id"
                                class="text-sm font-medium text-blue-600 dark:text-blue-400"
                            >
                                Ver detalles →
                            </Link>
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
    </AppLayout>
</template>