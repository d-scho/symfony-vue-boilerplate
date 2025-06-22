<?php

declare(strict_types=1);

use PhpCsFixer\Config;

return new Config()
    ->setFinder(new PhpCsFixer\Finder()
        ->in(__DIR__)
        ->exclude('var')
    )
;
