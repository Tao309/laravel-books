test:
	bin/phpunit
cover:
	XDEBUG_MODE=coverage bin/phpunit  --coverage-html="coverage"
php:
	docker exec -it laravel-books-php bash
build:
	docker-compose up -d --build
up:
	docker-compose up -d
debug-build:
	docker compose -f docker-compose.debug.yml up -d --build
debug:
	docker compose -f docker-compose.debug.yml up -d
stop:
	docker compose stop
down:
	docker compose down
cs-fix:
	./vendor/bin/pint
ide-helper:
	php artisan ide-helper:generate && php artisan ide-helper:meta
ide-helper-models:
	php artisan ide-helper:models -RW
post-update-cmd:
	composer run post-update-cmd
swagger-generate:
	php artisan l5-swagger:generate
