<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\DepartmentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/Accueil/Admin/Utilisateurs/Gestion')]
final class UserManagementController extends AbstractController
{
    #[Route(name: 'gestion_equipe_user_management_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Request $request, PaginatorInterface $paginator, DepartmentRepository $departmentRepository): Response
    {
        $countUsers = $userRepository->count([]);
        $department = $departmentRepository->findAll();

        if ($department === []) {
            $this->addFlash('error', 'Erreur : Aucun département / service n\'est défini. Veuillez créer des départements / services avant de gérer les utilisateurs.');

            return $this->redirectToRoute('gestion_equipe_department_index', [], Response::HTTP_SEE_OTHER);
        
        }

        $users = $userRepository->findAll();
        $pagination = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('user_management/index.html.twig', [
            'users' => $pagination,
            'countUsers' => $countUsers,
        ]);
    }

    #[Route('/Ajouter', name: 'gestion_equipe_user_management_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('companyId')->getData();
            $roles = $form->get('roles')->getData();
            $user->setRoles($roles);

            if($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            $compagnyId = strtoupper($user->getCompanyId());
            $user->setCompanyId($compagnyId);

            $lastname = strtoupper($user->getLastName());
            $user->setLastName($lastname);

            $firstname = ucfirst(strtolower($user->getFirstName()));
            $user->setFirstName($firstname);

            $adress = strtoupper($user->getAdress());
            $user->setAdress($adress);

            $mailPro = strtolower($user->getEmail());
            $user->setEmail($mailPro);

            $mailPrivate = strtolower($user->getEmailPrivate());
            $user->setEmailPrivate($mailPrivate);
            
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Confirmation : nouvel utilisateur <b>' . $user->getFirstName() . ' ' . $user->getLastName() . '</b> ajouté avec succès.');

            return $this->redirectToRoute('gestion_equipe_user_management_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_management/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/Consulter', name: 'gestion_equipe_user_management_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user_management/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/Modifier', name: 'gestion_equipe_user_management_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user, ['edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compagnyId = strtoupper($user->getCompanyId());
            $user->setCompanyId($compagnyId);

            $lastname = strtoupper($user->getLastName());
            $user->setLastName($lastname);

            $firstname = ucfirst(strtolower($user->getFirstName()));
            $user->setFirstName($firstname);

            $adress = strtoupper($user->getAdress());
            $user->setAdress($adress);

            $mailPro = strtolower($user->getEmail());
            $user->setEmail($mailPro);

            $mailPrivate = strtolower($user->getEmailPrivate());
            $user->setEmailPrivate($mailPrivate);

            $entityManager->flush();

            $this->addFlash('success', 'Confirmation : les informations de l\'utilisateur <b>' . $user->getFirstName() . ' ' . $user->getLastName() . '</b> ont été mises à jour avec succès.');

            return $this->redirectToRoute('gestion_equipe_user_management_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_management/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/Supprimer', name: 'gestion_equipe_user_management_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Confirmation : l\'utilisateur <b>' . $user->getFirstName() . ' ' . $user->getLastName() . '</b> supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Erreur : jeton CSRF invalide. La suppression de l\'utilisateur a échoué.');
        }

        return $this->redirectToRoute('app_user_management_index', [], Response::HTTP_SEE_OTHER);
    }
}
