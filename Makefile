php :=

.DEFAULT_GOAL := help
.PHONY: help
help: ## Affiche cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: install
install: vendor/autoload.php ## Installe les différentes dépendances
	APP_ENV=prod APP_DEBUG=0 $(php) composer install --no-dev --optimize-autoloader

.PHONY: test
test: vendor/autoload.php ## Lance les unitaires phpunit
	./vendor/bin/phpunit

.PHONY: lint
lint: vendor/autoload.php ## Analyse le code
	./vendor/bin/phpstan analyse src tests

.PHONY: format
format: ## Formate le code
	./vendor/bin/phpcbf
	./vendor/bin/php-cs-fixer fix

.PHONY: refactor
refactor: ## Reformate le code avec rector
	./vendor/bin/rector process --clear-cache

# -----------------------------------
# Dépendances
# -----------------------------------
vendor/autoload.php: composer.lock
	$(php) composer install