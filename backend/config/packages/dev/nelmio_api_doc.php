<?php

declare(strict_types=1);

use Symfony\Config\NelmioApiDoc\AreasConfig;
use Symfony\Config\NelmioApiDocConfig;

return static function (NelmioApiDocConfig $apiDoc): void {
    $apiDoc->documentation('info', [
        'version' => '1.0.0',
        'title' => 'Internal API Documentation',
    ]);

    $defaultArea = $apiDoc->areas('default');

    if ($defaultArea instanceof AreasConfig) {
        $defaultArea->pathPatterns([
            '^/api/(?!doc$)',
        ]);
    }
};
