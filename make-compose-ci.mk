sail := ./vendor/bin/sail

setup:
	docker run --rm \
		-u "$(shell id -u):$(shell id -g)" \
		-v $(shell pwd):/var/www/html \
		-w /var/www/html \
		laravelsail/php80-composer:latest \
		composer install --ignore-platform-reqs
	$(sail) build
	$(sail) up -d
	$(sail) bash -c "make install"
