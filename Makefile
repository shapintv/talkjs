.PHONY: ${TARGETS}
.DEFAULT_GOAL := help

DIR := ${CURDIR}
QA_IMAGE := jakzal/phpqa:php8.0-alpine

define say_red =
    echo "\033[31m$1\033[0m"
endef

define say_green =
    echo "\033[32m$1\033[0m"
endef

define say_yellow =
    echo "\033[33m$1\033[0m"
endef

help:
	@echo "\033[33mUsage:\033[0m"
	@echo "  make [command]"
	@echo ""
	@echo "\033[33mAvailable commands:\033[0m"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort \
		| awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[32m%s\033[0m___%s\n", $$1, $$2}' | column -ts___

install: ## Install all applications
	@$(call say_green,"Installing PHP dependencies")
	@composer install

cs-lint: ## Verify check styles
	@docker run --rm -v $(DIR):/project -w /project $(QA_IMAGE) php-cs-fixer fix --diff-format udiff --dry-run -vvv

cs-fix: ## Apply Check styles
	@docker run --rm -v $(DIR):/project -w /project $(QA_IMAGE) php-cs-fixer fix --diff-format udiff -vvv

phpstan: ## Run PHPStan
	@docker run --rm -v $(DIR):/project -w /project $(QA_IMAGE) phpstan analyse

phpunit: ## Run PHPUnit
	@rm -rf ./tests/app/var ./tests/app/cache
	@$(call say_green,"==\> Launch unit tests")
	@vendor/bin/phpunit

test: phpunit cs-lint phpstan ## Launch tests
