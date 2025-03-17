<?php 

namespace App\Tests\Entity;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use App\Entity\Verification;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class VerificationrValidatorTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void 
    {
        self::bootKernel();
        $this->validator = static::getContainer()->get('validator');
    }

    public function testValidOperationTrue(): void 
    {
        $verification = new Verification();
        $verification->setEstConforme(true);

        $violations = $this->validator->validate($verification);

        self::assertCount(0, $violations, 'La vérification doit respecter les contraintes de validation');
    }

    public function testValidOperationFalseCommentaire(): void 
    {
        $verification = new Verification();
        $verification->setEstConforme(false);
        $verification->setCommentaire('');
        $verification->setPhoto('photo.jpg');

        $violations = $this->validator->validate($verification);

        self::assertCount(2, $violations, 'La vérification doit respecter les contraintes de validation');

    }

    public function testValidOperationFalsePhoto(): void 
    {
        $verification = new Verification();
        $verification->setEstConforme(false);
        $verification->setCommentaire('Commentaire non conforme');
        $verification->setPhoto('');

        $violations = $this->validator->validate($verification);

        self::assertCount(1, $violations, 'La vérification doit respecter les contraintes de validation');

    }
}