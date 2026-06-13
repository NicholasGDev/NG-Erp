.PHONY: help up down build shell artisan composer logs test

COMPOSE_DEV=docker compose -f docker-compose.dev.yml
COMPOSE_PROD=docker compose -f docker-compose.prod.yml

## ── DEV ─────────────────────────────────────────────────────────────────────

help:
	@echo ""
	@echo "  ngErp — comandos Make"
	@echo "  ────────────────────────────────────────"
	@echo "  up        Sobe o ambiente DEV"
	@echo "  down      Derruba o ambiente DEV"
	@echo "  build     Reconstroi as imagens DEV"
	@echo "  shell     Abre shell no container app (DEV)"
	@echo "  artisan   Executa artisan  ex: make artisan cmd='migrate'"
	@echo "  composer  Executa composer ex: make composer cmd='install'"
	@echo "  logs      Exibe logs do app"
	@echo "  test      Roda PHPUnit no container"
	@echo "  prod-up   Sobe o ambiente PROD"
	@echo "  prod-down Derruba o ambiente PROD"
	@echo ""

up:
	${COMPOSE_DEV} up -d

down:
	${COMPOSE_DEV} down

build:
	${COMPOSE_DEV} build --no-cache

shell:
	${COMPOSE_DEV} exec app bash

artisan:
	${COMPOSE_DEV} exec app php artisan ${cmd}

composer:
	${COMPOSE_DEV} exec app composer ${cmd}

logs:
	${COMPOSE_DEV} logs -f app

test:
	${COMPOSE_DEV} exec app php artisan test

migrate:
	${COMPOSE_DEV} exec app php artisan migrate

fresh:
	${COMPOSE_DEV} exec app php artisan migrate:fresh --seed

## ── PROD ─────────────────────────────────────────────────────────────────────

prod-build:
	${COMPOSE_PROD} build --no-cache

prod-up:
	${COMPOSE_PROD} up -d

prod-down:
	${COMPOSE_PROD} down
