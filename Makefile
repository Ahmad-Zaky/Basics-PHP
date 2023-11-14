.PHONY: help

Command := $(firstword $(MAKECMDGOALS))
Arguments := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
CONTAINER_PHP=flow_app
CONTAINER_DATABASE=flow_mysql
VOLUME_DATABASE=
DOMAIN=${APP_HOST}:${APP_PORT}

help: ## Print help.
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n\nTargets:\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-10s\033[0m %s\n", $$1, $$2 }' $(MAKEFILE_LIST)

make\:model:
	cd app/Commands && php -f make.php Model $(Arguments) 

make\:migration:
	cd app/Commands && php -f make.php Migration $(Arguments) 

make\:controller:
	cd app/Commands && php -f make.php Controller $(Arguments) 

make\:view:
	cd app/Commands && php -f make.php View $(Arguments) 

make\:config:
	cd app/Commands && php -f make.php Config $(Arguments) 

make\:middleware:
	cd app/Commands && php -f make.php Middleware $(Arguments) 

make\:provider:
	cd app/Commands && php -f make.php Provider $(Arguments) 

make\:command:
	cd app/Commands && php -f make.php Command $(Arguments) 

migrate:
	cd app/Commands && php -f migrate.php

migrate\:fresh:
	cd app/Commands && php -f migrate.php fresh

migrate\:down:
	cd app/Commands && php -f migrate.php down

version:
	cd app/Commands && php -f version.php

test:
	./vendor/bin/pest $(group)

serve:
	php -S ${DOMAIN} -t public

ps: ## Show containers.
	@sudo docker compose ps

build: ## Build all containers
	@sudo docker build --no-cache .

build-start: ## Build all containers
	@sudo docker compose up --build -d

start: ## Start all containers
	@sudo docker compose up --force-recreate -d

fresh: stop destroy build start ## Destroy & recreate all containers

stop: ## Stop all containers
	@sudo docker compose stop

restart: stop start ## Restart all containers

destroy: stop ## Destroy all containers
	@sudo docker compose down
	@if [ "$(shell sudo docker volume ls --filter name=${VOLUME_DATABASE} --format {{.Name}})" ]; then \
		@sudo docker volume rm ${VOLUME_DATABASE}; \
	fi

logs: ## Print all docker logs
	sudo docker compose logs -f

logs-php: ## Print all php container logs
	sudo docker logs ${CONTAINER_PHP}

logs-database: ## Print all php container logs
	sudo docker logs ${CONTAINER_DATABASE}

ssh-php: ## SSH inside php container
	sudo docker exec -it ${CONTAINER_PHP} /bin/bash

ssh-database: ## SSH inside database container
	sudo docker exec -it ${CONTAINER_DATABASE} /bin/bash

exec-php: ## SSH inside php container with bash
	sudo docker exec -it ${CONTAINER_PHP} /bin/bash

%::
	@true