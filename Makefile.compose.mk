# Works only for project root

project := $(shell git rev-parse --show-toplevel)
sail_dir := $(project)/tools/laravel/sail
sail := $(sail_dir)/vendor/bin/sail

# Application

setup: sail-install build start dependencies-install

sail-install:
	docker run --rm \
		--user "$(shell id -u):$(shell id -g)" \
		--volume $(sail_dir):/var/www/html \
		--workdir /var/www/html \
		laravelsail/php81-composer:latest \
		composer install --ignore-platform-reqs

build:
	$(sail) -f docker-compose.yml -f docker-compose.dev.yml build

dependencies-install:
	$(sail) -f docker-compose.yml -f docker-compose.dev.yml exec application make install

destroy:
	$(sail) -f docker-compose.yml -f docker-compose.dev.yml down --rmi all --volumes --remove-orphans

start:
	$(sail) -f docker-compose.yml -f docker-compose.dev.yml up --detach

stop:
	$(sail) -f docker-compose.yml -f docker-compose.dev.yml stop

restart: stop start

# CI

ci: sail-install
	cp --no-clobber .env.example .env || true
	$(sail) -f docker-compose.ci.yml pull --ignore-pull-failures
	$(sail) -f docker-compose.ci.yml up --abort-on-container-exit
	$(sail) -f docker-compose.ci.yml down --volumes

# Tests

tests:
	$(sail) -f docker-compose.test.yml run --rm application_test bash -c "make test"

# Misc

fix-rights:
	sudo chown -R $(shell whoami) .
	sudo chmod -R 777 storage/
	$(sail) -f docker-compose.yml -f docker-compose.dev.yml php artisan cache:clear
	$(sail) -f docker-compose.yml -f docker-compose.dev.yml composer dump-autoload

stack:
	$(sail) -f docker-compose.yml -f docker-compose.dev.yml config > docker-compose.dev.stack.yml
	$(sail) -f docker-compose.test.yml config > docker-compose.test.stack.yml
	$(sail) -f docker-compose.ci.yml config > docker-compose.ci.stack.yml
