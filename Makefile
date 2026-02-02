.PHONY: up down init

up:
	docker-compose up -d

down:
	docker-compose down

init: up
	docker-compose exec frontend bash -c "php init --env=Development --overwrite=n && composer install && php yii migrate --interactive=0"
	docker-compose exec -T mysql mysql -uyii2advanced -psecret yii2advanced < db/demo_seed.sql
