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
use Knp\Component\Pager\PaginatorInterface;
use App\Service\PdfGeneratorService;

#[IsGranted('IS_AUTHENTICATED')]
#[Route('/admin')]
final class AuditController extends AbstractController
{
    #[Route(name: 'app_audit_index', methods: ['GET'])]
    public function index(AuditRepository $auditRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $auditRepository->createQueryBuilder('a')
            ->orderBy('a.date_heure_audit', 'DESC') 
            ->getQuery();
        $audits = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('audit/index.html.twig', [
            'audits' => $audits,
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

    #[Route('/audit/{id}/pdf', name: 'app_audit_pdf')]
    public function output(Audit $audit, PdfGeneratorService $pdfGeneratorService): Response
    {
        $html = $this->renderView('audit/pdf.html.twig', [
            'audit' => $audit,
        ]);

        $content = $pdfGeneratorService->getPdf($html);

        return new Response($content, 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
