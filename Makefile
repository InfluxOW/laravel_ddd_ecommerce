include make-compose-ci.mk

install:
	composer install
	cp -n .env.example .env || true
	php artisan key:generate
	php artisan migrate:fresh --seed

test:
	php artisan test
test-coverage:
	php artisan test -vvv --coverage-clover build/logs/clover.xml

lint:
	composer exec phpcs -v 2>/dev/null
lint-fix:
	composer exec phpcbf -v 2>/dev/null
analyse:
	composer exec phpstan analyse -v -- --memory-limit=-1 2>/dev/null

seed:
	php artisan db:seed

docs:
	php artisan ide-helper:eloquent
	php artisan ide-helper:generate
	php artisan ide-helper:meta
	php artisan ide-helper:models -n -W

clear:
	php artisan route:clear
	php artisan view:clear
	php artisan cache:clear
	php artisan config:clear
	php artisan optimize:clear
