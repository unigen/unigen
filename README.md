[![Build Status](https://travis-ci.org/unigen/unigen.svg?branch=master)](https://travis-ci.org/unigen/unigen)
[![Code Coverage](https://scrutinizer-ci.com/g/unigen/unigen/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/unigen/unigen/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/unigen/unigen/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/unigen/unigen/?branch=master)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%207.0-8892BF.svg)](https://php.net/)

### UniGen
UniGen is a unit test generator for PHP. It automatically generates unit tests for your classes.

### Installation

`composer require --dev unigen/unigen`

### Integrations

Symfony bundle [unigen/unigen-bundle](https://github.com/unigen/unigen-bundle)

### Configuration

* `parentTestCase` - Parent class that will be extend in generated test
* `mockObjectFramework` - Test framework mockery or phpunit
* `testTargetPathPattern` - SUT directory regexp pattern
* `testTargetPathReplacementPattern` - Test directory replacement patter that will be used to generate target test path
* `namespacePattern` - SUT namespace pattern
* `namespaceReplacementPattern` - Test namespace replacement patter that will be used to generate target test namepsace

For example by default target namespace will add `Test` sufix to SUT namespace. So `Organization\ExampleClass` will generate test with namespace `Organization\Test\ExampleClass` in directory `./test/Organization/ExampleClassTest.php`. If you want to change namespace or target test directory just proper regexp in configuration file.




