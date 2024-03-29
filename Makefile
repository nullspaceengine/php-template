.PHONY = all
.DEFAULT_GOAL := help

test:
	docker compose run --build --rm app ./vendor/bin/phpunit $(test)

build:
	docker build -t php-docker-image-test --progress plain --no-cache --target test .
