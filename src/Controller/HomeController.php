<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/')]
final class HomeController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/Utilisateur/Utilisateur/Tableau_de_bord', name: 'gestion_equipe_userhome')]
    public function userHome(string $user,): Response
    {
        return $this->render('home/index.user.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[IsGranted('ROLE_MANAGER')]
    #[Route('/Accueil/Manager/Tableau_de_bord', name: 'gestion_equipe_managerhome')]
    public function managerHome(): Response
    {
        return $this->render('home/index.manager.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/Accueil/Admin/Tableau_de_bord', name: 'gestion_equipe_adminhome')]
    public function adminHome(): Response
    {
        return $this->render('home/index.admin.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
