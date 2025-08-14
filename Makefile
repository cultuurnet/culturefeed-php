.PHONY: up down build bash install

up:
	docker-compose up -d

down:
	docker-compose down

build:
	docker-compose up -d --build

bash:
	docker-compose exec php bash

install:
	docker-compose exec php composer install