<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes) {
    $routes->import('../src', 'attribute');

    // create json login endpoint that is covered by security listener from firewall
    $routes
        ->add('app_login', '/login')
        ->methods(['POST']);
};
