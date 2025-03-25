<?php 

namespace App\Tests\Entity;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use App\Entity\Auditeur;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AuditeurValidatorTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void 
    {
        self::bootKernel();
        $this->validator = static::getContainer()->get('validator');
    }

    public function testValidOperation(): void 
    {
        $auditeur = new Auditeur();
        $auditeur->setNom('Nom valide');
        $auditeur->setPrenom('Prénom valide.');

        $violations = $this->validator->validate($auditeur);

        self::assertCount(0, $violations, 'L\'auditeur doit respecter les contraintes de validation');
    }

    public function testInvalidOperationNom(): void 
    {
        $auditeur = new Auditeur();
        $auditeur->setNom('');
        $auditeur->setPrenom('Prénom valide.');

        $violations = $this->validator->validate($auditeur);

        self::assertCount(2, $violations, 'L\'auditeur doit avoir un nom d\'au moins deux caractères');
    }

    public function testInvalidOperationPrenom(): void 
    {
        $auditeur = new Auditeur();
        $auditeur->setNom('Nom valide');
        $auditeur->setPrenom('');

        $violations = $this->validator->validate($auditeur);

        self::assertCount(2, $violations, 'L\'auditeur doit avoir un prénom d\'au moins deux caractères');
    }
}