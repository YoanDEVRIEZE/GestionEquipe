<?php

namespace App\Controller\Admin;

use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/Accueil/Admin/Equipe')]
final class TeamController extends AbstractController
{
    #[Route(name: 'gestion_equipe_team_index', methods: ['GET'])]
    public function index(TeamRepository $teamRepository): Response
    {   
        $team = $teamRepository->findAll();
        $countTeam = $teamRepository->count([]);
        $countUsersPerTeam = [];
        
        foreach ($team as $teams) {
            $countUsersPerTeam[$teams->getId()] = $teams->getUsers()->count();
        }

        return $this->render('team/index.html.twig', [
            'team' => $team,
            'countTeam' => $countTeam,
            'countUsersPerTeam' => $countUsersPerTeam,
        ]);
    }

    #[Route('/Ajouter', name: 'gestion_equipe_team_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($team);
            $entityManager->flush();

            $this->addFlash('success', '<b>Confirmation</b> : l\'équipe <b>'. $team->getName() .'</b> a été créé avec succès.');

            return $this->redirectToRoute('gestion_equipe_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('team/new.html.twig', [
            'team' => $team,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/Consulter', name: 'gestion_equipe_team_show', methods: ['GET'])]
    public function show(Team $team): Response
    {
        return $this->render('team/show.html.twig', [
            'team' => $team,
            'users' => $team->getUsers(),
            'countUsers' => $team->getUsers()->count(),
        ]);
    }

    #[Route('/{id}/Modifier', name: 'gestion_equipe_team_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', '<b>Confirmation</b> : L\'équipe <b>'. $team->getName() .'</b> a été modifiée avec succès.');

            return $this->redirectToRoute('gestion_equipe_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('team/edit.html.twig', [
            'team' => $team,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/Supprimer', name: 'gestion_equipe_team_delete', methods: ['POST'])]
    public function delete(Request $request, Team $team, EntityManagerInterface $entityManager): Response
    {
        if ($team->getUsers()->isEmpty() === false) {
            $this->addFlash('error', '<b>Erreur</b> : Veuillez dans un premier temps supprimer les utilisateurs liés à cette équipe.');

            return $this->redirectToRoute('gestion_equipe_team_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('delete'.$team->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($team);
            $entityManager->flush();

            $this->addFlash('success', '<b>Confirmation</b> : L\'équipe <b>'. $team->getName() .'</b> à été suprimée avec succès.');
        }

        return $this->redirectToRoute('gestion_equipe_team_index', [], Response::HTTP_SEE_OTHER);
    }
}
