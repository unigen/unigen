<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;

return [
    'prefix' => 'UniGenPhar',
    'finders' => [
        Finder::create()->files()->in('src'),
        Finder::create()
            ->files()
            ->ignoreVCS(true)
            ->notName('/LICENSE|.*\\.md|.*\\.dist|Makefile|composer\\.json|composer\\.lock/')
            ->exclude([
                'doc',
                'test',
                'test_old',
                'tests',
                'Tests',
                'vendor-bin',
            ])
            ->in('vendor'),
        Finder::create()->append([
            'composer.json',
        ]),
    ],
];
