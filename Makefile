.PHONY: help

Command := $(firstword $(MAKECMDGOALS))
Arguments := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))

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
	php -S localhost:5000 -t public

%::
	@true