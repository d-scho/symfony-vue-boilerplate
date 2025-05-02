<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services
        ->defaults()
        ->load('Api\\', '../src/')
        ->exclude('../src/**/ValueObject/*')
        ->autowire()
        ->autoconfigure()
        ->public()
    ;
};
