<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\AuditRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Service\PdfGeneratorService;

class AuditeurControllerTest extends WebTestCase 
{
    protected function setUp(): void 
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testAuditNew(): void 
    {
        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Opération de contrôle');
    } 

    public function testAuditNewForm(): void 
    {
        $auditZone = $this->generateRandomString(10);
        $auditScoreConformite = 62.0;
        $this->client->request('GET', '/');

        $this->client->submitForm('Envoyer', [
            'audit[zone]' => $auditZone,
            'audit[score_conformite]' => $auditScoreConformite,
        ]);

        $this->assertResponseRedirects('/validation', Response::HTTP_SEE_OTHER);

        $auditRepository = static::getContainer()->get(AuditRepository::class);

        $audit = $auditRepository->findOneBy(['zone' => $auditZone]);

        $this->assertNotNull($audit);
        $this->assertSame($auditZone, $audit->getZone());
        $this->assertSame($auditScoreConformite, $audit->getScoreConformite());
    }

    public function testSendPdf(): void 
    {
        $mailer = $this->createMock(MailerInterface::class);
        static::getContainer()->set('mailer', $mailer);

        $pdfGenerator = $this->createMock(PdfGeneratorService::class);
        $pdfGenerator->method('getPdf')->willReturn('fake_pdf_content');
        static::getContainer()->set(PdfGeneratorService::class, $pdfGenerator);

        $auditZone = $this->generateRandomString(10);
        $auditScoreConformite = 62.0;
        $this->client->request('GET', '/');
        $this->client->submitForm('Envoyer', [
            'audit[zone]' => $auditZone,
            'audit[score_conformite]' => $auditScoreConformite,
        ]);

        $this->assertEmailCount(1);
        $email = $this->getMailerMessage();
        $this->assertEmailSubjectContains($email, 'Audit');
        $this->assertEmailAttachmentCount($email, 1);

    }




    private function generateRandomString(int $length): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return mb_substr(str_shuffle(str_repeat($chars, (int) ceil($length / mb_strlen($chars)))), 1, $length);
    }
}