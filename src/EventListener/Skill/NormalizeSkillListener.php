<?php

namespace App\EventListener\Skill;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Form\Event\PreSubmitEvent;

final class NormalizeSkillListener
{
    #[AsEventListener(event: 'form.pre_submit')]
    public function onFormPreSubmit(PreSubmitEvent $event): void
    {
        $data = $event->getData();

        if (!$data) {
            return;
        }

        if (isset($data) && $data['name']) {
            $data['name'] = mb_strtoupper($data['name'], 'UTF-8');
        }

        $event->setData($data);
    }
}
