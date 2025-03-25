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
use App\Service\PdfGeneratorService;
use App\Service\MailerService;
use Symfony\Component\Form\FormError;
use Symfony\Component\Filesystem\Filesystem;


final class AuditController extends AbstractController
{
    #[Route('/', name: 'app_audit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, OperationRepository $operationRepository, SluggerInterface $slugger, PdfGeneratorService $pdfGeneratorService, MailerService $mailerService): Response
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

        $form = $this->createForm(AuditType::class, $audit, [
            'validation_groups' => ['Default', 'verifier_commentaire'],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach($form->get('verifications') as $verificationForm){
                $verificationData = $verificationForm->getData();

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

            // Récupération de l'image du graph pour l'intégrer au pdf
            $graphFile = $form->get('graph')->getData();
            if($graphFile) {
                $graphName = 'graph-' . uniqid() . '.png';
                $graphPath = $this->getParameter('graph_directory') . '/';
                
                try {
                    $graphFile->move($graphPath, $graphName);
                    $audit->setGraph($graphName);

                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'enregistrement du graphique.');
                }

            }

            $entityManager->persist($audit);
            $entityManager->flush();


            // Génération du pdf
            $html = $this->renderView('audit/pdf.html.twig', [
                'audit' => $audit,
            ]);
            $pdfContent = $pdfGeneratorService->getPdf($html);

            $pdfFileName = 'audit-' . $audit->getId() . '.pdf';
            $pdfFilePath = $this->getParameter('pdf_directory') . '/' . $pdfFileName;
            file_put_contents($pdfFilePath, $pdfContent);
            

            // Envoi du mail avec le pdf
            if ($audit->getSite() && $audit->getSite()->getMailContact()){
                $mailerService->sendAudit(
                    $audit->getSite()->getMailContact(),
                    'Audit de ' . $audit->getSite()->getNomSite() . " (" . $audit->getZone() . ") par " . $audit->getAuditeur()->getNom() . " " . $audit->getAuditeur()->getPrenom(),
                    'Audit conforme à ' . $audit->getScoreConformite() . "%",
                    $pdfFilePath
                );
            }

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
