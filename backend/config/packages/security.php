<?php

declare(strict_types=1);

use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationFailureHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Config\SecurityConfig;
use SymfonyVueBoilerplateBackend\Authentication\Provider\UserProvider;

return static function (SecurityConfig $security) {
    $security
        ->passwordHasher(PasswordAuthenticatedUserInterface::class, [
            'algorithm' => 'auto',
        ])
    ;

    $security
        ->provider('custom_user_provider')
        ->id(UserProvider::class)
    ;

    // firewall definitions
    // firewalls get activated based on the pattern - AUTHENTICATION
    // here, 'dev' will be triggered on the defined pattern and will disable all security
    $security
        ->firewall('dev')
        ->pattern([
            '^/_profiler/',
            '^/_wdt/',
            '/css/',
            '/js/',
            '^/api/doc$',
            '^/api/schema$',
        ])
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
            ->successHandler(AuthenticationSuccessHandler::class)
            ->failureHandler(AuthenticationFailureHandler::class);

    // then 'api' for all other routes - pattern can be left out if "all"
    $security
        ->firewall('api')
        ->stateless(true)
        ->jwt();

    //    /*
    //     * access control is just a broader way of covering access rules - AUTHORIZATION
    //     * i.e., it does not directly relate the firewall
    //     * e.g., if ->security(false) was not set on the 'dev' firewall, this would also cover it
    //     */
    $security->accessControl()
        ->path('^(?!api/login$)')
        ->roles(['ROLE_USER'])
    ;
};
