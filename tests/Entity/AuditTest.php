<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Auditeur;
use App\Entity\Audit;
use App\Entity\Site;
use App\Entity\Verification;

class AuditTest extends TestCase
{
    public function testGetDateHeureAudit(): void 
    {
        $audit = new Audit();
        $date = new \DateTime();
        $audit->setDateHeureAudit($date);
        self::assertSame($date, $audit->getDateHeureAudit());
    }

    public function testGetZone(): void 
    {
        $audit = new Audit();
        $audit->setZone('Couloir');
        self::assertSame('Couloir', $audit->getZone());
    }

    public function testGetGraph(): void 
    {
        $audit = new Audit();
        $audit->setGraph('123.png');
        self::assertSame('123.png', $audit->getGraph());
    }

    public function testGetAuditeur(): void 
    {
        $audit = new Audit();
        $auditeur = new Auditeur();

        $audit->setAuditeur($auditeur);
        $auditeur->addAudit($audit);

        self::assertSame($audit, $auditeur->getAudits()->first());
        self::assertSame($auditeur, $audit->getAuditeur());
    }

    public function testGetSite(): void 
    {
        $audit = new Audit();
        $site = new Site();

        $audit->setSite($site);
        $site->addAudit($audit);

        self::assertSame($audit, $site->getAudits()->first());
        self::assertSame($site, $audit->getSite());
    }

    public function testAddVerification(): void 
    {
        $audit = new Audit();
        $verification = new Verification();

        $audit->addVerification($verification);

        self::assertCount(1, $audit->getVerifications());
        self::assertSame($verification, $audit->getVerifications()->first());
        self::assertSame($audit, $verification->getAudit());
    }

    public function testRemoveVerification(): void 
    {
        $audit = new Audit();
        $verification = new Verification();

        $audit->addVerification($verification);
        $audit->removeVerification($verification);

        self::assertCount(0, $audit->getVerifications());
        self::assertNull($verification->getAudit());
    }


}