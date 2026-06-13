/**
 * http.js — Instância Axios configurada para o backend Laravel
 * Injeta JWT Bearer token e faz refresh automático em 401.
 */
import axios from 'axios';

const http = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
});

/* ── Request interceptor: injeta JWT Bearer token ───────────────────── */
http.interceptors.request.use((config) => {
    const token = localStorage.getItem('ng_jwt');
    if (token) config.headers['Authorization'] = `Bearer ${token}`;
    return config;
});

/* ── Response interceptor: refresh automático em 401 ────────────────── */
let isRefreshing = false;
let refreshQueue = [];

function processQueue(error, token = null) {
    refreshQueue.forEach(({ resolve, reject }) => error ? reject(error) : resolve(token));
    refreshQueue = [];
}

http.interceptors.response.use(
    (response) => response,
    async (error) => {
        const status  = error.response?.status;
        const origReq = error.config;

        // Ignora rotas de auth para não entrar em loop
        const isAuthRoute = origReq?.url?.startsWith('/auth/');

        if (status === 401 && !isAuthRoute && !origReq._retry) {
            if (isRefreshing) {
                // Enfileira as requisições enquanto já está refreshing
                return new Promise((resolve, reject) => {
                    refreshQueue.push({ resolve, reject });
                }).then((token) => {
                    origReq.headers['Authorization'] = `Bearer ${token}`;
                    return http(origReq);
                });
            }

            origReq._retry   = true;
            isRefreshing      = true;

            try {
                const { data } = await http.post('/auth/refresh');
                const newToken  = data.token;
                localStorage.setItem('ng_jwt',  newToken);
                localStorage.setItem('ng_user', JSON.stringify(data.user));
                http.defaults.headers.common['Authorization'] = `Bearer ${newToken}`;
                processQueue(null, newToken);
                origReq.headers['Authorization'] = `Bearer ${newToken}`;
                return http(origReq);
            } catch (refreshError) {
                processQueue(refreshError);
                localStorage.removeItem('ng_jwt');
                localStorage.removeItem('ng_user');
                window.location.href = '/login';
                return Promise.reject(refreshError);
            } finally {
                isRefreshing = false;
            }
        }

        if (status === 422) return Promise.reject(error.response.data);

        return Promise.reject(error);
    },
);

export default http;
