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
	@echo "PHPUNIT -- UNIT TESTS"
	@vendor/bin/phpunit

.PHONY: unit-coverage
unit-coverage: ## Launch PHPUnit unit coverage raport generation
	@echo "PHPUNIT -- UNIT COVERAGE"
	@vendor/bin/phpunit --coverage-text --coverage-clover=coverage.clover

.DEFAULT_GOAL := help
