<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 07/06/2018
 * Time: 09:49
 */

namespace App\Tests\integration;

use App\Entity\Agency;
use App\Entity\Client;
use App\Entity\Movement as MovementEntity;
use App\Entity\Movement;
use App\Repository\Stock\StockMovementRepository;
use App\Repository\Stock\StockStatusRepository;
use App\Service\SoapComplexType\Movement as MovementComplexType;
use App\Service\StockSoapService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StockSoapServiceTest extends KernelTestCase
{
    const DEAL_VALUE_MUST_BE_DELETED = 'TEST_INTEGRATION_MUST_BE_DELETE';

    private $reference1;
    private $reference2;
    private $reference3;
    private $reference4;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var StockSoapService
     */
    private $soapService;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->soapService = self::$kernel->getContainer()
            ->get(StockSoapService::class);

        $createdAt = new \DateTime();
        $createdAt->modify("+20 year");

        $this->createdAt = $createdAt->format('Y-m-d H:i:s');

        $this->reference1 = uniqid("ref1");
        $this->reference2 = uniqid("ref2");
        $this->reference3 = uniqid("ref3");
        $this->reference4 = uniqid("ref4");

    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function testCrudMethods()
    {
        /**
         * @var $repository StockMovementRepository
         */
        $repository = $this->entityManager->getRepository(MovementEntity::class);

        // Initial Check database
        $entities = $repository->findBy(["createdAt" => new \DateTime($this->createdAt)]);
        $this->assertCount(0, $entities, "MovementEntities with createdAt at ".$this->createdAt. " has detected. Please delete entities");


        // Test create
        $this->soapService->create($this->getMovementComplexType($this->reference1));
        $this->soapService->create($this->getMovementComplexType($this->reference2));
        $this->soapService->create($this->getMovementComplexType($this->reference3));
        $this->soapService->create($this->getMovementComplexType($this->reference4));
        $entitiesAfter4Insert = $repository->findBy(["createdAt" => new \DateTime($this->createdAt)]);
        $this->assertCount(4, $entitiesAfter4Insert);

        // Test Update
        /**
         * @var $movementBeforeUpdate Movement
         */
        $movementBeforeUpdate = $repository->findOneBy(["internalOrderReference" => $this->reference1]);
        $expectedId = $movementBeforeUpdate->getId();
        $notExpectedMovement = $movementBeforeUpdate->getMovement();
        $this->soapService->updateByCDE($this->reference1, $this->getMovementComplexType($this->reference1));
        /**
         * @var $movementAfterUpdate Movement
         */

        $movementAfterUpdate = $repository->findOneBy(["internalOrderReference" => $this->reference1]);

        $this->assertEquals($expectedId, $movementAfterUpdate->getId());
        $this->assertNotEquals($notExpectedMovement, $movementAfterUpdate->getMovement());

        // Test Delete
        $this->soapService->deleteByCreatedAt($this->createdAt);
        $entitiesAfterDelete = $repository->findBy(["createdAt" => new \DateTime($this->createdAt)]);
        $this->assertCount(0, $entitiesAfterDelete);
    }

    /**
     * @param $reference
     * @return MovementComplexType
     */
    private function getMovementComplexType($reference){

        $movementComplexType = new MovementComplexType();

        $agencies = $this->entityManager->getRepository(Agency::class)->findAll();
        $clients = $this->entityManager->getRepository(Client::class)->findAll();


        $movementComplexType->DT_APP = $this->createdAt;
        $movementComplexType->CDE = $reference;
        $movementComplexType->DATE = '2018-06-13 09:53:51';
        $movementComplexType->TPE = uniqid();
        $movementComplexType->TPE_TRA = uniqid();
        $movementComplexType->MVT = uniqid();
        $movementComplexType->QTE_REEL = 8;
        $movementComplexType->AFFAIRE = static::DEAL_VALUE_MUST_BE_DELETED;
        $movementComplexType->REF_CDE = '';
        $movementComplexType->PU = 2.5;
        $movementComplexType->DF = 2;
        $movementComplexType->TYPE_FACTU = StockStatusRepository::BILL_TYPE_M;
        $movementComplexType->REF1 = 3;
        $movementComplexType->AGENCE_CODE = $agencies[rand(0, count($agencies)-1)]->getCode();
        $movementComplexType->CLIENT_CODE = $clients[rand(0, count($clients)-1)]->getSourceReference();

        return $movementComplexType;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->getConnection()->query("DELETE FROM movement WHERE deal = '".static::DEAL_VALUE_MUST_BE_DELETED."'");
    }
}