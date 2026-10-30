sail-up:
	./vendor/bin/sail up -d

sail-down:
	./vendor/bin/sail down

sail-stop:
	./vendor/bin/sail stop

sail-restart:
	./vendor/bin/sail down && ./vendor/bin/sail up -d

sail-shell:
	./vendor/bin/sail shell

sail-artisan:
	./vendor/bin/sail artisan $(cmd)

sail-migrate:
	./vendor/bin/sail artisan migrate

sail-test:
	./vendor/bin/sail test

up:
	docker compose up -d

migrate:
	docker compose exec laravel.test php artisan migrate

test:
	docker compose exec app php artisan test

artisan:
	docker compose exec app php artisan $(cmd)
