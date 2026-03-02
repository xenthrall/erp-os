import { ref } from 'vue';

export type ToastType = 'success' | 'error' | 'info' | 'warning';

export interface Toast {
    id: number;
    type: ToastType;
    message: string;
    duration: number;
}

const toasts = ref<Toast[]>([]);
let idCounter = 0;

export function useToast() {
    function show(message: string, type: ToastType = 'info', duration = 4000) {
        const id = ++idCounter;
        toasts.value.push({ id, type, message, duration });

        setTimeout(() => {
            dismiss(id);
        }, duration);
    }

    function dismiss(id: number) {
        toasts.value = toasts.value.filter((t) => t.id !== id);
    }

    function success(message: string, duration?: number) {
        show(message, 'success', duration);
    }

    function error(message: string, duration?: number) {
        show(message, 'error', duration);
    }

    function info(message: string, duration?: number) {
        show(message, 'info', duration);
    }

    function warning(message: string, duration?: number) {
        show(message, 'warning', duration);
    }

    return { toasts, show, dismiss, success, error, info, warning };
}
