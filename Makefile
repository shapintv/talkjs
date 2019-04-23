.PHONY: ${TARGETS}
.DEFAULT_GOAL := help

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

test: ## Launch tests
	@vendor/bin/phpunit

cs-lint: ## Verify check styles
	-vendor/bin/php-cs-fixer fix --dry-run --using-cache=no --verbose --diff

cs-fix: ## Apply Check styles
	-vendor/bin/php-cs-fixer fix --using-cache=no --verbose --diff
