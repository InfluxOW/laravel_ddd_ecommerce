include make-compose-ci.mk

install:
	composer install
	cp --no-clobber .env.example .env || true
	php artisan key:generate
	php artisan migrate:fresh --seed

test:
	php artisan test --parallel -vvv
test-coverage:
	php artisan test --parallel -vvv --coverage-clover build/logs/clover.xml

lint:
	composer exec phpcs --verbose 2>/dev/null
lint-fix:
	composer exec phpcbf --verbose 2>/dev/null
analyse:
	composer exec phpstan analyse --verbose --no-cache -- --memory-limit=-1 2>/dev/null

seed:
	php artisan db:seed

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
