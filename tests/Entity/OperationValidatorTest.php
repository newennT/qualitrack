<?php 

namespace App\Tests\Entity;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use App\Entity\Operation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OperationValidatorTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void 
    {
        self::bootKernel();
        $this->validator = static::getContainer()->get('validator');
    }

    public function testValidOperation(): void 
    {
        $operation = new Operation();
        $operation->setNom('Opération valide');
        $operation->setDescription('Description valide.');
        $operation->setSupport('Support valide');
        $operation->setEstActif(true);
        $operation->setCritere('Critère valide.');

        $violations = $this->validator->validate($operation);

        self::assertCount(0, $violations, 'L\'opération doit respecter les contraintes de validation');
    }

    public function testInvalidOperationNom(): void 
    {
        $operation = new Operation();
        $operation->setNom('');
        $operation->setDescription('Description valide.');
        $operation->setSupport('Support valide');
        $operation->setEstActif(true);
        $operation->setCritere('Critère valide.');

        $violations = $this->validator->validate($operation);

        self::assertCount(2, $violations, 'L\'opération doit avoir un nom d\'au moins deux caractères');
    }

    public function testInvalidOperationNomValid(): void 
    {
        $operation = new Operation();
        $operation->setNom('a');
        $operation->setDescription('Description valide.');
        $operation->setSupport('Support valide');
        $operation->setEstActif(true);
        $operation->setCritere('Critère valide.');

        $violations = $this->validator->validate($operation);

        self::assertCount(1, $violations, 'L\'opération doit avoir un nom d\'au moins deux caractères');
    }

    public function testInvalidOperationDescription(): void 
    {
        $operation = new Operation();
        $operation->setNom('Nom valide');
        $operation->setDescription('');
        $operation->setSupport('Support valide');
        $operation->setEstActif(true);
        $operation->setCritere('Critère valide.');

        $violations = $this->validator->validate($operation);

        self::assertCount(2, $violations, 'L\'opération doit avoir une description d\'au moins cinq caractères');
    }

    public function testInvalidOperationSupport(): void 
    {
        $operation = new Operation();
        $operation->setNom('Nom valide');
        $operation->setDescription('Description valide.');
        $operation->setSupport('');
        $operation->setEstActif(true);
        $operation->setCritere('Critère valide.');

        $violations = $this->validator->validate($operation);

        self::assertCount(2, $violations, 'L\'opération doit avoir un support d\'au moins deux caractères');
    }

    public function testInvalidOperationCritere(): void 
    {
        $operation = new Operation();
        $operation->setNom('Nom valide');
        $operation->setDescription('Description valide.');
        $operation->setSupport('Support valide');
        $operation->setEstActif(true);
        $operation->setCritere('');

        $violations = $this->validator->validate($operation);

        self::assertCount(2, $violations, 'L\'opération doit avoir un critère d\'au moins deux caractères');
    }
}