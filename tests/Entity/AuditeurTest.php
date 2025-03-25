<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Auditeur;
use App\Entity\Audit;


class AuditeurTest extends TestCase
{
    public function testGetNom(): void
    {
        $auditeur = new Auditeur();
        $auditeur->setNom('Hubert');
        self::assertSame('Hubert', $auditeur->getNom());
    }

    public function testGetPrenom(): void 
    {
        $auditeur = new Auditeur();
        $auditeur->setPrenom('Martin');
        self::assertSame('Martin', $auditeur->getPrenom());
    }

    public function testAddAudit(): void 
    {
        $auditeur = new Auditeur();
        $audit = new Audit();

        $auditeur->addAudit($audit);

        self::assertCount(1, $auditeur->getAudits());
        self::assertSame($audit, $auditeur->getAudits()->first());
        self::assertSame($auditeur, $audit->getAuditeur());
    }

    public function testRemoveAudit(): void 
    {
        $auditeur = new Auditeur();
        $audit = new Audit();

        $auditeur->addAudit($audit);
        $auditeur->removeAudit($audit);

        self::assertCount(0, $auditeur->getAudits());
        self::assertNull($audit->getAuditeur());
    }

    public function testBidirectionalRelationAudit(): void
    {
        $auditeur = new Auditeur();
        $audit = new Audit();

        $auditeur->addAudit($audit);

        self::assertTrue($auditeur->getAudits()->contains($audit));
        self::assertSame($auditeur, $audit->getAuditeur());
    }
}
