<?php

namespace App\EventListener\User;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Logout\LogoutUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CheckActiveUserListener
{
    public function __construct(
        private Security $security,
        private LogoutUrlGenerator $logoutUrlGenerator
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $excludedRoutes = [
            'gestion_equipe_login',
            'gestion_equipe_logout',
            'gestion_equipe_password_forgot',
            'gestion_equipe_password_reset',
        ];

        if (in_array($request->attributes->get('_route'), $excludedRoutes, true)) {
            return;
        }

        $user = $this->security->getUser();

        if ($user && method_exists($user, 'isActive') && !$user->isActive()) {
            $event->setResponse(
                new RedirectResponse($this->logoutUrlGenerator->getLogoutPath())
            );
        }
    }
}
