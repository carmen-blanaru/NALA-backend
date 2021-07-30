<?php

namespace App\EventListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use App\Entity\User;
use Symfony\Component\Serializer\SerializerInterface;

class AuthenticationSuccessListener
{

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
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
                'firtname' => $user->getFirstname(),
                'lastname' => $user->getLastname()
            ],
        );
        $event->setData($data);
    }
}