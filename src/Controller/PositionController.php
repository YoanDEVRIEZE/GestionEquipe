<?php

namespace App\Controller;

use App\Entity\Position;
use App\Form\PositionType;
use App\Repository\PositionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/LesPostes')]
final class PositionController extends AbstractController
{
    #[Route(name: 'gestion_equipe_position_index', methods: ['GET'])]
    public function index(PositionRepository $positionRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $countPosition = $positionRepository->count([]);

        $positions = $positionRepository->findAll();
        $pagination = $paginator->paginate(
            $positions,
            $request->query->getInt('page', 1),
            10
        ); 
        
        $countUsersPerPosition = [];

        foreach ($positions as $pos) {
            $countUsersPerPosition[$pos->getId()] = $pos->getUsers()->count();
        }   

        return $this->render('position/index.html.twig', [
            'positions' => $pagination,
            'countUsersPerPosition' => $countUsersPerPosition,
            'countPosition' => $countPosition,
        ]);
    }

    #[Route('/Ajouter', name: 'gestion_equipe_position_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $position = new Position();
        $form = $this->createForm(PositionType::class, $position);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($position);
            $entityManager->flush();
            $this->addFlash('success', 'Confirmation : Le poste <b>' . $position->getName() . '</b> a été créé avec succès.');

            return $this->redirectToRoute('gestion_equipe_position_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('position/new.html.twig', [
            'position' => $position,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/Consulter', name: 'gestion_equipe_position_show', methods: ['GET'])]
    public function show(Position $position, UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $users = $userRepository->findBy(['position' => $position]);
        $pagination = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            10
        );

        $countUsers = $position->getUsers()->count();

        return $this->render('position/show.html.twig', [
            'position' => $position,
            'users' => $pagination,
            'countUsers' => $countUsers,
        ]);
    }

    #[Route('/{id}/Modifier', name: 'gestion_equipe_position_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Position $position, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PositionType::class, $position);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Confirmation : Le poste <b>' . $position->getName() . '</b> a été mis à jour avec succès.');

            return $this->redirectToRoute('gestion_equipe_position_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('position/edit.html.twig', [
            'position' => $position,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/Supprimer', name: 'gestion_equipe_position_delete', methods: ['POST'])]
    public function delete(Request $request, Position $position, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$position->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($position);
            $entityManager->flush();
            $this->addFlash('success', 'Confirmation : Le poste <b>' . $position->getName() . '</b> a été supprimé avec succès.');
        }

        return $this->redirectToRoute('gestion_equipe_position_index', [], Response::HTTP_SEE_OTHER);
    }
}
