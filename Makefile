.PHONY: help

help: ## Print help.
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n\nTargets:\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-10s\033[0m %s\n", $$1, $$2 }' $(MAKEFILE_LIST)

migrate:
	cd app/Commands && php -f migrate.php

migrate\:fresh:
	cd app/Commands && php -f migrate.php fresh

migrate\:down:
	cd app/Commands && php -f migrate.php down

serve:
	php -S localhost:5000 -t public
