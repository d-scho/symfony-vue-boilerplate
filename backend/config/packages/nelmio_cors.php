<?php

declare(strict_types=1);

use Symfony\Config\NelmioCorsConfig;

// Attention: CORS only protects against reading responses, it doesn't block requests
return static function (NelmioCorsConfig $cors) {
    $cors
        ->paths('^/')
        ->allowOrigin(['localhost'])
        ->allowMethods(['GET', 'POST'])
        ->allowHeaders(['X-CSRF-Token']) // returned from preflight request -> says which headers are allowed on actual request
        ->maxAge(3600) // validity duration of preflight response
//        ->allowCredentials(true)
    ;
};
