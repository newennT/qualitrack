<?php

namespace App\Controller\Admin;

use App\Entity\Auditeur;
use App\Form\AuditeurType;
use App\Repository\AuditeurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

#[IsGranted('IS_AUTHENTICATED')]
#[Route('/admin/auditeur')]
final class AuditeurController extends AbstractController
{
    #[Route(name: 'app_auditeur_index', methods: ['GET'])]
    public function index(AuditeurRepository $auditeurRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $auditeurRepository->createQueryBuilder('a')
            ->orderBy('a.nom', 'ASC')
            ->getQuery();
        $auditeurs = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('auditeur/index.html.twig', [
            'auditeurs' => $auditeurs,
        ]);
    }

    #[Route('/new', name: 'app_auditeur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $auditeur = new Auditeur();
        $form = $this->createForm(AuditeurType::class, $auditeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($auditeur);
            $entityManager->flush();

            return $this->redirectToRoute('app_auditeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('auditeur/new.html.twig', [
            'auditeur' => $auditeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_auditeur_show', methods: ['GET'])]
    public function show(Auditeur $auditeur): Response
    {
        return $this->render('auditeur/show.html.twig', [
            'auditeur' => $auditeur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_auditeur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Auditeur $auditeur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AuditeurType::class, $auditeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_auditeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('auditeur/edit.html.twig', [
            'auditeur' => $auditeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_auditeur_delete', methods: ['POST'])]
    public function delete(Request $request, Auditeur $auditeur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$auditeur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($auditeur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_auditeur_index', [], Response::HTTP_SEE_OTHER);
    }
}
