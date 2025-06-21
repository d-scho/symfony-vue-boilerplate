<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes) {
    $routes
        ->add('app.swapper_ui', '/api/doc')
        ->controller('nelmio_api_doc.controller.swagger_ui') // needs Twig and Asset
        ->methods(['GET']);

    $routes
        ->add('app.swapper_json', '/api/schema')
        ->controller('nelmio_api_doc.controller.swagger_json')
        ->methods(['GET']);
};
