/**
 * http.js — Instância Axios configurada para o backend Laravel
 * Centraliza baseURL, headers e interceptors.
 */
import axios from 'axios';

const http = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
    withCredentials: true,
});

/* ── Request interceptor: injeta CSRF token do meta tag ─────────────── */
http.interceptors.request.use((config) => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (token) config.headers['X-CSRF-TOKEN'] = token;
    return config;
});

/* ── Response interceptor: trata erros globais ──────────────────────── */
http.interceptors.response.use(
    (response) => response,
    (error) => {
        const status = error.response?.status;
        if (status === 401) window.location.href = '/login';
        if (status === 422) return Promise.reject(error.response.data);
        return Promise.reject(error);
    },
);

export default http;
