<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RedirectUserListener
{
    private $tokenStorage;
    private $router;


    public function __construct(TokenStorageInterface $t, RouterInterface $r)
    {
        $this->tokenStorage = $t;
        $this->router = $r;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $currentRoute = $event->getRequest()->attributes->get('_route');

        if ($this->isUserLogged() && $event->isMasterRequest()) {
            if ($this->isAuthenticatedUserOnAnonymousPage($currentRoute)) {
                $response = new RedirectResponse($this->router->generate('post_index'));
                $event->setResponse($response);
            }
        } else {
            if ($this->isAnonymousUserOnAuthenticatedPage($currentRoute)) {
                $response = new RedirectResponse($this->router->generate('login'));
                $event->setResponse($response);
            }
        }
    }

    private function isUserLogged()
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return false;
        }

        return $token->getUser() instanceof User;
    }

    private function isAnonymousUserOnAuthenticatedPage($currentRoute)
    {
        return in_array($currentRoute, [
                'post_index',
                'post_new',
                'post_edit',
                'post_delete',
            ]
        );
    }


    private function isAuthenticatedUserOnAnonymousPage($currentRoute)
    {
        return in_array($currentRoute, [
            'login'
            ]
        );
    }
}