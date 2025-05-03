<?php

declare(strict_types=1);

namespace SymfonyVueBoilerplateBackend\Authentication\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use SymfonyVueBoilerplateBackend\Authentication\Entity\User;

#[AsEventListener(event: 'lexik_jwt_authentication.on_jwt_created', method: 'onJWTCreated')]
final readonly class JWTCreatedListener
{
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $payload = $event->getData();

        $user = $event->getUser();

        if ($user instanceof User) {
            $payload['displayName'] = $user->displayName;
        }

        $event->setData($payload);
    }
}
