<?php

declare(strict_types=1);

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Config\SecurityConfig;
use SymfonyVueBoilerplateBackend\Authentication\Provider\CustomUserProvider;

return static function (SecurityConfig $security) {
    $security
        ->passwordHasher(PasswordAuthenticatedUserInterface::class, [
            'algorithm' => 'auto',
        ])
    ;

    $security
        ->provider('custom_user_provider')
        ->id(CustomUserProvider::class)
    ;

    // firewall definitions
    // firewalls get activated based on the pattern - AUTHENTICATION
    // here, 'dev' will be triggered on the defined pattern and will disable all security
    $security
        ->firewall('dev')
        ->pattern('^/(_(profiler|wdt)|css|images|js)/')
        ->security(false);

    // then, 'login' is triggered for '/api/login'
    $security
        ->firewall('login')
        ->pattern('^/api/login$')
        ->provider('custom_user_provider')
        ->stateless(true)
        ->jsonLogin()
            ->checkPath('/api/login') // lexik JWT bundle covers that route
            ->usernamePath('username')
            ->passwordPath('password')
            ->successHandler('lexik_jwt_authentication.handler.authentication_success')
            ->failureHandler('lexik_jwt_authentication.handler.authentication_failure');

    // then 'api' for all other routes - pattern can be left out if "all"
    $security
        ->firewall('api')
        ->provider('custom_user_provider')
        ->stateless(true)
        ->jwt();

    //    /*
    //     * access control is just a broader way of covering access rules - AUTHORIZATION
    //     * i.e., it does not directly relate the firewall
    //     * e.g., if ->security(false) was not set on the 'dev' firewall, this would also cover it
    //     */
    //    $security->accessControl()
    //        ->path('...')
    //        ->roles([...])
    //    ;
};
