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
	$(sail) build

dependencies-install:
	$(sail) exec application make install

destroy:
	$(sail) down --rmi all --volumes --remove-orphans

start:
	$(sail) up --detach

stop:
	$(sail) stop

restart: stop start

# CI

ci: sail-install
	cp --no-clobber .env.example .env || true
	$(sail) -f docker-compose.ci.yml build
	$(sail) -f docker-compose.ci.yml up --abort-on-container-exit
	$(sail) -f docker-compose.ci.yml down --volumes --remove-orphans
