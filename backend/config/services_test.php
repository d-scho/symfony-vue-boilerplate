<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use SymfonyVueBoilerplateBackend\Authentication\Entity\User;
use SymfonyVueBoilerplateBackend\Authentication\Repository\UserRepository;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set(UserRepository::class)
        ->factory([service('doctrine'), 'getRepository'])
        ->args([User::class])
        ->autowire()
        ->autoconfigure()
        ->public();
    ;
};
