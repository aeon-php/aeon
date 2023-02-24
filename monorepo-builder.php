<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Config\MBConfig;

return static function (MBConfig $config): void {
    $config->defaultBranch('1.x');
    $config->packageDirectories([
        __DIR__ . '/src',
    ]);

    $config->dataToAppend([
        ComposerJsonSection::SCRIPTS => [
            "build" => [
                "@static:analyze",
                "@test",
                "@test:mutation"
            ],
            "test" => [
                "tools/vendor/bin/phpunit"
            ],
            "test:mutation" => [
                "tools/vendor/bin/infection -j2"
            ],
            "static:analyze" => [
                "tools/vendor/bin/psalm.phar --output-format=compact",
                "tools/vendor/bin/phpstan analyze -c phpstan.neon",
                "tools/vendor/bin/php-cs-fixer fix --dry-run"
            ],
            "cs:php:fix" => [
                "tools/cs-fixer/vendor/bin/php-cs-fixer fix"
            ],
            "post-install-cmd" => [
                "@tools:install"
            ],
            "post-update-cmd" => [
                "@tools:install"
            ],
            "tools:install" => [
                "composer install --working-dir=./tools",
            ]
        ]
    ]);

    $config->dataToRemove([
        ComposerJsonSection::REQUIRE_DEV => [
            "phpunit/phpunit" => "*",
            "infection/infection" => "*",
            "friendsofphp/php-cs-fixer" => "*",
            "phpstan/phpstan" => "*",
            "vimeo/psalm" => "*",
        ]
    ]);
};
