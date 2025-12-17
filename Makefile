up:
	./vendor/bin/sail up -d

down:
	./vendor/bin/sail down

stop:
	./vendor/bin/sail stop

restart:
	./vendor/bin/sail down && ./vendor/bin/sail up

shell:
	./vendor/bin/sail shell

artisan:
	./vendor/bin/sail artisan $(cmd)

migrate:
	./vendor/bin/sail artisan migrate

test:
	./vendor/bin/sail test
