## — MY PHP FRAMEWORK MAKEFILE ———————————————————————————————————————————————

.DEFAULT_GOAL = help
.PHONY: help
help: ## Print self-documented Makefile
	@grep -E '(^[.a-zA-Z_-]+[^:]+:.*##.*?$$)|(^#{2})' $(MAKEFILE_LIST) \
	| awk 'BEGIN {FS = "## "}; \
		{ \
			split($$1, command, ":"); \
			target=command[1]; \
			description=$$2; \
			# --- space --- \
			if (target=="##") \
				printf "\033[33m%s\n", ""; \
			# --- title --- \
			else if (target=="" && description!="") \
				printf "\033[33m\n%s\n", description; \
			# --- command + description --- \
			else if (target!="" && description!="") \
				printf "\033[32m  %-30s \033[0m%s\n", target, description; \
			# --- do nothing --- \
			else \
				; \
		}'
	@echo

## — PHP —————————————————————————————————————————————————————————————————————

.PHONY: start
start: ## Start the server
	 php -S localhost:3000 -t web web/front.php

## — COMPOSER ————————————————————————————————————————————————————————————————

.PHONY: install
install: ## Full installation
	php bin/composer install

## — TEST & QUALITY ——————————————————————————————————————————————————————————

.PHONY: tests
tests: ## Run PHPUnit
	php vendor/bin/phpunit

.PHONY: list
list: ## Run PHPUnit with a checklist of the tests
	php vendor/bin/phpunit --testdox

.PHONY: coverage
coverage: ## Run PHPUnit coverage (with Xdebug)
	XDEBUG_MODE=coverage php vendor/bin/phpunit --coverage-html=cov/

