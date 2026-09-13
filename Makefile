COMPOSE ?= docker compose
DEV_COMPOSE ?= $(COMPOSE) -f compose.yaml -f compose.dev.yaml
.DEFAULT_GOAL := help

.PHONY: help init permissions up up-all dev-up dev-stop dev-logs down ps logs build migrate lint

help:
	@echo "make init    Initialise local SQLite and permissions"
	@echo "make up      Start only the web UI"
	@echo "make up-all  Start web UI and scheduler"
	@echo "make dev-up  Start web UI with live-mounted source"
	@echo "make dev-stop Stop the live-development web UI"
	@echo "make ps      Show container status"
	@echo "make logs    Follow web logs"
	@echo "make down    Stop the local stack"

## Initialise a local SQLite database and apply migrations.
init: build
	$(COMPOSE) run --rm web php yii app/start
	$(MAKE) permissions
	$(COMPOSE) run --rm web php yii migrate --interactive=0

## Give the container's web user ownership of its persistent local directories.
permissions:
	$(COMPOSE) run --rm --user root web chown -R www-data:www-data db runtime web/assets

## Start only the web UI. This deliberately does not run device cron jobs.
up:
	$(COMPOSE) up -d web

## Start the complete production-like stack, including the scheduler.
up-all:
	$(COMPOSE) up -d

## Start the web UI with source mounted from the working tree.
dev-up:
	$(DEV_COMPOSE) up -d web

dev-stop:
	$(DEV_COMPOSE) stop web

dev-logs:
	$(DEV_COMPOSE) logs --follow --tail=100 web

down:
	$(COMPOSE) down

ps:
	$(COMPOSE) ps

logs:
	$(COMPOSE) logs --follow --tail=100 web

build:
	$(COMPOSE) build

migrate:
	$(COMPOSE) run --rm web php yii migrate --interactive=0

lint:
	find . -name '*.php' -not -path './vendor/*' -print0 | xargs -0 -n1 php -l
