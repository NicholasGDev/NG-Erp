/**
 * useTheme.js — Controla o tema light/dark do Caronte ERP.
 *
 * Persiste a preferência em localStorage.
 * Aplica data-theme="light|dark" no <html> para o DaisyUI e ativa
 * o variant dark: do Tailwind v4 (configurado via @custom-variant).
 */
import { ref, watchEffect } from 'vue';

const STORAGE_KEY = 'caronte_theme';

// Detecta preferência inicial: localStorage → prefers-color-scheme → light
function getInitial() {
    const stored = localStorage.getItem(STORAGE_KEY);
    if (stored === 'light' || stored === 'dark') return stored;
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

const theme = ref(getInitial());

// Aplica no <html> e persiste sempre que o tema mudar
watchEffect(() => {
    document.documentElement.setAttribute('data-theme', theme.value);
    localStorage.setItem(STORAGE_KEY, theme.value);
});

export function useTheme() {
    const isDark = () => theme.value === 'dark';

    function toggle() {
        theme.value = theme.value === 'light' ? 'dark' : 'light';
    }

    function set(value) {
        if (value === 'light' || value === 'dark') theme.value = value;
    }

    return { theme, isDark, toggle, set };
}
