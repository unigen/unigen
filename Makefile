help:
	@echo "Available commands:"
	@echo ""
	@echo "  help              list of available make commands"
	@echo "  install           installs all dependencies (composer, npm, bower)"
	@echo "  unit-test         launch PHPUnit unit tests"

install:
	composer install

unit:
	@echo "PHPUNIT -- UNIT TESTS"
	vendor/bin/phpunit
