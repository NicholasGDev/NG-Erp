#!/bin/sh
# =============================================================================
# entrypoint.dev.sh — bootstrap automático do backend em DEV
# Roda composer install, key:generate e migrate antes de iniciar o PHP-FPM.
# =============================================================================
set -e

echo ""
echo "══════════════════════════════════════════════════"
echo "  Caronte ERP — Bootstrap DEV"
echo "══════════════════════════════════════════════════"

# ── 1. Composer install ───────────────────────────────────────────────────────
echo ""
echo "── [1/4] Dependências Composer ───────────────────"
if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-interaction --prefer-dist --ansi
else
    echo "  vendor/ já existe — pulando."
fi

# ── 2. Criar .env se não existir ─────────────────────────────────────────────
echo ""
echo "── [2/4] Arquivo .env ────────────────────────────"
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo "  .env criado a partir de .env.example"
    else
        echo "  AVISO: .env e .env.example não encontrados!"
    fi
else
    echo "  .env já existe — pulando."
fi

# ── 3. Gerar APP_KEY se vazio ────────────────────────────────────────────────
echo ""
echo "── [3/4] APP_KEY ─────────────────────────────────"
APP_KEY_VALUE=$(grep "^APP_KEY=" .env 2>/dev/null | cut -d'=' -f2)
if [ -z "$APP_KEY_VALUE" ]; then
    php artisan key:generate --ansi
    echo "  APP_KEY gerado."
else
    echo "  APP_KEY já configurado — pulando."
fi

# ── 4. Migrations ────────────────────────────────────────────────────────────
echo ""
echo "── [4/4] Migrations ──────────────────────────────"
php artisan migrate --force --ansi

echo ""
echo "══════════════════════════════════════════════════"
echo "  Bootstrap concluído — iniciando PHP-FPM..."
echo "══════════════════════════════════════════════════"
echo ""

exec php-fpm
