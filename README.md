# Translator Buro

CRM для переводчиков (Yii2 Advanced). Запуск через Docker.

## Требования

- Docker, Docker Compose
- Свободные порты: 20080, 21080

## Запуск

```bash
cd /path/project
docker-compose up -d
```

Инициализация (один раз):

```bash
docker-compose exec frontend bash -c "php init --env=Development --overwrite=n && composer install && php yii migrate --interactive=0"
docker-compose exec mysql mysql -uyii2advanced -psecret yii2advanced < db/demo_seed.sql
```

## Доступ

- Frontend: http://localhost:20080
- Backend: http://localhost:21080
