<?php 

namespace App\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use App\Repository\AuditeurRepository;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class AuditeurControllerTest extends WebTestCase 
{
    private KernelBrowser $client;

    protected function setUp(): void 
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testIndexAuditeurAsLoggedAdmin(): void 
    {   
        $this->loginAsAdmin();

        $this->client->request('GET', '/admin/auditeur');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }

    public function testNewAuditeurAsLoggedAdmin(): void 
    {
        $this->loginAsAdmin();

        $auditeurNom = $this->generateRandomString(10);
        $auditeurPrenom = $this->generateRandomString(8);

        $this->client->request('GET', '/admin/auditeur/new');
        $this->assertResponseIsSuccessful();

        $this->client->submitForm('Save', [
            'auditeur[nom]' => $auditeurNom,
            'auditeur[prenom]' => $auditeurPrenom,
        ]);

        $this->assertResponseRedirects('/admin/auditeur', Response::HTTP_SEE_OTHER);

        $auditeurRepository = static::getContainer()->get(AuditeurRepository::class);

        $auditeur = $auditeurRepository->findOneBy(['nom' => $auditeurNom]);

        $this->assertNotNull($auditeur);
        $this->assertSame($auditeurNom, $auditeur->getNom());
        $this->assertSame($auditeurPrenom, $auditeur->getPrenom());
    }

    public function testEditAuditeurAsLoggedAdmin(): void 
    {
        $this->loginAsAdmin();

        $newAuditeurPrenom = $this->generateRandomString(8);
        $auditeurRepository = static::getContainer()->get(AuditeurRepository::class);
        $auditeur = $auditeurRepository->findOneBy([]);
        $this->client->request('GET', '/admin/auditeur/' . $auditeur->getId() . '/edit');
        
        $this->assertResponseIsSuccessful();
        $this->client->submitForm('Update', [
            'auditeur[prenom]' => $newAuditeurPrenom,
        ]);

        $this->assertResponseRedirects('/admin/auditeur', Response::HTTP_SEE_OTHER);

        $auditeur = $auditeurRepository->find($auditeur->getId());

        $this->assertSame($newAuditeurPrenom, $auditeur->getPrenom());
    }
    
    public function testShowAuditeurAsLoggedAdmin(): void 
    {
        $this->loginAsAdmin();

        $auditeurRepository = static::getContainer()->get(AuditeurRepository::class);
        $auditeur = $auditeurRepository->findOneBy([]);

        $this->client->request('GET', '/admin/auditeur/'. $auditeur->getId());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }

    public function testAccessDeniedIndexAuditeur(): void 
    {
        $this->client->request('GET', '/admin/auditeur');
        $this->assertResponseRedirects('/login');
    }

    public function testAccessDeniedShowAuditeur(): void 
    {
        $auditeurRepository = static::getContainer()->get(AuditeurRepository::class);
        $auditeur = $auditeurRepository->findOneBy([]);

        $this->client->request('GET', '/admin/auditeur/' . $auditeur->getId());
        $this->assertResponseRedirects('/login');
    }

    public function testAccessDeniedNewAuditeur(): void 
    {
        $this->client->request('GET', '/admin/auditeur/new');
        $this->assertResponseRedirects('/login');
    }

    public function testAccessDeniedEditAuditeur(): void 
    {
        $auditeurRepository = static::getContainer()->get(AuditeurRepository::class);
        $auditeur = $auditeurRepository->findOneBy([]);

        $this->client->request('GET', '/admin/auditeur/' . $auditeur->getId() . '/edit');
        $this->assertResponseRedirects('/login');
    }

    public function testDeleteAuditeurAsLoggedAdmin(): void 
    {
        $this->loginAsAdmin();

        $auditeurRepository = static::getContainer()->get(AuditeurRepository::class);
        $auditeur = $auditeurRepository->findOneBy([]);
        
        $crawler = $this->client->request('GET', '/admin/auditeur/' . $auditeur->getId());
        $this->client->submit($crawler->filter('#delete-form')->form());

        $this->assertResponseRedirects('/admin/auditeur', Response::HTTP_SEE_OTHER);
        $this->assertNull($auditeurRepository->find($auditeur->getId()));
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