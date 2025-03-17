<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Site;
use App\Entity\Audit;

class SiteTest extends TestCase
{
    public function testGetNomSite(): void 
    {
        $site = new Site();
        $site->setNomSite('SNCF');
        self::assertSame('SNCF', $site->getNomSite());
    }

    public function testGetMailContact(): void 
    {
        $site = new Site();
        $site->setMailContact('test@mail.com');
        self::assertSame('test@mail.com', $site->getMailContact());
    }

    public function testAddAudit(): void 
    {
        $site = new Site();
        $audit = new Audit();

        $site->addAudit($audit);

        self::assertCount(1, $site->getAudits());
        self::assertSame($audit, $site->getAudits()->first());
        self::assertSame($site, $audit->getSite());
    }

    public function testRemoveAudit(): void 
    {
        $site = new Site();
        $audit = new Audit();

        $site->addAudit($audit);
        $site->removeAudit($audit);

        self::assertCount(0, $site->getAudits());
        self::assertNull($audit->getSite());
    }

    public function testBidirectionalRelation(): void
    {
        $site = new Site();
        $audit = new Audit();

        $site->addAudit($audit);

        self::assertTrue($site->getAudits()->contains($audit));
        self::assertSame($site, $audit->getSite());
    }
}