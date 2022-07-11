include Makefile.compose.mk

install:
	composer install
	cp --no-clobber .env.example .env || true
	php artisan key:generate
	php artisan migrate:refresh

test:
	php artisan test --parallel -vvv
test-coverage:
	XDEBUG_MODE=coverage php artisan test --parallel --coverage-clover storage/logs/clover.xml

lint:
	composer exec phpcs --verbose && composer exec pint -- --test 2>/dev/null
lint-fix:
	composer exec phpcbf --verbose && composer exec pint 2>/dev/null
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
