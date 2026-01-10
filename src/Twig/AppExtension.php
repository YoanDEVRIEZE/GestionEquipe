<?php 

namespace App\Twig;

use Symfony\Bundle\SecurityBundle\Security as SecurityBundleSecurity;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(private SecurityBundleSecurity $security) {}

    public function getGlobals(): array
    {
        return [
            'tdb' => match (true) {
                $this->security->isGranted('ROLE_ADMIN')   => 'gestion_equipe_admin',
                $this->security->isGranted('ROLE_MANAGER') => 'gestion_equipe_manager',
                $this->security->isGranted('ROLE_USER')    => 'gestion_equipe_user',
                default => 'gestion_equipe_user',
            },
        ];
    }
}
