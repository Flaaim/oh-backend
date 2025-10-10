init: docker-down-clear docker-pull docker-build docker-up app-init
up: docker-up
down: docker-down
restart: down up
test: unit-test

app-init:app-permission composer-install pause app-migrations

pause:
	sleep 10

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphan

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

composer-install:
	docker-compose run --rm php-cli composer install

app-permission:
	docker run --rm -v ${PWD}:/app -w /app alpine chmod 777 bin var/cache

unit-test:
	docker-compose run --rm php-cli composer test

app-migrations:
	docker-compose run --rm php-cli composer app migrations:migrate -- --no-interaction