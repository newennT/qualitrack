<?php 

namespace App\Tests\Entity;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use App\Entity\Audit;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AuditValidatorTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void 
    {
        self::bootKernel();
        $this->validator = static::getContainer()->get('validator');
    }

    public function testValidZone(): void 
    {
        $audit = new Audit();
        $audit->setZone('Couloir');

        $violations = $this->validator->validate($audit);

        self::assertCount(0, $violations, 'L\'audit doit respecter les contraintes de validation');
    }
}