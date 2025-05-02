<?php

declare(strict_types=1);

namespace Api\User\EventListener;

use Api\User\ValueObject\CustomUser;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'lexik_jwt_authentication.on_jwt_created', method: 'onJWTCreated')]
final readonly class JWTCreatedListener
{
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $payload = $event->getData();

        $user = $event->getUser();

        if ($user instanceof CustomUser) {
            $payload['displayName'] = $user->displayName;
        }

        $event->setData($payload);
    }
}