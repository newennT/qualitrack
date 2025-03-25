<?php 

namespace App\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use App\Repository\SiteRepository;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class SiteControllerTest extends WebTestCase 
{
    private KernelBrowser $client;

    protected function setUp(): void 
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testIndexSiteAsLoggedAdmin(): void 
    {   
        $this->loginAsAdmin();

        $this->client->request('GET', '/admin/site');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }

    public function testNewSiteAsLoggedAdmin(): void 
    {
        $this->loginAsAdmin();

        $siteNomSite = $this->generateRandomString(10);
        $siteMailContact = "contact@gmail.com";

        $this->client->request('GET', '/admin/site/new');
        $this->assertResponseIsSuccessful();

        $this->client->submitForm('Save', [
            'site[nom_site]' => $siteNomSite,
            'site[mail_contact]' => $siteMailContact
        ]);

        $this->assertResponseRedirects('/admin/site', Response::HTTP_SEE_OTHER);

        $siteRepository = static::getContainer()->get(SiteRepository::class);

        $site = $siteRepository->findOneBy(['nom_site' => $siteNomSite]);

        $this->assertNotNull($site);
        $this->assertSame($siteNomSite, $site->getNomSite());
    }

    public function testEditSiteAsLoggedAdmin(): void 
    {
        $this->loginAsAdmin();

        $newSiteNomSite = $this->generateRandomString(8);
        $siteRepository = static::getContainer()->get(SiteRepository::class);
        $site = $siteRepository->findOneBy([]);
        $this->client->request('GET', '/admin/site/' . $site->getId() . '/edit');
        
        $this->assertResponseIsSuccessful();
        $this->client->submitForm('Update', [
            'site[nom_site]' => $newSiteNomSite,
        ]);

        $this->assertResponseRedirects('/admin/site', Response::HTTP_SEE_OTHER);

        $site = $siteRepository->find($site->getId());

        $this->assertSame($newSiteNomSite, $site->getNomSite());
    }
    
    public function testShowSiteAsLoggedAdmin(): void 
    {
        $this->loginAsAdmin();

        $siteRepository = static::getContainer()->get(SiteRepository::class);
        $site = $siteRepository->findOneBy([]);

        $this->client->request('GET', '/admin/site/'. $site->getId());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }

    public function testAccessDeniedIndexSite(): void 
    {
        $this->client->request('GET', '/admin/site');
        $this->assertResponseRedirects('/login');
    }

    public function testAccessDeniedShowSite(): void 
    {
        $siteRepository = static::getContainer()->get(SiteRepository::class);
        $site = $siteRepository->findOneBy([]);

        $this->client->request('GET', '/admin/site/' . $site->getId());
        $this->assertResponseRedirects('/login');
    }

    public function testAccessDeniedNewSite(): void 
    {
        $this->client->request('GET', '/admin/site/new');
        $this->assertResponseRedirects('/login');
    }

    public function testAccessDeniedEditSite(): void 
    {
        $siteRepository = static::getContainer()->get(SiteRepository::class);
        $site = $siteRepository->findOneBy([]);

        $this->client->request('GET', '/admin/site/' . $site->getId() . '/edit');
        $this->assertResponseRedirects('/login');
    }

    public function testDeleteSiteAsLoggedAdmin(): void 
    {
        $this->loginAsAdmin();

        $siteRepository = static::getContainer()->get(SiteRepository::class);
        $site = $siteRepository->findOneBy([]);
        
        $crawler = $this->client->request('GET', '/admin/site/' . $site->getId());
        $this->client->submit($crawler->filter('#delete-form')->form());

        $this->assertResponseRedirects('/admin/site', Response::HTTP_SEE_OTHER);
        $this->assertNull($siteRepository->find($site->getId()));
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