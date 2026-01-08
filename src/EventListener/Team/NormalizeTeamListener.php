<?php

namespace App\EventListener\Team;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Form\Event\PreSubmitEvent;

final class NormalizeTeamListener
{
    #[AsEventListener(event: 'form.pre_submit')]
    public function onFormPreSubmit(PreSubmitEvent $event): void
    {
        $data = $event->getData();

        if (isset($data['name']) && $data['name']) {
            $data = mb_strtoupper($data['name'], 'UTF-8');
        }

        $event->setData($data);
    }
}
