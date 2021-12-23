# Works only for project root

project=$(shell git rev-parse --show-toplevel)
sail_dir := $(project)/tools/laravel/sail
sail := $(sail_dir)/vendor/bin/sail

setup: sail-install build start dependencies-install

sail-install:
	docker run --rm \
		--user "$(shell id -u):$(shell id -g)" \
		--volume $(sail_dir):/var/www/html \
		--workdir /var/www/html \
		laravelsail/php80-composer:latest \
		composer install --ignore-platform-reqs

build:
	$(sail) build

start:
	$(sail) up --detach --remove-orphans

dependencies-install:
	$(sail) bash -c "make install"

destroy:
	$(sail) down --rmi all --volumes --remove-orphans

stop:
	$(sail) stop

restart: stop start
