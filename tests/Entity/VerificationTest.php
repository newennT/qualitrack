<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Auditeur;
use App\Entity\Verification;
use App\Entity\Audit;
use App\Entity\Operation;


class VerificationTest extends TestCase
{
    public function testIsEstConforme(): void
    {
        $verification = new Verification();
        self::assertTrue($verification->isEstConforme());
    }

    public function testSetEstConforme(): void
    {
        $verification = new Verification();
        $verification->setEstConforme(false);
        self::assertFalse($verification->isEstConforme());
    }

    public function testGetCommentaire(): void 
    {
        $verification = new Verification();
        $verification->setCommentaire('Commentaire');
        self::assertSame('Commentaire', $verification->getCommentaire());
    }

    public function testGetPhoto(): void 
    {
        $verification = new Verification();
        $verification->setPhoto('photo.jpg');
        self::assertSame('photo.jpg', $verification->getPhoto());
    }

    public function testGetOperation(): void 
    {
        $verification = new Verification();
        $operation = new Operation();

        $verification->setOperation($operation);

        self::assertSame($operation, $verification->getOperation());
    }

    public function testGetAudit(): void 
    {
        $verification = new Verification();
        $audit = new Audit();

        $verification->setAudit($audit);

        self::assertSame($audit, $verification->getAudit());
    }
}