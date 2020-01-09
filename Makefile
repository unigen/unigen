.PHONY: help
help: ## List of available make commands
	@echo "Available commands:"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-14s\033[0m %s\n", $$1, $$2}'

.PHONY: install
install: ## Install PHP dependencies
	@composer install

.PHONY: unit
unit: ## Launch PHPUnit unit tests
	@echo "UNIT TESTS"
	@vendor/bin/phpunit

.PHONY: build
build: ## Build bin/unigen
	@vendor/bin/box build

.PHONY: lint
lint: ## Lint source files
	@vendor/bin/phpstan analyze

.DEFAULT_GOAL := help
