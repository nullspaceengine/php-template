.PHONY = all
.DEFAULT_GOAL := help

test:
	docker compose run --build --rm app ./vendor/bin/phpunit $(test)

lint:
	docker compose run --build --rm app phpcbf --standard=Drupal --extensions=php,module,inc,install,test,profile,theme,css,info,txt,md,yml /file/to/drupal/example_module

build:
	docker build -t php-docker-image-test --progress plain --no-cache --target test .

docs:
	docker run --rm -v ${PWD}:/data phpdoc/phpdoc:3 -- -d ./src -t ./docs
