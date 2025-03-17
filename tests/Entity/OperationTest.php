<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Operation;
use App\Entity\Verification;

class OperationTest extends TestCase
{
    public function testGetDescription(): void 
    {
        $operation = new Operation();
        $operation->setDescription('Description de l\'opération');
        self::assertSame('Description de l\'opération', $operation->getDescription());
    }

    public function testGetNom(): void 
    {
        $operation = new Operation();
        $operation->setNom('Nettoyage');
        self::assertSame('Nettoyage', $operation->getNom());
    }

    public function testGetSupport(): void 
    {
        $operation = new Operation();
        $operation->setSupport('Mobilier');
        self::assertSame('Mobilier', $operation->getSupport());
    }

    public function testIsEstActif(): void 
    {
        $operation = new Operation();
        $operation->setEstActif(true);
        self::assertTrue($operation->isEstActif());
    }

    public function testGetCritere(): void 
    {
        $operation = new Operation();
        $operation->setCritere('Critère n°5');
        self::assertSame('Critère n°5', $operation->getCritere());
    }

    public function testAddVerification(): void 
    {
        $operation = new Operation();
        $verification = new Verification();

        $operation->addVerification($verification);

        self::assertCount(1, $operation->getVerifications());
        self::assertSame($verification, $operation->getVerifications()->first());
        self::assertSame($operation, $verification->getOperation());
    }

    public function testRemoveVerification(): void 
    {
        $operation = new Operation();
        $verification = new Verification();

        $operation->addVerification($verification);
        $operation->removeVerification($verification);

        self::assertCount(0, $operation->getVerifications());
        self::assertNull($verification->getOperation());
    }

    public function testBidirectionalRelationVerification(): void 
    {
        $operation = new Operation();
        $verification = new Verification();

        $operation->addVerification($verification);

        self::assertTrue($operation->getVerifications()->contains($verification));
        self::assertSame($operation, $verification->getOperation());
    }
}