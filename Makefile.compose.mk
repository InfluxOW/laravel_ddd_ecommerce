# Works only for project root

project := $(shell git rev-parse --show-toplevel)

sail_dir := $(project)/tools/sail
sail := $(sail_dir)/vendor/bin/sail

sail_ci := $(sail) -f docker-compose.ci.yml

sail_test := $(sail) -f docker-compose.test.yml
sail_test_bash := $(sail_test) run --rm application_test bash -c

sail_composer_image = $(shell cat .env.infrastructure.example | grep SAIL_COMPOSER_IMAGE | sed "s/SAIL_COMPOSER_IMAGE=//")

SAIL_COMPOSER_CACHE ?= false
# Application

setup: sail-install build start dependencies-install
	$(sail) php artisan app:refresh

sail-install:
	${SAIL_COMPOSER_CACHE} || docker run --rm \
		--user "$(shell id -u):$(shell id -g)" \
		--volume $(sail_dir):/var/www/html \
		--workdir /var/www/html \
		$(sail_composer_image) \
		composer install --ignore-platform-reqs

build:
	$(sail) build --pull

dependencies-install:
	$(sail) bash -c "make install"

destroy:
	$(sail) down --rmi all --volumes --remove-orphans

start:
	$(sail) up --detach --build

stop:
	$(sail) stop

restart: stop start

# CI

ci-local: prepare-env sail-install
	$(sail_ci) up --abort-on-container-exit --attach application_ci --build --pull missing
	$(sail_ci) down --volumes

ci: prepare-env sail-install
	$(sail_ci) pull --ignore-pull-failures
	$(sail_ci) up --abort-on-container-exit --attach application_ci
	$(sail_ci) down --volumes

# Tests

infections:
	$(sail_test_bash) "make infection"
infections-coverage:
	$(sail_test_bash) "make infection-coverage"
tests:
	$(sail_test_bash) "make test"
tests-debug:
	$(sail_test_bash) "make test-debug"
tests-profile:
	$(sail_test_bash) "make test-profile"
tests-coverage:
	$(sail_test_bash) "make test-coverage"

# Misc

prepare-env:
	test -f .env || sort -u -t '=' -k 1,1 .env.infrastructure.example .env.example | grep -v '^#' > .env

fix-rights:
	sudo chown -R $(shell whoami) .
	sudo chmod -R 777 storage/
	$(sail) php artisan cache:clear
	$(sail) composer dump-autoload

fix-phpstan:
	cat /dev/null > ./vendor/nesbot/carbon/extension.neon

stack:
	$(sail) config > docker-compose.dev.stack.yml
	$(sail_test) config > docker-compose.test.stack.yml
	$(sail_ci) config > docker-compose.ci.stack.yml
