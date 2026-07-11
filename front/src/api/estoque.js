/**
 * estoque.js — All stock API endpoints
 * Mapped from routes/api_erp_estoque.php
 */
import http from './http.js';

const BASE = '/estoque';

/* ── Warehouses ─────────────────────────────────────────────────────── */
export const warehouses = {
    index:   (params) => http.get(`${BASE}/warehouses`, { params }),
    show:    (id)     => http.get(`${BASE}/warehouses/${id}`),
    store:   (data)   => http.post(`${BASE}/warehouses`, data),
    update:  (id, data) => http.put(`${BASE}/warehouses/${id}`, data),
    destroy: (id)     => http.delete(`${BASE}/warehouses/${id}`),
};

/* ── Suppliers ───────────────────────────────────────────────────────── */
export const suppliers = {
    index:   (params) => http.get(`${BASE}/suppliers`, { params }),
    show:    (id)     => http.get(`${BASE}/suppliers/${id}`),
    store:   (data)   => http.post(`${BASE}/suppliers`, data),
    update:  (id, data) => http.put(`${BASE}/suppliers/${id}`, data),
    destroy: (id)     => http.delete(`${BASE}/suppliers/${id}`),
};

/* ── Products ────────────────────────────────────────────────────────── */
export const products = {
    index:   (params) => http.get(`${BASE}/products`, { params }),
    show:    (id)     => http.get(`${BASE}/products/${id}`),
    store:   (data)   => http.post(`${BASE}/products`, data),
    update:  (id, data) => http.put(`${BASE}/products/${id}`, data),
    destroy: (id)     => http.delete(`${BASE}/products/${id}`),
};

/* ── Purchase Orders ─────────────────────────────────────────────────── */
export const purchaseOrders = {
    index:   (params) => http.get(`${BASE}/purchase-orders`, { params }),
    show:    (id)     => http.get(`${BASE}/purchase-orders/${id}`),
    store:   (data)   => http.post(`${BASE}/purchase-orders`, data),
    update:  (id, data) => http.put(`${BASE}/purchase-orders/${id}`, data),
    destroy: (id)     => http.delete(`${BASE}/purchase-orders/${id}`),
};

/* ── Stock Movements (Kardex) ────────────────────────────────────────── */
export const stockMovements = {
    index:   (params) => http.get(`${BASE}/stock-movements`, { params }),
    show:    (id)     => http.get(`${BASE}/stock-movements/${id}`),
    store:   (data)   => http.post(`${BASE}/stock-movements`, data),
};

/* ── Physical Inventories ────────────────────────────────────────────── */
export const physicalInventories = {
    index:   (params) => http.get(`${BASE}/inventories`, { params }),
    show:    (id)     => http.get(`${BASE}/inventories/${id}`),
    store:   (data)   => http.post(`${BASE}/inventories`, data),
    update:  (id, data) => http.put(`${BASE}/inventories/${id}`, data),
    destroy: (id)     => http.delete(`${BASE}/inventories/${id}`),
};
