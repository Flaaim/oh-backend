<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
    ->setRiskyAllowed(true)
    ->setCacheFile(__DIR__ . '/var/cache/.php-cs-fixer.cache')
    ->setRules([
        '@PSR12' => true,
        '@PSR12:risky' => true,
        '@DoctrineAnnotation' => true,
        '@PHP8x2Migration' => true,
        '@PHP8x2Migration:risky' => true,
        '@PHPUnit10x0Migration:risky' => true,
    ])
    // 💡 by default, Fixer looks for `*.php` files excluding `./vendor/` - here, you can groom this config
    ->setFinder(
        (new Finder())
            // 💡 root folder to check
            ->in([
                __DIR__ . '/bin',
                __DIR__ . '/config',
                __DIR__ . '/public',
                __DIR__ . '/src',
                __DIR__ . '/tests',
            ])
        ->append([
            __FILE__,
        ])
        // 💡 additional files, eg bin entry file
        // ->append([__DIR__.'/bin-entry-file'])
        // 💡 folders to exclude, if any
        // ->exclude([/* ... */])
        // 💡 path patterns to exclude, if any
        // ->notPath([/* ... */])
        // 💡 extra configs
        // ->ignoreDotFiles(false) // true by default in v3, false in v4 or future mode
        // ->ignoreVCS(true) // true by default
    )
;
