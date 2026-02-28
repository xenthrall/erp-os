import type { ComputedRef, Ref } from 'vue'
import { computed, onMounted, ref } from 'vue'
import type { Appearance, ResolvedAppearance } from '@/types'

export type { Appearance, ResolvedAppearance }

export type UseThemeReturn = {
    theme: Ref<Appearance>
    resolvedTheme: ComputedRef<ResolvedAppearance>
    setTheme: (value: Appearance) => void
}

/**
 * Aplica la clase dark al <html>
 */
function applyTheme(value: Appearance): void {
    if (typeof window === 'undefined') return

    if (value === 'system') {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
        document.documentElement.classList.toggle('dark', prefersDark)
    } else {
        document.documentElement.classList.toggle('dark', value === 'dark')
    }
}

function getStoredTheme(): Appearance | null {
    if (typeof window === 'undefined') return null
    return localStorage.getItem('theme') as Appearance | null
}

function setCookie(name: string, value: string, days = 365) {
    if (typeof document === 'undefined') return

    const maxAge = days * 24 * 60 * 60
    document.cookie = `${name}=${value};path=/;max-age=${maxAge};SameSite=Lax`
}

function listenToSystemChanges(currentTheme: Ref<Appearance>) {
    if (typeof window === 'undefined') return

    const media = window.matchMedia('(prefers-color-scheme: dark)')

    media.addEventListener('change', () => {
        if (currentTheme.value === 'system') {
            applyTheme('system')
        }
    })
}

/**
 * Ejecutar una sola vez en main.ts
 */
export function initializeTheme(): void {
    const saved = getStoredTheme() ?? 'system'
    applyTheme(saved)
}

const theme = ref<Appearance>('system')

export function useTheme(): UseThemeReturn {
    onMounted(() => {
        const saved = getStoredTheme()

        if (saved) {
            theme.value = saved
        }

        applyTheme(theme.value)
        listenToSystemChanges(theme)
    })

    const resolvedTheme = computed<ResolvedAppearance>(() => {
        if (theme.value === 'system') {
            return window.matchMedia('(prefers-color-scheme: dark)').matches
                ? 'dark'
                : 'light'
        }

        return theme.value
    })

    function setTheme(value: Appearance) {
        theme.value = value

        localStorage.setItem('theme', value)
        setCookie('theme', value)

        applyTheme(value)
    }

    return {
        theme,
        resolvedTheme,
        setTheme,
    }
}