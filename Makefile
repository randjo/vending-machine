COMPOSE = docker compose

.PHONY: help up down restart ps logs backend-logs frontend-logs db-logs rebuild clean

help:
	@echo "Available commands:"
	@echo "  make up            Start containers in background"
	@echo "  make down          Stop and remove containers"
	@echo "  make restart       Restart all services"
	@echo "  make ps            Show running containers"
	@echo "  make logs          Follow all service logs"
	@echo "  make backend-logs  Follow backend logs only"
	@echo "  make frontend-logs Follow frontend logs only"
	@echo "  make db-logs       Follow mysql logs only"
	@echo "  make rebuild       Rebuild images and start"
	@echo "  make clean         Stop and remove containers + volumes"

up:
	$(COMPOSE) up -d

down:
	$(COMPOSE) down

restart:
	$(COMPOSE) restart

ps:
	$(COMPOSE) ps

logs:
	$(COMPOSE) logs -f

backend-logs:
	$(COMPOSE) logs -f backend

frontend-logs:
	$(COMPOSE) logs -f frontend

db-logs:
	$(COMPOSE) logs -f mysql

rebuild:
	$(COMPOSE) up -d --build

clean:
	$(COMPOSE) down -v
