.DEFAULT_GOAL := help

SAIL = ./vendor/bin/sail

# ─────────────────────────────────────────────
# Hilfe
# ─────────────────────────────────────────────

.PHONY: help
help: ## Zeigt alle verfügbaren Befehle
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) \
		| awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

# ─────────────────────────────────────────────
# Setup
# ─────────────────────────────────────────────

.PHONY: install
install: ## Vollständiger Setup (composer, .env, key, migrate, npm, build)
	composer setup

.PHONY: env
env: ## .env aus .env.example erstellen
	cp .env.example .env

# ─────────────────────────────────────────────
# Entwicklung (lokal)
# ─────────────────────────────────────────────

.PHONY: dev
dev: ## Alle Dev-Server starten (PHP, Queue, Logs, Vite)
	composer dev

.PHONY: serve
serve: ## Nur PHP-Dev-Server starten
	php artisan serve

.PHONY: vite
vite: ## Nur Vite-Dev-Server starten
	npm run dev

# ─────────────────────────────────────────────
# Docker / Laravel Sail
# ─────────────────────────────────────────────

.PHONY: sail-up
sail-up: ## Docker-Container starten (detached)
	$(SAIL) up -d

.PHONY: sail-down
sail-down: ## Docker-Container stoppen
	$(SAIL) down

.PHONY: sail-build
sail-build: ## Docker-Image neu bauen (ohne Cache)
	$(SAIL) build --no-cache

.PHONY: sail-restart
sail-restart: ## Docker-Container neu starten
	$(SAIL) restart

.PHONY: sail-shell
sail-shell: ## Shell im Container öffnen
	$(SAIL) shell

.PHONY: sail-logs
sail-logs: ## Container-Logs live verfolgen
	$(SAIL) logs -f

# ─────────────────────────────────────────────
# Datenbank
# ─────────────────────────────────────────────

.PHONY: migrate
migrate: ## Migrationen ausführen
	php artisan migrate

.PHONY: migrate-fresh
migrate-fresh: ## Datenbank zurücksetzen und neu migrieren + seeden
	php artisan migrate:fresh --seed

.PHONY: seed
seed: ## Datenbank seeden
	php artisan db:seed

.PHONY: tinker
tinker: ## Laravel Tinker (interaktive REPL)
	php artisan tinker

# ─────────────────────────────────────────────
# Build / Assets
# ─────────────────────────────────────────────

.PHONY: build
build: ## Frontend für Produktion bauen
	npm run build

.PHONY: preview
preview: ## Produktions-Build vorschauen
	npm run preview

# ─────────────────────────────────────────────
# Code-Qualität
# ─────────────────────────────────────────────

.PHONY: test
test: ## Tests ausführen (PHPUnit)
	composer test

.PHONY: format
format: ## Code formatieren (Laravel Pint)
	composer format

.PHONY: format-check
format-check: ## Formatierung prüfen (ohne Änderungen)
	composer format:check

.PHONY: analyse
analyse: ## Statische Code-Analyse (PHPStan)
	composer analyse

# ─────────────────────────────────────────────
# Cache / Maintenance
# ─────────────────────────────────────────────

.PHONY: cache-clear
cache-clear: ## Alle Caches leeren (cache, config, route, view)
	php artisan cache:clear
	php artisan config:clear
	php artisan route:clear
	php artisan view:clear

.PHONY: storage-link
storage-link: ## Storage-Symlink erstellen
	php artisan storage:link

.PHONY: queue-work
queue-work: ## Queue Worker starten
	php artisan queue:work
