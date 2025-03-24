<?php 

namespace App\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use App\Repository\AuditRepository;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class AuditControllerTest extends WebTestCase 
{
    private KernelBrowser $client;

    protected function setUp(): void 
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testIndex(): void 
    {   
        $this->loginAsAdmin();

        $this->client->request('GET', '/admin');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }

    public function testEdit(): void 
    {
        $this->loginAsAdmin();

        $newAuditZone = $this->generateRandomString(8);
        $auditRepository = static::getContainer()->get(AuditRepository::class);
        $audit = $auditRepository->findOneBy([]);
        $this->client->request('GET', '/admin/audit/' . $audit->getId() . '/edit');
        
        $this->assertResponseIsSuccessful();
        $this->client->submitForm('Envoyer', [
            'audit[zone]' => $newAuditZone,
        ]);

        $this->assertResponseRedirects('/admin', Response::HTTP_SEE_OTHER);

        $audit = $auditRepository->find($audit->getId());

        $this->assertSame($newAuditZone, $audit->getZone());
    }

    public function testShow(): void 
    {
        $this->loginAsAdmin();

        $auditRepository = static::getContainer()->get(AuditRepository::class);
        $audit = $auditRepository->findOneBy([]);

        $this->client->request('GET', '/admin/audit/'. $audit->getId());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }

    public function testAccessDeniedIndex(): void 
    {
        $this->client->request('GET', '/admin');
        $this->assertResponseRedirects('/login');
    }

    public function testAccessDeniedShow(): void 
    {
        $auditRepository = static::getContainer()->get(AuditRepository::class);
        $audit = $auditRepository->findOneBy([]);

        $this->client->request('GET', '/admin/audit/' . $audit->getId());
        $this->assertResponseRedirects('/login');
    }

    public function testAccessDeniedEdit(): void 
    {
        $auditRepository = static::getContainer()->get(AuditRepository::class);
        $audit = $auditRepository->findOneBy([]);

        $this->client->request('GET', '/admin/audit/' . $audit->getId() . '/edit');
        $this->assertResponseRedirects('/login');
    }

    public function testDelete(): void 
    {
        $this->loginAsAdmin();

        $auditRepository = static::getContainer()->get(AuditRepository::class);
        $audit = $auditRepository->findOneBy([]);
        
        $crawler = $this->client->request('GET', '/admin/audit/' . $audit->getId());
        $this->client->submit($crawler->filter('#delete-form')->form());

        $this->assertResponseRedirects('/admin', Response::HTTP_SEE_OTHER);
        $this->assertNull($auditRepository->find($audit->getId()));
    }


    private function loginAsAdmin(): void
    {
        $userRepository = $this->client->getContainer()->get(UserRepository::class);
        $loggedUser = $userRepository->findOneBy(['email' => 'admin@gmail.com']);
        $this->client->loginUser($loggedUser);
    }

    private function generateRandomString(int $length): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return mb_substr(str_shuffle(str_repeat($chars, (int) ceil($length / mb_strlen($chars)))), 1, $length);
    }
}