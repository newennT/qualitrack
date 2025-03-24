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

        $this->client->request('GET', '/admin/audit');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }


    private function loginAsAdmin(): void
    {
        $userRepository = $this->client->getContainer()->get(UserRepository::class);
        $loggedUser = $userRepository->findOneByEmail('test@gmail.com');
        $this->client->loginUser($loggedUser);
    }

    private function generateRandomString(int $length): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return mb_substr(str_shuffle(str_repeat($chars, (int) ceil($length / mb_strlen($chars)))), 1, $length);
    }

}