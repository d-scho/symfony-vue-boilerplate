<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services
        ->defaults()
        ->load('SymfonyVueBoilerplateBackend\\', '../src/')
        ->exclude([
            '../src/Kernel.php',
            '../src/**/Entity/*',
            '../src/**/DTO/*',
            '../src/**/View/*',
        ])
        ->autowire()
        ->autoconfigure()
        ->public()
    ;
};
