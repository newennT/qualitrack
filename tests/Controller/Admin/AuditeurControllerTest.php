<?php 

namespace App\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class AuditeurControllerTest extends WebTestCase 
{
    private EntityManagerInterface $entityManager;
    private $client;

    protected function setUp(): void 
    {
        $this->client = static::createClient(); 
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
    }

    public function testIndex(): void 
    {      
        $userRepository = $this->client->getContainer()->get(UserRepository::class);
        $loggedUser = $userRepository->findOneByEmail('test@gmail.com');

        $this->client->loginUser($loggedUser);

        $this->client->request('GET', '/admin/auditeur');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Auditeur index');
    }
}