<?php

namespace App\Controller\Admin;

use App\Entity\Department;
use App\Form\DepartmentType;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/Accueil/Admin/Services')]
final class DepartmentController extends AbstractController
{
    #[Route(name: 'gestion_equipe_department_index', methods: ['GET'])]
    public function index(DepartmentRepository $departmentRepository): Response
    {
        $department = $departmentRepository->findAll();
        $countDepartment = $departmentRepository->count([]);
        $countUsersPerDepartment = [];
        
        foreach ($department as $dept) {
            $countUsersPerDepartment[$dept->getId()] = $dept->getUsers()->count();
        }

        return $this->render('department/index.html.twig', [
            'department' => $department,
            'countUsersPerDepartment' => $countUsersPerDepartment,
            'countDepartment' => $countDepartment,
        ]);
    }

    #[Route('/Ajouter', name: 'gestion_equipe_department_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $department = new Department();
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($department);
            $entityManager->flush();

            $this->addFlash('success', '<b>Confirmation</b> : Le service <b>'. $department->getName() .'</b> a été créé avec succès.');
            return $this->redirectToRoute('gestion_equipe_department_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('department/new.html.twig', [
            'department' => $department,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/Consulter', name: 'gestion_equipe_department_show', methods: ['GET'])]
    public function show(Department $department): Response
    {
        return $this->render('department/show.html.twig', [
            'department' => $department,
            'users' => $department->getUsers(),
            'countUsers' => $department->getUsers()->count(),
        ]);
    }

    #[Route('/{id}/Modifier', name: 'gestion_equipe_department_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Department $department, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', '<b>Confirmation</b> : Le service <b>'. $department->getName() .'</b> a été modifié avec succès.');
            return $this->redirectToRoute('gestion_equipe_department_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('department/edit.html.twig', [
            'department' => $department,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/Supprimer', name: 'gestion_equipe_department_delete', methods: ['POST'])]
    public function delete(Request $request, Department $department, EntityManagerInterface $entityManager): Response
    {
        if ($department->getUsers()->isEmpty() === false) {
            $this->addFlash('error', '<b>Erreur</b> : Veuillez dans un premier temps supprimer les utilisateurs liés à ce service.');
            return $this->redirectToRoute('gestion_equipe_department_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('delete'.$department->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($department);
            $entityManager->flush();
        }

        $this->addFlash('success', '<b>Confirmation</b> : Le service <b>'. $department->getName() .'</b> a été supprimé avec succès.');
        return $this->redirectToRoute('gestion_equipe_department_index', [], Response::HTTP_SEE_OTHER);
    }
}
