<script setup lang="ts">
import { useToast } from '@/composables/useToast';
import { usePage } from '@inertiajs/vue3';
import { watch, onMounted } from 'vue';
import { CheckCircle2, XCircle, Info, AlertTriangle, X } from 'lucide-vue-next';

const { toasts, dismiss, success, error, info } = useToast();
const page = usePage<{ flash: { success?: string; error?: string; info?: string } }>();

// Detectar flash al cargar y en cada navegación de Inertia
function processFlash() {
    const flash = page.props.flash;
    if (flash?.success) success(flash.success);
    if (flash?.error)   error(flash.error);
    if (flash?.info)    info(flash.info);
}

onMounted(processFlash);
watch(() => page.props.flash, processFlash, { deep: true });

const icons = {
    success: CheckCircle2,
    error:   XCircle,
    info:    Info,
    warning: AlertTriangle,
};

const styles = {
    success: {
        card: 'bg-white dark:bg-zinc-900 border-l-4 border-green-500',
        icon: 'text-green-500',
        title: 'text-green-700 dark:text-green-400',
        bar: 'bg-green-500',
    },
    error: {
        card: 'bg-white dark:bg-zinc-900 border-l-4 border-red-500',
        icon: 'text-red-500',
        title: 'text-red-700 dark:text-red-400',
        bar: 'bg-red-500',
    },
    info: {
        card: 'bg-white dark:bg-zinc-900 border-l-4 border-blue-500',
        icon: 'text-blue-500',
        title: 'text-blue-700 dark:text-blue-400',
        bar: 'bg-blue-500',
    },
    warning: {
        card: 'bg-white dark:bg-zinc-900 border-l-4 border-amber-500',
        icon: 'text-amber-500',
        title: 'text-amber-700 dark:text-amber-400',
        bar: 'bg-amber-500',
    },
};

const labels = {
    success: 'Éxito',
    error:   'Error',
    info:    'Información',
    warning: 'Atención',
};
</script>

<template>
    <Teleport to="body">
        <div
            aria-live="polite"
            class="fixed bottom-5 right-5 z-[9999] flex flex-col-reverse gap-3 w-full max-w-sm pointer-events-none"
        >
            <TransitionGroup
                name="toast"
                tag="div"
                class="flex flex-col gap-3"
            >
                <div
                    v-for="toast in toasts"
                    :key="toast.id"
                    :class="[
                        'relative overflow-hidden rounded-xl shadow-xl border border-slate-200 dark:border-zinc-700',
                        'flex items-start gap-3 p-4 pointer-events-auto',
                        'min-w-[300px]',
                        styles[toast.type].card,
                    ]"
                >
                    <!-- Icono -->
                    <component
                        :is="icons[toast.type]"
                        :class="['w-5 h-5 mt-0.5 shrink-0', styles[toast.type].icon]"
                    />

                    <!-- Texto -->
                    <div class="flex-1 min-w-0">
                        <p :class="['text-xs font-semibold uppercase tracking-wide mb-0.5', styles[toast.type].title]">
                            {{ labels[toast.type] }}
                        </p>
                        <p class="text-sm text-slate-700 dark:text-slate-300 leading-snug break-words">
                            {{ toast.message }}
                        </p>
                    </div>

                    <!-- Botón cerrar -->
                    <button
                        @click="dismiss(toast.id)"
                        class="shrink-0 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors"
                        aria-label="Cerrar notificación"
                    >
                        <X class="w-4 h-4" />
                    </button>

                    <!-- Barra de progreso animada -->
                    <div
                        class="absolute bottom-0 left-0 h-0.5 rounded-full"
                        :class="styles[toast.type].bar"
                        :style="{ animationDuration: `${toast.duration}ms` }"
                        style="animation: toast-progress linear forwards;"
                    />
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>

<style scoped>
/* Entrada / salida del toast */
.toast-enter-active {
    transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.toast-leave-active {
    transition: all 0.25s ease-in;
}
.toast-enter-from {
    opacity: 0;
    transform: translateX(110%);
}
.toast-leave-to {
    opacity: 0;
    transform: translateX(110%);
}
.toast-move {
    transition: transform 0.3s ease;
}

/* Barra de progreso */
@keyframes toast-progress {
    from { width: 100%; }
    to   { width: 0%; }
}
</style>
