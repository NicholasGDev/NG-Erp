#!/bin/sh
# =============================================================================
# entrypoint.prod.sh — bootstrap automático do backend em PROD
# Roda migrate --force antes de iniciar o Supervisor (Nginx + PHP-FPM).
# Composer install e otimizações já são feitos no build (Dockerfile.prod).
# =============================================================================
set -e

echo ""
echo "══════════════════════════════════════════════════"
echo "  Caronte ERP — Bootstrap PROD"
echo "══════════════════════════════════════════════════"

# ── Migrations ───────────────────────────────────────────────────────────────
echo ""
echo "── Migrations ────────────────────────────────────"
php artisan migrate --force --ansi

echo ""
echo "══════════════════════════════════════════════════"
echo "  Bootstrap concluído — iniciando Supervisor..."
echo "══════════════════════════════════════════════════"
echo ""

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
