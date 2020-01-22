#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';
require 'vendor/autoload.php';

$configFactory = new UniGen\Config\ConfigFactory(
    new UniGen\Config\SchemaFactory(__DIR__ . '/Resources/schema/config')
);

$generatorFactory = new UniGen\Generator\GeneratorFactory(
    new UniGen\Sut\SutFactory(),
    new UniGen\Renderer\RendererFactory()
);

$generatorCommand = new UniGen\Generator\GenerateCommand(
    $configFactory,
    $generatorFactory
);

$application = new Symfony\Component\Console\Application();
$application->add($generatorCommand);
$application->setDefaultCommand($generatorCommand::NAME, true);
$application->setAutoExit(true);
$application->run();
