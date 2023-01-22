start:
	docker-compose down && docker-compose up -d --force-recreate

build:
	docker-compose build

build-no-cache:
	docker-compose build --no-cache

stop:
	docker-compose down

