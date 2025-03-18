<?php

namespace App\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Auditeur;
use App\Entity\Audit;
use Doctrine\ORM\EntityManagerInterface;

class AuditeurRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void 
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testCreateAuditeur(): void 
    {
        $auditeur = new Auditeur();
        $auditeur->setNom('Test');
        $auditeur->setPrenom('Martin');

        $this->entityManager->persist($auditeur);
        $this->entityManager->flush();

        $auditeurRepository = $this->entityManager->getRepository(Auditeur::class);
        $savedAuditeur = $auditeurRepository->findOneBy(['nom' => 'Test']);

        $this->assertNotNull($savedAuditeur);
        $this->assertSame('Test', $savedAuditeur->getNom());
    }

    public function testFindAuditeurById(): void 
    {
        $auditeur = new Auditeur();
        $auditeur->setNom('Test');
        $auditeur->setPrenom('Martin');

        $this->entityManager->persist($auditeur);
        $this->entityManager->flush();

        $auditeurRepository = $this->entityManager->getRepository(Auditeur::class);
        $savedAuditeur = $auditeurRepository->find($auditeur->getId());

        $this->assertSame('Test', $savedAuditeur->getNom());
        $this->assertSame('Martin', $savedAuditeur->getPrenom());
    }

    public function testRemoveAuditeur(): void 
    {
        $auditeur = new Auditeur();
        $auditeur->setNom('TestDelete');
        $auditeur->setPrenom('Martin');

        $this->entityManager->persist($auditeur);
        $this->entityManager->flush();

        $this->entityManager->remove($auditeur);
        $this->entityManager->flush();

        $auditeurRepository = $this->entityManager->getRepository(Auditeur::class);
        $deletedAuditeur = $auditeurRepository->findOneBy(['nom' => 'TestDelete']);

        $this->assertNull($deletedAuditeur);
    }
}