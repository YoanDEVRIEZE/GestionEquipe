<?php

namespace App\EventListener\User;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class NormalizeUserListener
{
    #[AsEventListener(event: FormEvents::PRE_SUBMIT)]
    public function onFormPreSubmit(FormEvent $event): void
    {
        $data = $event->getData();

        if (!$data) {
            return;
        }

        if (isset($data['firstName']) && $data['firstName']) {
            $data['firstName'] = ucfirst(strtolower($data['firstName']));
        }

        if (isset($data['lastName']) && $data['lastName']) {
            $data['lastName'] = mb_strtoupper($data['lastName'], 'UTF-8');
        }

        if (isset($data['email']) && $data['email']) {
            $data['email'] = strtolower($data['email']);
        }

        if (isset($data['emailPrivate']) && $data['emailPrivate']) {
            $data['emailPrivate'] = strtolower($data['emailPrivate']);
        }

        if (isset($data['companyId']) && $data['companyId']) {
            $data['companyId'] = mb_strtoupper($data['companyId'], 'UTF-8');
        }

        if (isset($data['address']) && $data['address']) {
            $data['address'] = mb_strtoupper($data['address'], 'UTF-8');
        }

        $event->setData($data);
    }
}
