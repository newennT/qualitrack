<?php

namespace App\Controller\Admin;

use App\Entity\Audit;
use App\Form\AuditType;
use App\Repository\AuditRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED')]
#[Route('/admin')]
final class AuditController extends AbstractController
{
    #[Route(name: 'app_audit_index', methods: ['GET'])]
    public function index(AuditRepository $auditRepository): Response
    {
        return $this->render('audit/index.html.twig', [
            'audits' => $auditRepository->findAll(),
        ]);
    }

    

    #[Route('/audit/{id}', name: 'app_audit_show', methods: ['GET'])]
    public function show(Audit $audit): Response
    {
        return $this->render('audit/show.html.twig', [
            'audit' => $audit,
        ]);
    }

    #[Route('/audit/{id}/edit', name: 'app_audit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Audit $audit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AuditType::class, $audit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_audit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('audit/edit.html.twig', [
            'audit' => $audit,
            'form' => $form,
        ]);
    }

    #[Route('/audit/{id}', name: 'app_audit_delete', methods: ['POST'])]
    public function delete(Request $request, Audit $audit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$audit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($audit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_audit_index', [], Response::HTTP_SEE_OTHER);
    }
}
