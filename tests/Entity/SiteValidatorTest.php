<?php 

namespace App\Tests\Entity;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use App\Entity\Site;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SiteValidatorTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void 
    {
        self::bootKernel();
        $this->validator = static::getContainer()->get('validator');
    }

    public function testValidOperation(): void 
    {
        $site = new Site();
        $site->setNomSite('Nom valide');
        $site->setMailContact('test@gmail.com');

        $violations = $this->validator->validate($site);

        self::assertCount(0, $violations, 'Le site doit respecter les contraintes de validation');
    }

    public function testInvalidOperationNom(): void 
    {
        $site = new Site();
        $site->setNomSite('');
        $site->setMailContact('test@gmail.com');

        $violations = $this->validator->validate($site);

        self::assertCount(2, $violations, 'Le site doit avoir un nom d\'au moins deux caractères');
    }

    public function testInvalidOperationMailContact(): void 
    {
        $site = new Site();
        $site->setNomSite('Nom valide');
        $site->setMailContact('');

        $violations = $this->validator->validate($site);

        self::assertCount(2, $violations, 'Le site doit avoir une adresse mail de contact valide');
    }
}