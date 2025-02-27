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
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


final class AuditController extends AbstractController
{
    #[Route('/', name: 'app_audit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, OperationRepository $operationRepository, SluggerInterface $slugger): Response
    {
        $audit = new Audit();
        $audit->setDateHeureAudit(new \DateTime());

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
            foreach($form->get('verifications') as $verificationForm){
                $photoFile = $verificationForm->get('photo')->getData();
                if($photoFile){
                    $originalFileName = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFileName = $slugger->slug($originalFileName);
                    $newFileName = $safeFileName . '-' . uniqid() . '.' . $photoFile->guessExtension();

                    try {
                        $photoFile->move(
                            $this->getParameter('upload_directory'),
                            $newFileName
                        );
                        $verificationForm->getData()->setPhoto($newFileName);
                    } catch(FileException $e) {
                        $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image.');
                    }
                }
            }

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
