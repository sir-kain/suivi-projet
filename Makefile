stop-server:
	symfony server:stop

stop-docker:
	docker-compose down

start-docker:
	docker-compose up -d

start-server:
	symfony serve -d

start-client:
	yarn dev-server

down: stop-server stop-docker

up: start-docker start-server start-client
