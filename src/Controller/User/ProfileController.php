<?php

namespace App\Controller\User;

use App\Repository\SkillRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/Accueil/Utilisateur')]
final class ProfileController extends AbstractController
{
    #[Route('/Profil/{id}', name: 'gestion_equipe_user_profile', methods: ['GET'])]
    public function index(String $id, UserRepository $userRepository, SkillRepository $skillRepository): Response
    {
        return $this->render('user/profile/index.html.twig', [
            'user' => $userRepository->find($id),
        ]);
    }
}
