/**
 * useTheme.js — Controla o tema light/dark do Caronte ERP.
 *
 * Singleton: o efeito é criado apenas uma vez. Qualquer componente pode
 * chamar useTheme() e obterá a mesma instância reativa.
 */
import { ref, watch } from 'vue';

const STORAGE_KEY = 'caronte_theme';

function getInitial() {
    const stored = localStorage.getItem(STORAGE_KEY);
    if (stored === 'light' || stored === 'dark') return stored;
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

const theme = ref(getInitial());

// Aplica imediatamente no <html>
function applyTheme(value) {
    document.documentElement.setAttribute('data-theme', value);
    localStorage.setItem(STORAGE_KEY, value);
}

// Aplica o tema inicial assim que o módulo é carregado
applyTheme(theme.value);

// Reage a mudanças (ex: clique no toggle)
watch(theme, applyTheme);

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
