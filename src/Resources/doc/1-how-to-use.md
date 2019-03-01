### How to use

Run command from cli

```bash
bin/test-generator ./src/TestGenerator.php
```

Will produce

```php
<?php

namespace UniGen\Test;

use UniGen\Config;
use UniGen\Renderer\RendererInterface;
use UniGen\FileSystem\FileSystemInterface;
use UniGen\Sut\SutProviderInterface;
use Mockery;
use Mockery\MockInterface as MockObject;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use UniGen\TestGenerator;

class TestGeneratorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var Config|MockObject */
    private $config;

    /** @var RendererInterface|MockObject */
    private $renderer;

    /** @var FileSystemInterface|MockObject */
    private $fileSystem;

    /** @var SutProviderInterface|MockObject */
    private $sutProvider;

    /** @var TestGenerator */
    private $sut;

    /**
     * {@inheritdoc}
    */
    public function setUp()
    {
        $this->config = Mockery::mock(Config::class);
        $this->renderer = Mockery::mock(RendererInterface::class);
        $this->fileSystem = Mockery::mock(FileSystemInterface::class);
        $this->sutProvider = Mockery::mock(SutProviderInterface::class);

        $this->sut = new TestGenerator(
            $this->config,
            $this->renderer,
            $this->fileSystem,
            $this->sutProvider    
        );
    }

    public function testGenerate()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }
}
```

Run `bin/test-generator` to get more help if needed

```
Usage:
  unigen:generate [options] [--] <path>

Arguments:
  path                                                           

Options:
  -t, --testCase=TESTCASE                                        
  -p, --pathPattern=PATHPATTERN                                  
  -f, --mockFramework=MOCKFRAMEWORK                              
  -b, --templateName=TEMPLATENAME                                
  -d, --templateDirPath=TEMPLATEDIRPATH                          
  -l, --namespacePattern=NAMESPACEPATTERN                        
  -z, --pathPatternReplacement=PATHPATTERNREPLACEMENT            
  -x, --namespacePatternReplacement=NAMESPACEPATTERNREPLACEMENT  
  -h, --help                                                     Display this help message
  -q, --quiet                                                    Do not output any message
  -V, --version                                                  Display this application version
      --ansi                                                     Force ANSI output
      --no-ansi                                                  Disable ANSI output
  -n, --no-interaction                                           Do not ask any interactive question
  -v|vv|vvv, --verbose                                           Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

* `testCase` - Parent class that will be extend in generated test
* `mockFramework` - Test framework mockery or phpunit
* `pathPattern` - SUT directory regexp pattern
* `pathReplacementPattern` - Test directory replacement patter that will be used to generate target test path
* `namespacePattern` - SUT namespace pattern
* `namespaceReplacementPattern` - Test namespace replacement patter that will be used to generate target test namepsace
* `template` Template used to generate test
* `templateDir` Twig templates directory