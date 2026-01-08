<?php

namespace App\Controller\Admin;

use App\Entity\Skill;
use App\Form\Skill\SkillType;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/Accueil/Admin/Competences')]
final class SkillController extends AbstractController
{
    #[Route(name: 'gestion_equipe_skill_index', methods: ['GET'])]
    public function index(SkillRepository $skillRepository): Response
    {
        $skill = $skillRepository->findAll();

        $countUsersPerSkill = [];
        
        foreach ($skill as $skills) {
            $countUsersPerSkill[$skills->getId()] = $skills->getUsers()->count();
        }

        return $this->render('skill/index.html.twig', [
            'skill' => $skillRepository->findAll(),
            'countSkill' => $skillRepository->count([]),
            'countUsersPerSkill' => $countUsersPerSkill,
        ]);
    }

    #[Route('/Ajouter', name: 'gestion_equipe_skill_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $skill = new Skill();
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($skill);
            $entityManager->flush();

            $this->addFlash('success', '<b>Confirmation</b> : La compétence <b>' . $skill->getName() . '</b> a été créée avec succès.');
            return $this->redirectToRoute('gestion_equipe_skill_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('skill/new.html.twig', [
            'skill' => $skill,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/Consulter', name: 'gestion_equipe_skill_show', methods: ['GET'])]
    public function show(Skill $skill): Response
    {
        return $this->render('skill/show.html.twig', [
            'skill' => $skill,
            'users' => $skill->getUsers(),
            'countUsers' => $skill->getUsers()->count(),
        ]);
    }

    #[Route('/{id}/Modifier', name: 'gestion_equipe_skill_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Skill $skill, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', '<b>Confirmation</b> : La compétence <b>' . $skill->getName() . '</b> a été modifiée avec succès.');
            return $this->redirectToRoute('gestion_equipe_skill_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('skill/edit.html.twig', [
            'skill' => $skill,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/Supprimer', name: 'gestion_equipe_skill_delete', methods: ['POST'])]
    public function delete(Request $request, Skill $skill, EntityManagerInterface $entityManager): Response
    {
        if ($skill->getUsers()->isEmpty() === false) {
            $this->addFlash('error', '<b>Erreur</b> : Veuillez dans un premier temps supprimer les utilisateurs liés à cette compétence.');
            return $this->redirectToRoute('gestion_equipe_skill_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('delete'.$skill->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($skill);
            $entityManager->flush();
        }

        $this->addFlash('success', '<b>Confirmation</b> : La compétence <b>'. $skill->getName() .'</b> à été suprimée avec succès.');
        return $this->redirectToRoute('gestion_equipe_skill_index', [], Response::HTTP_SEE_OTHER);
    }
}
