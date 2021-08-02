<?php

namespace App\EventListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use App\Entity\User;

class AuthenticationSuccessListener
{
    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        $data['data'] = array(
            'user' => [
                'identifier' => $user->getUserIdentifier(),
                'email' => $user->getEmail(),
                'id' => $user->getId(),
                'nickname' => $user->getNickname(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname()
            ],
        );
        $event->setData($data);
    }
}