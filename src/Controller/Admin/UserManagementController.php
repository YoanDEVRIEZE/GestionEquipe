<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\User\UserType;
use App\Repository\DepartmentRepository;
use App\Repository\SkillRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_ADMIN')]
#[Route('/Accueil/Admin/Utilisateurs')]
final class UserManagementController extends AbstractController
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger) 
    {
        $this->slugger = $slugger;
    }

    #[Route(name: 'gestion_equipe_user_management_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, DepartmentRepository $departmentRepository, TeamRepository $teamRepository): Response
    {
        if ($departmentRepository->count([]) === 0) {
            $this->addFlash('error', '<b>Erreur</b> : Aucun service n\'est défini. Veuillez créer des services avant de gérer les utilisateurs.');
            return $this->redirectToRoute('gestion_equipe_department_index');
        }

        if ($teamRepository->count([]) === 0) {
            $this->addFlash('error', '<b>Erreur</b> : Aucune équipe n\'est définie. Veuillez créer des équipes avant de gérer les utilisateurs.');
            return $this->redirectToRoute('gestion_equipe_team_index');
        }

        return $this->render('user_management/index.html.twig', [
            'users' => $userRepository->findAll(),
            'countUsers' => $userRepository->count([]),
        ]);
    }

    #[Route('/Ajouter', name: 'gestion_equipe_user_management_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, SkillRepository $skillRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('companyId')->getData();
            $roles = $form->get('roles')->getData();
            $user->setRoles($roles);

            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }
            
             /** @var UploadedFile|null $file */
            $file = $form->get('avatar')->getData();

            if ($file) {
                $safeFileName = $this->slugger->slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                $newFileName = $safeFileName.'-'.uniqid().'.'.$file->guessExtension();
                $file->move($this->getParameter('profile_pictures_directory'), $newFileName);
                $user->setAvatar($newFileName);
            }
            
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', '<b>Confirmation</b> : nouvel utilisateur <b>' . $user->getFirstName() . ' ' . $user->getLastName() . '</b> ajouté avec succès.');
            return $this->redirectToRoute('gestion_equipe_user_management_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_management/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'skill' => $skillRepository->count(),
        ]);
    }

    #[Route('/{id}/Consulter', name: 'gestion_equipe_user_management_show', methods: ['GET'])]
    public function show(User $user, SkillRepository $skillRepository): Response
    {
        return $this->render('user_management/show.html.twig', [
            'user' => $user,
            'countSkill' => $skillRepository->count(),
            'skill' => $skillRepository->findAll(),
        ]);
    }

    #[Route('/{id}/Modifier', name: 'gestion_equipe_user_management_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, SkillRepository $skillRepository): Response
    {
        $form = $this->createForm(UserType::class, $user, ['edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             /** @var UploadedFile|null $file */
            $file = $form->get('avatar')->getData();

            if ($file) {
                $safeFileName = $this->slugger->slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                $newFileName = $safeFileName.'-'.uniqid().'.'.$file->guessExtension();
                $file->move($this->getParameter('profile_pictures_directory'), $newFileName);
                $user->setAvatar($newFileName);
            }

            $entityManager->flush();

            $this->addFlash('success', '<b>Confirmation</b> : les informations de l\'utilisateur <b>' . $user->getFirstName() . ' ' . $user->getLastName() . '</b> ont été mises à jour avec succès.');
            return $this->redirectToRoute('gestion_equipe_user_management_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_management/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'skill' => $skillRepository->count(),
        ]);
    }

    #[Route('/{id}/Supprimer', name: 'gestion_equipe_user_management_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            if ($this->getUser() === $user) {
                $this->addFlash('error', '<b>Erreur</b> : Vous ne pouvez pas supprimer votre propre compte.');
                return $this->redirectToRoute('gestion_equipe_user_management_index', [], Response::HTTP_SEE_OTHER);
            }

            $fileName = $user->getAvatar();

            if ($fileName) {
                $baseName = basename($fileName);
                $filePath = $this->getParameter('profile_pictures_directory').'/'.$baseName;
            
                if (!is_file($filePath)) {
                    $this->addFlash('error', 'La photo de profile <b>'. $fileName . '</b> est introuvable.');
                } elseif (!unlink($filePath)) {
                    $this->addFlash('error', 'Impossible de supprimer la photo de profile '. $fileName);
                }
            }

            $entityManager->remove($user);
            $entityManager->flush();
        } else {
            $this->addFlash('error', 'Erreur : jeton CSRF invalide. La suppression de l\'utilisateur a échoué.');
        }

        $this->addFlash('success', '<b>Confirmation</b> : l\'utilisateur <b>' . $user->getFirstName() . ' ' . $user->getLastName() . '</b> supprimé avec succès.');
        return $this->redirectToRoute('gestion_equipe_user_management_index', [], Response::HTTP_SEE_OTHER);
    }
}
