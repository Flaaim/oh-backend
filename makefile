init: docker-down-clear docker-pull docker-build docker-up
up: docker-up
down: docker-down
restart: down up
test: unit-test

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
	ocker-compose run --rm php-cli composer install

unit-test:
	docker-compose run --rm php-cli composer test