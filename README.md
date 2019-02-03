### UniGen
UniGen is a unit test generator for PHP. It automatically generates unit tests for your classes.

### Installation

`composer require --dev unitgen/unitgen`

### Integrations

Symfony bundle unitgen/unitgen-bundle

### Configuration

`parentTestCase` - Parent class that will be extend in generated test
`mockObjectFramework` - Test framework mockery or phpunit
`testTargetPathPattern` - SUT directory regexp pattern
`testTargetPathReplacementPattern` - Test directory replacement patter that will be used to generate target test path
`namespacePattern` - SUT namespace pattern
`namespaceReplacementPattern` - Test namespace replacement patter that will be used to generate target test namepsace

For example by default target namespace will add `Test` sufix to SUT namespace. So `Organization\ExampleClass` will generate test with namespace `Organization\Test\ExampleClass` in directory `./test/Organization/ExampleClassTest.php`. If you want to change namespace or target test directory just proper regexp in configuration file.




