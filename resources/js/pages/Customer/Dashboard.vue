<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import {
    PlusCircle,
    Clock,
    CheckCircle2,
    AlertCircle,
    XCircle,
    Package,
    ChevronRight,
    Headset,
    FileText,
    MoreVertical,
    Eye,
    Pencil
} from 'lucide-vue-next';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Inicio', href: "/customer/dashboard" },
];

import { usePage } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth.user;

const stats = page.props.stats as {
    pending: number;
    inReview: number;
    approved: number;
    rejected: number;
    total: number;
};

const recentWarranties = page.props.recentWarranties as Array<{
    id: number;
    customer_sequence: number;
    model: string;
    status: string;
    created_at: string;
}>;

// Utilidades para formato
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('es-ES', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
};

const getStatusConfig = (status: string) => {
    const map: Record<string, { label: string; bg: string; text: string; icon: any; iconColor: string }> = {
        pending: {
            label: 'Pendiente',
            bg: 'bg-amber-100 dark:bg-amber-900/30',
            text: 'text-amber-700 dark:text-amber-400',
            iconColor: 'text-amber-500',
            icon: Clock,
        },
        in_review: {
            label: 'En Revisión',
            bg: 'bg-blue-100 dark:bg-blue-900/30',
            text: 'text-blue-700 dark:text-blue-400',
            iconColor: 'text-blue-500',
            icon: AlertCircle,
        },
        approved: {
            label: 'Aprobada',
            bg: 'bg-green-100 dark:bg-green-900/30',
            text: 'text-green-700 dark:text-green-400',
            iconColor: 'text-green-500',
            icon: CheckCircle2,
        },
        rejected: {
            label: 'Rechazada',
            bg: 'bg-red-100 dark:bg-red-900/30',
            text: 'text-red-700 dark:text-red-400',
            iconColor: 'text-red-500',
            icon: XCircle,
        },
    };
    return map[status?.toLowerCase()] || map['pending'];
};
</script>

<template>

    <Head title="Panel de Control" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4 md:p-8 max-w-7xl mx-auto w-full pb-32 md:pb-8">

            <!-- HEADER PREMIUM -->
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 bg-gradient-to-r from-blue-900 to-indigo-800 rounded-3xl p-8 md:p-10 text-white shadow-xl relative overflow-hidden">
                <!-- Efecto visual de fondo -->
                <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3 opacity-10 pointer-events-none">
                    <svg width="400" height="400" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#FFFFFF"
                            d="M44.7,-76.4C58.8,-69.2,71.8,-59.1,81.3,-46.3C90.8,-33.5,96.8,-18,95.5,-3C94.2,12,85.6,26.5,75.4,38.5C65.2,50.5,53.4,60.1,40.1,66.9C26.8,73.8,12,77.9,-2.8,79.5C-17.6,81.1,-35.3,80.1,-48.7,72.7C-62.1,65.3,-71.4,51.4,-78.9,36.5C-86.3,21.6,-91.9,5.7,-91.1,-10.1C-90.3,-25.9,-83.1,-41.8,-71.6,-53.4C-60.1,-65,-44.3,-72.3,-29.4,-77.4C-14.4,-82.5,0.7,-85.4,15.6,-81.9C30.4,-78.4,44,-68.5,44.7,-76.4Z"
                            transform="translate(100 100)" />
                    </svg>
                </div>

                <div class="relative z-10 max-w-2xl">
                    <p class="text-blue-200 font-medium mb-1 truncate">Hola, {{ user.name }} 👋</p>
                    <h1 class="text-3xl md:text-4xl font-bold mb-3 tracking-tight">Centro de Garantías LED</h1>
                    <p class="text-blue-100 text-sm md:text-base leading-relaxed opacity-90">
                        Gestiona tus solicitudes relacionadas con barras LED, adjunta evidencias,
                        y realiza seguimiento en tiempo real al estado de evaluación y envío de reemplazo.
                    </p>
                </div>

                <Link href="/customer/warranties/create"
                    class="relative z-10 shrink-0 inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white text-blue-900 font-bold hover:bg-slate-50 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    <PlusCircle class="w-5 h-5" />
                    Radicar Garantía
                </Link>
            </div>

            <!-- KPIs (TARJETAS GLASMORPHISM / PASTEL) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- Pendientes -->
                <div
                    class="rounded-2xl border border-amber-200/50 bg-gradient-to-br from-amber-50 to-white dark:from-amber-900/10 dark:to-zinc-900 dark:border-amber-900/30 p-5 shadow-sm relative overflow-hidden">
                    <div
                        class="absolute right-0 top-0 p-4 opacity-5 bg-amber-500 rounded-bl-full w-24 h-24 -mr-8 -mt-8 pointer-events-none">
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-semibold text-amber-800 dark:text-amber-500 mb-1">Pendientes</p>
                            <h3 class="text-3xl font-bold text-slate-800 dark:text-white">{{ stats.pending }}</h3>
                        </div>
                        <div
                            class="p-2.5 bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400 rounded-xl">
                            <Clock class="w-5 h-5" />
                        </div>
                    </div>
                </div>

                <!-- En Revisión -->
                <div
                    class="rounded-2xl border border-blue-200/50 bg-gradient-to-br from-blue-50 to-white dark:from-blue-900/10 dark:to-zinc-900 dark:border-blue-900/30 p-5 shadow-sm relative overflow-hidden">
                    <div
                        class="absolute right-0 top-0 p-4 opacity-5 bg-blue-500 rounded-bl-full w-24 h-24 -mr-8 -mt-8 pointer-events-none">
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-semibold text-blue-800 dark:text-blue-500 mb-1">En Revisión</p>
                            <h3 class="text-3xl font-bold text-slate-800 dark:text-white">{{ stats.inReview }}</h3>
                        </div>
                        <div class="p-2.5 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 rounded-xl">
                            <AlertCircle class="w-5 h-5" />
                        </div>
                    </div>
                </div>

                <!-- Aprobadas -->
                <div
                    class="rounded-2xl border border-green-200/50 bg-gradient-to-br from-green-50 to-white dark:from-green-900/10 dark:to-zinc-900 dark:border-green-900/30 p-5 shadow-sm relative overflow-hidden">
                    <div
                        class="absolute right-0 top-0 p-4 opacity-5 bg-green-500 rounded-bl-full w-24 h-24 -mr-8 -mt-8 pointer-events-none">
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-semibold text-green-800 dark:text-green-500 mb-1">Aprobadas</p>
                            <h3 class="text-3xl font-bold text-slate-800 dark:text-white">{{ stats.approved }}</h3>
                        </div>
                        <div
                            class="p-2.5 bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400 rounded-xl">
                            <CheckCircle2 class="w-5 h-5" />
                        </div>
                    </div>
                </div>

                <!-- Rechazadas -->
                <div
                    class="rounded-2xl border border-red-200/50 bg-gradient-to-br from-red-50 to-white dark:from-red-900/10 dark:to-zinc-900 dark:border-red-900/30 p-5 shadow-sm relative overflow-hidden">
                    <div
                        class="absolute right-0 top-0 p-4 opacity-5 bg-red-500 rounded-bl-full w-24 h-24 -mr-8 -mt-8 pointer-events-none">
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-semibold text-red-800 dark:text-red-500 mb-1">Rechazadas</p>
                            <h3 class="text-3xl font-bold text-slate-800 dark:text-white">{{ stats.rejected }}</h3>
                        </div>
                        <div class="p-2.5 bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400 rounded-xl">
                            <XCircle class="w-5 h-5" />
                        </div>
                    </div>
                </div>

            </div>

            <!-- CONTENIDO PRINCIPAL: 2 COLUMNAS EN DESKTOP -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Columna Izquierda: Casos Recientes (2/3) -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center justify-between px-1">
                        <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <FileText class="w-5 h-5 text-slate-400" />
                            Gestiones Recientes
                        </h2>
                        <Link href="/customer/warranties"
                            class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 flex items-center">
                            Ver todas
                            <ChevronRight class="w-4 h-4" />
                        </Link>
                    </div>

                    <div v-if="recentWarranties.length === 0"
                        class="rounded-2xl border border-dashed border-slate-300 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-900/50 p-10 text-center">
                        <Package class="w-12 h-12 text-slate-300 dark:text-zinc-700 mx-auto mb-3" />
                        <h3 class="text-base font-semibold text-slate-700 dark:text-slate-300">No hay solicitudes
                            recientes</h3>
                        <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Cuando radiques tu primera garantía,
                            aparecerá aquí para que puedas hacerle seguimiento rápido.</p>
                        <Link href="/customer/warranties/create"
                            class="inline-flex items-center justify-center rounded-lg bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 mt-4 hover:bg-slate-50 dark:hover:bg-zinc-700 transition">
                            Empezar ahora
                        </Link>
                    </div>

                    <!-- Lista de Tarjetas Interactivas -->
                    <div v-else class="space-y-3">
                        <div v-for="warranty in recentWarranties" :key="warranty.id"
                            class="group relative flex items-center justify-between p-4 rounded-2xl border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 hover:border-blue-300 dark:hover:border-blue-900/50 transition-all shadow-sm hover:shadow-md">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-zinc-800 flex flex-col justify-center items-center shrink-0 border border-slate-200 dark:border-zinc-700 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20 transition-colors">
                                    <span
                                        class="text-[10px] font-bold text-slate-400 dark:text-slate-500 leading-none mb-0.5">#</span>
                                    <span class="text-sm font-bold text-slate-800 dark:text-slate-200 leading-none">{{
                                        warranty.customer_sequence }}</span>
                                </div>

                                <div>
                                    <h4 class="font-semibold text-slate-900 dark:text-white line-clamp-1">Barra LED — {{
                                        warranty.model }}</h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span
                                            :class="['inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium', getStatusConfig(warranty.status).bg, getStatusConfig(warranty.status).text]">
                                            <component :is="getStatusConfig(warranty.status).icon" class="w-3 h-3" />
                                            {{ getStatusConfig(warranty.status).label }}
                                        </span>
                                        <span
                                            class="text-xs text-slate-400 flex items-center before:content-['•'] before:mr-2 before:opacity-50">
                                            {{ formatDate(warranty.created_at) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <button
                                            class="p-2 -mr-1 rounded-full text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-700 dark:hover:text-slate-200 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <MoreVertical class="w-5 h-5" />
                                        </button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end" class="w-48">
                                        <DropdownMenuItem as-child>
                                            <Link :href="'/customer/warranties/' + warranty.id"
                                                class="flex items-center gap-2 w-full cursor-pointer">
                                                <Eye class="w-4 h-4 opacity-70" />
                                                Ver detalles
                                            </Link>
                                        </DropdownMenuItem>
                                        <DropdownMenuItem v-if="warranty.status === 'pending'" as-child>
                                            <Link :href="'/customer/warranties/' + warranty.id + '/edit'"
                                                class="flex items-center gap-2 w-full cursor-pointer">
                                                <Pencil class="w-4 h-4 opacity-70" />
                                                Editar solicitud
                                            </Link>
                                        </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </div>

                            <!-- Borde activo on hover izquierdo prop (decorativo) -->
                            <div
                                class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-0 bg-blue-500 rounded-r-full group-hover:h-2/3 transition-all duration-300 opacity-0 group-hover:opacity-100">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Panel de Ayuda (1/3) -->
                <div class="space-y-4">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 px-1">
                        <Headset class="w-5 h-5 text-slate-400" />
                        Centro de Ayuda
                    </h2>

                    <div
                        class="rounded-2xl border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm">
                        <h3 class="font-semibold text-slate-900 dark:text-white mb-2">¿Cómo funciona el proceso de
                            garantía LED?</h3>
                        <ol
                            class="space-y-4 my-6 relative before:absolute before:inset-y-0 before:left-[11px] before:w-px before:bg-slate-200 dark:before:bg-zinc-800">
                            <li class="relative flex items-start gap-3">
                                <div
                                    class="w-6 h-6 rounded-full bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 flex items-center justify-center shrink-0 z-10 text-xs font-bold text-slate-600 dark:text-slate-400">
                                    1</div>
                                <div>
                                    <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Radicación</p>
                                    <p class="text-xs text-slate-500 mt-0.5">Registras la garantía y adjuntas
                                        fotografías, videos y descripción del inconveniente.</p>
                                </div>
                            </li>
                            <li class="relative flex items-start gap-3">
                                <div
                                    class="w-6 h-6 rounded-full bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 flex items-center justify-center shrink-0 z-10 text-xs font-bold text-slate-600 dark:text-slate-400">
                                    2</div>
                                <div>
                                    <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Evaluación de
                                        Evidencias</p>
                                    <p class="text-xs text-slate-500 mt-0.5">Nuestro equipo técnico analiza la
                                        información adjunta.
                                        Tiempo estimado: <strong>2 a 3 días hábiles</strong>.</p>
                                </div>
                            </li>
                            <li class="relative flex items-start gap-3">
                                <div
                                    class="w-6 h-6 rounded-full bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 flex items-center justify-center shrink-0 z-10 text-xs font-bold text-slate-600 dark:text-slate-400">
                                    3</div>
                                <div>
                                    <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Resolución</p>
                                    <p class="text-xs text-slate-500 mt-0.5">Recibirás notificación con el resultado:
                                        aprobación o rechazo de la garantía</p>
                                </div>
                            </li>
                            <li class="relative flex items-start gap-3">
                                <div
                                    class="w-6 h-6 rounded-full bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 flex items-center justify-center shrink-0 z-10 text-xs font-bold text-slate-600 dark:text-slate-400">
                                    4</div>
                                <div>
                                    <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Acumulación de
                                        Garantías Aprobadas</p>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        Las barras LED aprobadas se acumulan en tu cuenta hasta completar un mínimo de
                                        <strong>10 unidades</strong> para despacho agrupado.
                                    </p>
                                </div>
                            </li>
                            <li class="relative flex items-start gap-3">
                                <div
                                    class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 flex items-center justify-center shrink-0 z-10 text-xs font-bold text-blue-600 dark:text-blue-400 shadow-[0_0_0_4px_white] dark:shadow-[0_0_0_4px_#18181b]">
                                    5</div>
                                <div>
                                    <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Envío de Reemplazo
                                    </p>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        Una vez alcanzado el mínimo requerido, gestionamos el envío de las barras LED de
                                        reemplazo.
                                    </p>
                                </div>
                            </li>

                        </ol>

                        <div class="mt-6 pt-5 border-t border-slate-100 dark:border-zinc-800">
                            <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3">
                                Evaluación y Tiempos de Respuesta
                            </h4>

                            <div class="space-y-3">
                                <p
                                    class="text-sm text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-zinc-800/50 p-3 rounded-xl border border-slate-100 dark:border-zinc-800">
                                    La solicitud será evaluada con base en las evidencias adjuntas
                                    (fotografías, videos y descripción del caso).
                                    El tiempo estimado de respuesta es de
                                    <strong>2 a 3 días hábiles</strong>.
                                </p>

                                <p class="text-xs text-slate-500 dark:text-zinc-500">
                                    En caso de aprobación, se solicitará evidencia adicional que demuestre la
                                    inutilización física de la barra LED (proceso de ruptura), como requisito
                                    previo para autorizar el reemplazo.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>