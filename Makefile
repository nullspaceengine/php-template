.PHONY = all
.DEFAULT_GOAL := help

PHPCS_CONTAINER ?= pathtoproject/phpcs-drupal
PHPCS_CONTAINER_VERSION ?= v1.5.5

test:
	docker compose run --build --rm app ./vendor/bin/phpunit $(test)

auto-format:
	docker run --rm -v $(PWD):/tmp $(PHPCS_CONTAINER):$(PHPCS_CONTAINER_VERSION) \
	phpcbf -vv --standard=Drupal --extensions=php,module,inc,install,test,profile,theme,css,info,txt,md,yml \
	src

lint:
	docker run --rm -v $(PWD):/tmp $(PHPCS_CONTAINER):$(PHPCS_CONTAINER_VERSION) \
	phpcs --standard=Drupal --extensions=php,module,inc,install,test,profile,theme,css,info,txt,md,yml \
	src

build:
	docker build -t php-docker-image-test --progress plain --no-cache --target test .

docs:
	docker run --rm -v ${PWD}:/data phpdoc/phpdoc:3 -d ./src -t ./docs
