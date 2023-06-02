#!/usr/bin/make

THIS_FILE := $(lastword $(MAKEFILE_LIST))

.PHONY : up-all

.DEFAULT_GOAL := help

help:
	make -pRrq  -f $(THIS_FILE) : 2>/dev/null | awk -v RS= -F: '/^# File/,/^# Finished Make data base/ {if ($$1 !~ "^[#.]") {print $$1}}' | sort | egrep -v -e '^[^[:alnum:]]' -e '^$@$$'
up:
	docker-compose up -d
down:
	docker-compose down
init:
	docker-compose exec php bash -c "composer install"
	docker-compose exec php bash -c "php artisan key:generate"
	docker-compose exec php bash -c "php artisan migrate:fresh --seed"
	docker-compose exec php bash -c "composer install --working-dir=tools/csfixer"
	docker-compose exec php bash -c "composer install --working-dir=tools/psalm"
test:
	docker-compose exec php sh -c "php artisan test"
