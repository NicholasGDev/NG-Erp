/**
 * estoque.js — Todos os endpoints da API de estoque
 * Mapeados a partir de routes/api_erp_estoque.php
 */
import http from './http.js';

const BASE = '/estoque';

/* ── Armazéns ────────────────────────────────────────────────────────── */
export const armazens = {
    index:   (params) => http.get(`${BASE}/armazens`, { params }),
    show:    (id)     => http.get(`${BASE}/armazens/${id}`),
    store:   (data)   => http.post(`${BASE}/armazens`, data),
    update:  (id, data) => http.put(`${BASE}/armazens/${id}`, data),
    destroy: (id)     => http.delete(`${BASE}/armazens/${id}`),
};

/* ── Fornecedores ────────────────────────────────────────────────────── */
export const fornecedores = {
    index:   (params) => http.get(`${BASE}/fornecedores`, { params }),
    show:    (id)     => http.get(`${BASE}/fornecedores/${id}`),
    store:   (data)   => http.post(`${BASE}/fornecedores`, data),
    update:  (id, data) => http.put(`${BASE}/fornecedores/${id}`, data),
    destroy: (id)     => http.delete(`${BASE}/fornecedores/${id}`),
};

/* ── Produtos ────────────────────────────────────────────────────────── */
export const produtos = {
    index:   (params) => http.get(`${BASE}/produtos`, { params }),
    show:    (id)     => http.get(`${BASE}/produtos/${id}`),
    store:   (data)   => http.post(`${BASE}/produtos`, data),
    update:  (id, data) => http.put(`${BASE}/produtos/${id}`, data),
    destroy: (id)     => http.delete(`${BASE}/produtos/${id}`),
};

/* ── Pedidos de Compra ───────────────────────────────────────────────── */
export const pedidosCompra = {
    index:   (params) => http.get(`${BASE}/pedidos-compra`, { params }),
    show:    (id)     => http.get(`${BASE}/pedidos-compra/${id}`),
    store:   (data)   => http.post(`${BASE}/pedidos-compra`, data),
    update:  (id, data) => http.put(`${BASE}/pedidos-compra/${id}`, data),
    destroy: (id)     => http.delete(`${BASE}/pedidos-compra/${id}`),
};

/* ── Movimentações de Estoque (Kardex) ───────────────────────────────── */
export const movimentacoes = {
    index:   (params) => http.get(`${BASE}/movimentacoes`, { params }),
    show:    (id)     => http.get(`${BASE}/movimentacoes/${id}`),
    store:   (data)   => http.post(`${BASE}/movimentacoes`, data),
};

/* ── Inventários ─────────────────────────────────────────────────────── */
export const inventarios = {
    index:   (params) => http.get(`${BASE}/inventarios`, { params }),
    show:    (id)     => http.get(`${BASE}/inventarios/${id}`),
    store:   (data)   => http.post(`${BASE}/inventarios`, data),
    update:  (id, data) => http.put(`${BASE}/inventarios/${id}`, data),
    destroy: (id)     => http.delete(`${BASE}/inventarios/${id}`),
};
