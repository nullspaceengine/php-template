.PHONY: *
.DEFAULT_GOAL := help

PHPCS_CONTAINER ?= pathtoproject/phpcs-drupal
PHPCS_CONTAINER_VERSION ?= v1.5.5

## Run the code against the PHP Unit tests defined in the tests directory.
test:
	docker compose run --build --rm app ./vendor/bin/phpunit $(test)

## Auto-format the code based on the DrupalCS coding stanards.
auto-format:
	docker run --rm -v $(PWD):/tmp $(PHPCS_CONTAINER):$(PHPCS_CONTAINER_VERSION) \
	phpcbf -vv --standard=Drupal --extensions=php,module,inc,install,test,profile,theme,css,info,txt,md,yml \
	src

## Not everything can be fixed automatically. Use this to lint the code.
lint:
	docker run --rm -v $(PWD):/tmp $(PHPCS_CONTAINER):$(PHPCS_CONTAINER_VERSION) \
	phpcs --standard=Drupal --extensions=php,module,inc,install,test,profile,theme,css,info,txt,md,yml \
	src

build:
	docker build -t php-docker-image-test --progress plain --no-cache --target test .

## Generate the docs in the docs directory.
docs:
	docker run --rm -v ${PWD}:/data phpdoc/phpdoc:3 -d ./src
