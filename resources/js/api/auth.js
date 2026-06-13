/**
 * auth.js — Estado de autenticação JWT + helpers
 * Armazena o token em localStorage e expõe funções de login/logout/refresh.
 */
import { reactive, computed } from 'vue';
import http from './http.js';

const TOKEN_KEY = 'ng_jwt';

/* ── Estado reativo ──────────────────────────────────────────────── */
const state = reactive({
    token: localStorage.getItem(TOKEN_KEY) ?? null,
    user:  JSON.parse(localStorage.getItem('ng_user') ?? 'null'),
});

/* ── Getters ─────────────────────────────────────────────────────── */
export const isAuthenticated = computed(() => !!state.token);
export const currentUser     = computed(() => state.user);

/* ── Helpers internos ────────────────────────────────────────────── */
function setSession({ token, user }) {
    state.token = token;
    state.user  = user;
    localStorage.setItem(TOKEN_KEY,  token);
    localStorage.setItem('ng_user',  JSON.stringify(user));
}

function clearSession() {
    state.token = null;
    state.user  = null;
    localStorage.removeItem(TOKEN_KEY);
    localStorage.removeItem('ng_user');
}

/* ── API calls ───────────────────────────────────────────────────── */
export async function login(email, password, remember = false) {
    const { data } = await http.post('/auth/login', { email, password, remember });
    setSession({ token: data.token, user: data.user });
    return data.user;
}

export async function register(payload) {
    const { data } = await http.post('/auth/register', payload);
    setSession({ token: data.token, user: data.user });
    return data.user;
}

export async function logout() {
    try {
        await http.post('/auth/logout');
    } finally {
        clearSession();
    }
}

export async function refreshToken() {
    const { data } = await http.post('/auth/refresh');
    setSession({ token: data.token, user: data.user });
    return data.token;
}

export async function fetchMe() {
    const { data } = await http.get('/auth/me');
    state.user = data;
    localStorage.setItem('ng_user', JSON.stringify(data));
    return data;
}

/* ── Token getter para o interceptor do http.js ──────────────────── */
export function getToken() {
    return state.token;
}
