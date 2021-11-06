# Works only for project root

sail_dir := $(shell pwd)/tools/laravel/sail
sail := $(sail_dir)/vendor/bin/sail

setup:
	docker run --rm \
		-u "$(shell id -u):$(shell id -g)" \
		-v $(sail_dir):/var/www/html \
		-w /var/www/html \
		laravelsail/php80-composer:latest \
		composer install --ignore-platform-reqs
	$(sail) build
	$(sail) up -d
	$(sail) bash -c "make install"
