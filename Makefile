help:
	@echo "Available commands:"
	@echo ""
	@echo "help                        List of available make commands"
	@echo "install                     Install PHP dependencies"
	@echo "unit                        Launch PHPUnit unit tests"
	@echo "unit-coverage               Launch PHPUnit unit coverage raport generation"

install:
	composer install

unit:
	@echo "PHPUNIT -- UNIT TESTS"
	vendor/bin/phpunit

unit-coverage:
	@echo "PHPUNIT -- UNIT COVERAGE"
	vendor/bin/phpunit --coverage-text --coverage-clover=coverage.clover
