<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes) {
    $routes->import('../src', 'attribute');

    // create endpoint that is covered by check path of json login firewall
    $routes
        ->add(name: 'api_login', path: '/api/login')
        ->methods(['POST']);
};
