<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import {
    ArrowLeft,
    Clock,
    Package,
    FileText,
    MapPin,
    Calendar,
    Hash,
    AlertCircle,
    MessageSquare,
    Paperclip,
    CheckCircle2,
    XCircle,
    User,
    Pencil,
} from 'lucide-vue-next';

interface WarrantyNote {
    id: number;
    note: string;
    created_at: string;
    user: { name: string };
}

interface WarrantyAttachment {
    id: number;
    file_path: string;
}

interface Warranty {
    id: number;
    model: string;
    invoice_number: string;
    internal_code?: string;
    quantity: number;
    status: string;
    failure_description: string;
    shipping_city: string;
    shipping_address: string;
    purchase_date: string;
    damage_date: string;
    factory_id?: number;
    factory_sequence?: number;
    created_at: string;
    updated_at: string;
    notes: WarrantyNote[];
    attachments: WarrantyAttachment[];
}

const props = defineProps<{ warranty: Warranty }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Inicio', href: '/customer/dashboard' },
    { title: 'Mis Garantías', href: '/customer/warranties' },
    { title: `Garantía #${props.warranty.id}`, href: '#' },
];

const getStatusConfig = (status: string) => {
    const map: Record<string, { label: string; card: string; badge: string; icon: any }> = {
        pending: {
            label: 'Pendiente de revisión',
            card: 'border-amber-200 bg-amber-50 dark:border-amber-800/40 dark:bg-amber-900/10',
            badge: 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
            icon: Clock,
        },
        approved: {
            label: 'Aprobada',
            card: 'border-green-200 bg-green-50 dark:border-green-800/40 dark:bg-green-900/10',
            badge: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
            icon: CheckCircle2,
        },
        rejected: {
            label: 'Rechazada',
            card: 'border-red-200 bg-red-50 dark:border-red-800/40 dark:bg-red-900/10',
            badge: 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
            icon: XCircle,
        },
        in_review: {
            label: 'En revisión',
            card: 'border-blue-200 bg-blue-50 dark:border-blue-800/40 dark:bg-blue-900/10',
            badge: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            icon: AlertCircle,
        },
    };
    return map[status?.toLowerCase()] ?? map['pending'];
};

const formatDate = (date: string) =>
    new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });

const formatDateTime = (date: string) =>
    new Date(date).toLocaleString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });

const statusConfig = getStatusConfig(props.warranty.status);
</script>

<template>

    <Head :title="`Garantía #${warranty.id}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4 md:p-8 max-w-4xl mx-auto w-full">

            <!-- ===== HEADER ===== -->
            <div class="flex items-center gap-4">
                <Link href="/customer/warranties"
                    class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-zinc-800 transition text-slate-600 dark:text-slate-300 shrink-0"
                    aria-label="Volver al listado">
                    <ArrowLeft class="w-5 h-5" />
                </Link>

                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white truncate">
                        Garantía <span class="text-slate-400 dark:text-zinc-500">#{{ warranty.id }}</span>
                    </h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Radicada el {{ formatDate(warranty.created_at) }}
                    </p>
                </div>

                <!-- Badge de estado -->
                <span :class="[
                    'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium shrink-0',
                    statusConfig.badge,
                ]">
                    <component :is="statusConfig.icon" class="w-4 h-4" />
                    {{ statusConfig.label }}
                </span>
            </div>

            <!-- ===== BANNER DE ESTADO ===== -->
            <div :class="[
                'rounded-xl border p-4 flex items-start gap-3',
                statusConfig.card,
            ]">
                <component :is="statusConfig.icon" class="w-5 h-5 mt-0.5 shrink-0 opacity-70" />
                <div>
                    <p class="font-semibold text-sm text-slate-800 dark:text-slate-200">
                        Estado: {{ statusConfig.label }}
                    </p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Te notificaremos por correo ante cualquier actualización de tu solicitud.
                    </p>
                </div>
            </div>

            <!-- ===== GRID PRINCIPAL ===== -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Columna izquierda (2/3) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Sección: Producto -->
                    <section
                        class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800 shadow-sm p-5 md:p-6 space-y-4">
                        <h2
                            class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            <Package class="w-4 h-4" />
                            Información del Producto
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mb-1">Modelo</p>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ warranty.model }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mb-1">Cantidad</p>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ warranty.quantity
                                    }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mb-1 flex items-center gap-1">
                                    <Hash class="w-3 h-3" /> Número de Factura
                                </p>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{
                                    warranty.invoice_number }}</p>
                            </div>
                            <div v-if="warranty.internal_code">
                                <p class="text-xs text-slate-400 dark:text-slate-500 mb-1">Código Interno</p>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{
                                    warranty.internal_code }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mb-1 flex items-center gap-1">
                                    <Calendar class="w-3 h-3" /> Fecha de Compra
                                </p>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{
                                    formatDate(warranty.purchase_date) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mb-1 flex items-center gap-1">
                                    <Calendar class="w-3 h-3" /> Fecha del Daño
                                </p>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{
                                    formatDate(warranty.damage_date) }}</p>
                            </div>
                        </div>
                    </section>

                    <!-- Sección: Descripción de la falla -->
                    <section
                        class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800 shadow-sm p-5 md:p-6 space-y-3">
                        <h2
                            class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            <AlertCircle class="w-4 h-4" />
                            Descripción de la Falla
                        </h2>
                        <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                            {{ warranty.failure_description }}
                        </p>
                    </section>

                    <!-- Sección: Línea de Tiempo (Notas del equipo) -->
                    <section v-if="warranty.notes && warranty.notes.length > 0"
                        class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800 shadow-sm p-5 md:p-6 space-y-5">
                        <h2
                            class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 border-b border-slate-100 dark:border-zinc-800 pb-3">
                            <MessageSquare class="w-4 h-4" />
                            Historial del Caso
                            <span
                                class="ml-auto text-xs font-normal normal-case bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 px-2 py-0.5 rounded-full">
                                {{ warranty.notes.length }} nota{{ warranty.notes.length !== 1 ? 's' : '' }}
                            </span>
                        </h2>

                        <!-- Contenedor Timeline -->
                        <div class="relative pl-1">
                            <!-- Línea vertical central -->
                            <div class="absolute left-[15px] top-2 bottom-2 w-px bg-slate-200 dark:bg-zinc-800"></div>

                            <ul class="space-y-6">
                                <li v-for="(note, index) in warranty.notes" :key="note.id" class="relative pl-10">
                                    <!-- Punto en la línea -->
                                    <div class="absolute left-[-5px] top-1 w-10 h-10 flex items-center justify-center">
                                        <div
                                            class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/50 border-[3px] border-white dark:border-zinc-900 shadow-sm flex items-center justify-center z-10 hidden sm:flex">
                                            <User class="w-4 h-4 text-blue-600 dark:text-blue-400" />
                                        </div>
                                        <div
                                            class="w-3 h-3 rounded-full bg-blue-500 border-2 border-white dark:border-zinc-900 z-10 sm:hidden">
                                        </div>
                                    </div>

                                    <!-- Tarjeta del Note -->
                                    <div
                                        class="bg-slate-50 dark:bg-zinc-800/40 border border-slate-100 dark:border-zinc-800/60 rounded-xl p-4 transition-all hover:bg-slate-100 dark:hover:bg-zinc-800 group">
                                        <div
                                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                                            <p
                                                class="text-sm font-semibold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                                {{ note.user?.name ?? 'Equipo de soporte' }}
                                                <span v-if="index === 0"
                                                    class="text-[10px] uppercase font-bold tracking-wider rounded-sm bg-blue-100 text-blue-700 px-1.5 py-0.5">MÁS
                                                    RECIENTE</span>
                                            </p>
                                            <p
                                                class="text-[11px] font-medium text-slate-400 dark:text-slate-500 flex items-center gap-1 shrink-0">
                                                <Clock class="w-3 h-3" />
                                                {{ formatDateTime(note.created_at) }}
                                            </p>
                                        </div>
                                        <p
                                            class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed group-hover:text-slate-900 dark:group-hover:text-amber-50 transition-colors">
                                            {{ note.note }}
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </section>

                    <!-- Sección: Archivos adjuntos (si existen) -->
                    <section v-if="warranty.attachments && warranty.attachments.length > 0"
                        class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800 shadow-sm p-5 md:p-6 space-y-3">
                        <h2
                            class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            <Paperclip class="w-4 h-4" />
                            Archivos Adjuntos
                        </h2>
                        <ul class="space-y-2">
                            <li v-for="file in warranty.attachments" :key="file.id"
                                class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 dark:bg-zinc-800/50 text-sm text-slate-700 dark:text-slate-300">
                                <Paperclip class="w-4 h-4 text-slate-400 shrink-0" />
                                <span class="truncate">{{ file.file_path.split('/').pop() }}</span>
                            </li>
                        </ul>
                    </section>

                </div>

                <!-- Columna derecha (1/3): Resumen y envío -->
                <div class="space-y-6">

                    <!-- Tarjeta: Resumen -->
                    <section
                        class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800 shadow-sm p-5 space-y-4">
                        <h2
                            class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            <FileText class="w-4 h-4" />
                            Resumen
                        </h2>

                        <dl class="space-y-3">
                            <div class="flex justify-between items-start gap-2">
                                <dt class="text-xs text-slate-400 dark:text-slate-500 shrink-0">ID Radicado</dt>
                                <dd class="text-sm font-bold text-slate-800 dark:text-slate-200">#{{ warranty.id }}</dd>
                            </div>
                            <div class="flex justify-between items-start gap-2">
                                <dt class="text-xs text-slate-400 dark:text-slate-500 shrink-0">Fecha radicado</dt>
                                <dd class="text-xs text-slate-600 dark:text-slate-400 text-right">{{
                                    formatDate(warranty.created_at) }}</dd>
                            </div>
                            <div class="flex justify-between items-start gap-2">
                                <dt class="text-xs text-slate-400 dark:text-slate-500 shrink-0">Última actualización
                                </dt>
                                <dd class="text-xs text-slate-600 dark:text-slate-400 text-right">{{
                                    formatDate(warranty.updated_at) }}</dd>
                            </div>
                            <div v-if="warranty.factory_sequence" class="flex justify-between items-start gap-2">
                                <dt class="text-xs text-slate-400 dark:text-slate-500 shrink-0">Secuencia fábrica</dt>
                                <dd class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{
                                    String(warranty.factory_sequence).padStart(4, '0') }}</dd>
                            </div>
                        </dl>
                    </section>

                    <!-- Tarjeta: Información de envío -->
                    <section
                        class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800 shadow-sm p-5 space-y-4">
                        <h2
                            class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            <MapPin class="w-4 h-4" />
                            Destino de Envío
                        </h2>

                        <dl class="space-y-3">
                            <div>
                                <dt class="text-xs text-slate-400 dark:text-slate-500 mb-0.5">Ciudad</dt>
                                <dd class="text-sm font-medium text-slate-800 dark:text-slate-200">{{
                                    warranty.shipping_city }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-400 dark:text-slate-500 mb-0.5">Dirección</dt>
                                <dd class="text-sm text-slate-700 dark:text-slate-300">{{ warranty.shipping_address }}
                                </dd>
                            </div>
                        </dl>
                    </section>

                    <!-- Acciones -->
                    <Link v-if="warranty.status === 'pending'" :href="`/customer/warranties/${warranty.id}/edit`"
                        class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors shadow-sm">
                        <Pencil class="w-4 h-4" />
                        Editar solicitud
                    </Link>

                    <Link href="/customer/warranties"
                        class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-zinc-700 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors">
                        <ArrowLeft class="w-4 h-4" />
                        Volver al listado
                    </Link>

                </div>
            </div>

        </div>
    </AppLayout>
</template>
