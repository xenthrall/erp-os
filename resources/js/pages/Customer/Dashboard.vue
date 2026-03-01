<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { PlusCircle, Clock, CheckCircle, Package, Store } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Inicio',
        href: "/customer/dashboard",

    },
];

import { usePage } from '@inertiajs/vue3';

const page = usePage();

const stats = page.props.stats as {
    inProcess: number;
    approved: number;
    total: number;
};

const recentWarranties = page.props.recentWarranties as Array<{
    id: number;
    model: string;
    invoice_number: string;
    damage_date: string;
    status: string;
}>;
</script>

<template>
    <Head title="Mis Solicitudes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4 md:p-8 max-w-7xl mx-auto w-full">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Resumen de Garantías</h1>
                    <p class="text-slate-600 dark:text-zinc-400 text-sm">Monitorea el estado de tus reposiciones y radica nuevos casos.</p>
                </div>
                
                <Link 
                    href="/customer/warranties/create" 
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-green-600 text-white font-medium hover:bg-green-700 transition shadow-sm"
                >
                    <PlusCircle class="w-5 h-5" />
                    Radicar Garantía
                </Link>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="flex items-center gap-4 rounded-xl border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm">
                    <div class="p-3 bg-amber-50 dark:bg-zinc-800 text-amber-600 dark:text-amber-400 rounded-lg">
                        <Clock class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">En Proceso</p>
                        <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ stats.inProcess }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 rounded-xl border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm">
                    <div class="p-3 bg-green-50 dark:bg-zinc-800 text-green-600 dark:text-green-400 rounded-lg">
                        <CheckCircle class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">Aprobadas</p>
                        <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ stats.approved }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 rounded-xl border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm">
                    <div class="p-3 bg-blue-50 dark:bg-zinc-800 text-blue-600 dark:text-blue-400 rounded-lg">
                        <Package class="w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">Total Histórico</p>
                        <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ stats.total }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-zinc-800">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">Casos Recientes</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 dark:text-zinc-400">
                        <thead class="bg-slate-50 dark:bg-zinc-800/50 text-slate-700 dark:text-zinc-300">
                            <tr>
                                <th class="px-6 py-4 font-medium">ID Caso</th>
                                <th class="px-6 py-4 font-medium">Repuesto / Modelo</th>
                                <th class="px-6 py-4 font-medium">Factura</th>
                                <th class="px-6 py-4 font-medium">Fecha Daño</th>
                                <th class="px-6 py-4 font-medium">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-zinc-800">
                            <tr v-for="warranty in recentWarranties" :key="warranty.id" class="hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition">
                                <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">#{{ warranty.id }}</td>
                                <td class="px-6 py-4">{{ warranty.model }}</td>
                                <td class="px-6 py-4">{{ warranty.invoice_number }}</td>
                                <td class="px-6 py-4">{{ warranty.damage_date }}</td>
                                <td class="px-6 py-4">
                                    <span :class="['px-3 py-1 text-xs font-semibold rounded-full']">
                                        {{ warranty.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </AppLayout>
</template>