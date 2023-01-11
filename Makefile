include Makefile.compose.mk

infection := composer exec infection -- --threads=max --verbose --only-covering-test-cases --only-covered

APPLICATION_COMPOSER_CACHE ?= false
CLEAN_APPLICATION_PHPSTAN_CACHE ?= false

prepare:
	${APPLICATION_COMPOSER_CACHE} || composer install
	npm install
	make prepare-env
	php artisan key:generate
install: prepare
	php artisan migrate:fresh

infection:
	$(infection)
infection-coverage:
	$(infection) --skip-initial-tests --coverage=storage/logs
test:
	php artisan test --parallel -vvv
test-coverage:
	php -d pcov.enabled=1 artisan test --parallel --coverage-clover=storage/logs/clover.xml --coverage-xml=storage/logs/coverage-xml --log-junit=storage/logs/junit.xml

insights:
	php artisan insights app --summary
insights-fix:
	php artisan insights --fix
lint:
	composer exec phpcs --verbose && composer exec pint -- --test 2>/dev/null && composer validate --strict && composer normalize --dry-run
lint-fix:
	composer exec phpcbf --verbose; composer exec pint 2>/dev/null; composer normalize
rector:
	composer exec rector process -- --dry-run
rector-fix:
	composer exec rector process
analyse:
	if [ "${CLEAN_APPLICATION_PHPSTAN_CACHE}" = "true" ] || git diff --name-only | grep modulite -q; then composer exec phpstan clear-result-cache; fi
	composer exec phpstan analyse --verbose -- --memory-limit=-1 2>/dev/null
deptrac:
	composer exec deptrac

seed:
	php artisan db:seed --class=App\\Domains\\Common\\Database\\Seeders\\DatabaseSeeder

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
