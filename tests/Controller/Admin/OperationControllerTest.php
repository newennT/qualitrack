<?php 

namespace App\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use App\Repository\OperationRepository;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class OperationControllerTest extends WebTestCase 
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

        $this->client->request('GET', '/admin/operation');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }

    public function testNew(): void 
    {
        $this->loginAsAdmin();

        $operationDescription = $this->generateRandomString(50);
        $operationNom = $this->generateRandomString(10);
        $operationSupport = $this->generateRandomString(15);
        $operationCritere = $this->generateRandomString(50);

        $this->client->request('GET', '/admin/operation/new');
        $this->assertResponseIsSuccessful();

        $this->client->submitForm('Save', [
            'operation[description]' => $operationDescription,
            'operation[nom]' => $operationNom,
            'operation[support]' => $operationSupport,
            'operation[critere]' => $operationCritere,
        ]);

        $this->assertResponseRedirects('/admin/operation', Response::HTTP_SEE_OTHER);

        $operationRepository = static::getContainer()->get(OperationRepository::class);

        $operation = $operationRepository->findOneBy(['nom' => $operationNom]);

        $this->assertNotNull($operation);
        $this->assertSame($operationDescription, $operation->getDescription());
        $this->assertSame($operationNom, $operation->getNom());
        $this->assertSame($operationCritere, $operation->getCritere());
        $this->assertSame($operationSupport, $operation->getSupport());
    }

    public function testEdit(): void 
    {
        $this->loginAsAdmin();

        $newOperationDescription = $this->generateRandomString(120);

        $operationRepository = static::getContainer()->get(OperationRepository::class);
        $operation = $operationRepository->findOneBy([]);

        $this->client->request('GET', '/admin/operation/' . $operation->getId() . '/edit');
        $this->assertResponseIsSuccessful();
        $this->client->submitForm('Update', [
            'operation[description]' => $newOperationDescription,
        ]);

        $this->assertResponseRedirects('/admin/operation', Response::HTTP_SEE_OTHER);

        $operation = $operationRepository->find($operation->getId());
        $this->assertSame($newOperationDescription, $operation->getDescription());

    }

    public function testShow(): void 
    {
        $this->loginAsAdmin();

        $operationRepository = static::getContainer()->get(OperationRepository::class);
        $operation = $operationRepository->findOneBy([]);

        $this->client->request('GET', '/admin/operation/'. $operation->getId());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }

    public function testAccessDeniedIndex(): void 
    {
        $this->client->request('GET', '/admin/operation');
        $this->assertResponseRedirects('/login');
    }

    public function testAccessDeniedShow(): void 
    {
        $operationRepository = static::getContainer()->get(OperationRepository::class);
        $operation = $operationRepository->findOneBy([]);

        $this->client->request('GET', '/admin/operation/' . $operation->getId());
        $this->assertResponseRedirects('/login');
    }

    public function testAccessDeniedNew(): void 
    {
        $this->client->request('GET', '/admin/operation/new');
        $this->assertResponseRedirects('/login');
    }

    public function testAccessDeniedEdit(): void 
    {
        $operationRepository = static::getContainer()->get(OperationRepository::class);
        $operation = $operationRepository->findOneBy([]);

        $this->client->request('GET', '/admin/operation/' . $operation->getId() . '/edit');
        $this->assertResponseRedirects('/login');
    }

    public function testDelete(): void 
    {
        $this->loginAsAdmin();

        $operationRepository = static::getContainer()->get(OperationRepository::class);
        $operation = $operationRepository->findOneBy([]);

        $crawler = $this->client->request('GET', '/admin/operation/' . $operation->getId());
        $this->client->submit($crawler->filter('#delete-form')->form());

        $this->assertResponseRedirects('/admin/operation', Response::HTTP_SEE_OTHER);
        $this->assertNull($operationRepository->find($operation->getId()));
    }






    private function loginAsAdmin(): void
    {
        $userRepository = $this->client->getContainer()->get(UserRepository::class);
        $loggedUser = $userRepository->findOneByEmail('test@gmail.com');
        $this->client->loginUser($loggedUser);
    }

    private function generateRandomString(int $length): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyz_ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return mb_substr(str_shuffle(str_repeat($chars, (int) ceil($length / mb_strlen($chars)))), 1, $length);
    }
}