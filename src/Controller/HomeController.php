<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/Accueil')]
final class HomeController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/Utilisateur/Tableau_de_bord', name: 'gestion_equipe_user_home')]
    public function userHome(): Response
    {
        return $this->render('home/index.user.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[IsGranted('ROLE_MANAGER')]
    #[Route('/Manager/Tableau_de_bord', name: 'gestion_equipe_manager_home')]
    public function managerHome(): Response
    {
        return $this->render('home/index.manager.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/Administrateur/Tableau_de_bord', name: 'gestion_equipe_admin_home')]
    public function adminHome(): Response
    {
        return $this->render('home/index.admin.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
