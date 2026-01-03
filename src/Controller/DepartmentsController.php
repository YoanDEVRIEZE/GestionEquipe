<?php

namespace App\Controller;

use App\Entity\Departments;
use App\Form\DepartmentsType;
use App\Repository\DepartmentsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/Departements/Service')]
final class DepartmentsController extends AbstractController
{
    #[Route(name: 'gestion_equipe_departments_index', methods: ['GET'])]
    public function index(DepartmentsRepository $departmentsRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $departments = $paginator->paginate(
            $departmentsRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('departments/index.html.twig', [
            'departments' => $departments,
        ]);
    }

    #[Route('/Ajouter', name: 'gestion_equipe_departments_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $department = new Departments();
        $form = $this->createForm(DepartmentsType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($department);
            $entityManager->flush();

            $this->addFlash('success', 'Confirmation : Le département <b>'. $department->getName() .'</b> a été créé avec succès.');

            return $this->redirectToRoute('gestion_equipe_departments_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('departments/new.html.twig', [
            'department' => $department,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/Consulter', name: 'gestion_equipe_departments_show', methods: ['GET'])]
    public function show(Departments $department, UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $users = $userRepository->findBy(['department' => $department]);
        $pagination = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('departments/show.html.twig', [
            'department' => $department,
            'users' => $pagination,
        ]);
    }

    #[Route('/{id}/Modifier', name: 'gestion_equipe_departments_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Departments $department, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DepartmentsType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Confirmation : Le département <b>'. $department->getName() .'</b> a été modifié avec succès.');

            return $this->redirectToRoute('gestion_equipe_departments_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('departments/edit.html.twig', [
            'department' => $department,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/Supprimer', name: 'gestion_equipe_departments_delete', methods: ['POST'])]
    public function delete(Request $request, Departments $department, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$department->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($department);
            $entityManager->flush();

            $this->addFlash('success', 'Confirmation : Le département <b>'. $department->getName() .'</b> a été supprimé avec succès.');
        }

        return $this->redirectToRoute('gestion_equipe_departments_index', [], Response::HTTP_SEE_OTHER);
    }
}
