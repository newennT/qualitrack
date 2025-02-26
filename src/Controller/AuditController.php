<?php

namespace App\Controller;

use App\Entity\Audit;
use App\Entity\Verification;
use App\Entity\Operation;
use App\Form\AuditType;
use App\Repository\AuditRepository;
use App\Repository\OperationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class AuditController extends AbstractController
{
    #[Route('/', name: 'app_audit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, OperationRepository $operationRepository): Response
    {
        $audit = new Audit();

        $operations = $operationRepository->findAll();

        foreach ($operations as $operation){
            $verification = new Verification();
            $verification->setOperation($operation);
            $verification->setAudit($audit);
            $audit->addVerification($verification);
        }

        $form = $this->createForm(AuditType::class, $audit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($audit);
            $entityManager->flush();

            return $this->redirectToRoute('app_audit_validation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/audit.html.twig', [
            'audit' => $audit,
            'form' => $form,
        ]);
    }

    
    #[Route('/validation', name: 'app_audit_validation', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('client/validation.html.twig');
    }

    
}
