build:
	docker-compose up -d --build
	docker-compose exec web php ../config/setup.php

start:
	docker-compose up -d

clean:
	docker-compose down -v --rmi all --remove-orphans

re: clean build

.PHONY: build start clean re
