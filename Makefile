include Makefile.compose.mk

infection := composer exec infection -- --threads=max --verbose --only-covering-test-cases --only-covered

APPLICATION_COMPOSER_CACHE ?= false

install:
	${APPLICATION_COMPOSER_CACHE} || composer install
	npm install
	make prepare-env
	php artisan key:generate
	php artisan migrate:fresh

infection:
	$(infection)
infection-coverage:
	$(infection) --skip-initial-tests --coverage=storage/logs
test:
	php artisan test --parallel -vvv
test-coverage:
	XDEBUG_MODE=coverage php artisan test --parallel --coverage-clover storage/logs/clover.xml --coverage-xml=storage/logs/coverage-xml --log-junit=storage/logs/junit.xml

deptrac:
	composer exec deptrac
insights:
	php artisan insights --summary
insights-fix:
	php artisan insights --fix
lint:
	composer exec phpcs --verbose && composer exec pint -- --test 2>/dev/null && composer validate --strict && composer normalize --dry-run
lint-fix:
	composer exec phpcbf --verbose; composer exec pint 2>/dev/null; composer normalize
analyse:
	composer exec phpstan analyse --verbose -- --memory-limit=-1 2>/dev/null

seed:
	php artisan db:seed --class=App\\Domains\\Generic\\Database\\Seeders\\DatabaseSeeder

docs:
	php artisan ide-helper:eloquent
	php artisan ide-helper:generate
	php artisan ide-helper:meta
	php artisan ide-helper:models --no-interaction --write --smart-reset

clear:
	php artisan route:clear
	php artisan view:clear
	php artisan cache:clear
	php artisan config:clear
	php artisan optimize:clear
