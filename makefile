init: docker-down-clear app-clear docker-pull docker-build docker-up app-init
up: docker-up
down: docker-down
restart: down up
test: unit-test functional-test

app-init:app-permission composer-install pause app-migrations app-fixtures

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

app-clear:
	docker run --rm -v ${PWD}:/app -w /app  alpine sh -c 'rm -rf var/cache/* var/log/*'

app-permission:
	docker run --rm -v ${PWD}:/app -w /app alpine chmod 777 bin var/cache var/log

unit-test:
	docker-compose run --rm php-cli composer test -- --testsuite=Unit

functional-test:
	docker-compose run --rm php-cli composer test -- --testsuite=Functional

app-migrations:
	docker-compose run --rm php-cli composer app migrations:migrate -- --no-interaction

app-fixtures:
	docker-compose run --rm php-cli composer app fixtures:load