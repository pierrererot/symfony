<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 31/05/2018
 * Time: 11:40
 */

namespace App\Tests\integration;

use App\Entity\Address;
use App\Entity\Client;
use App\Entity\Contact;
use App\Entity\OrderStatus;
use App\Service\ClientSoapService;
use App\Service\SoapComplexType\Checkpoint;
use App\Service\SoapComplexType\Command;
use App\Service\SoapComplexType\Phase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Constraints\DateTime;

class ClientSoapServiceTest extends KernelTestCase
{
    const PREFIX_RESOURCE_NAME = 'TEST_CLIENT_SOAP_SERVICE_';
    const ORDER_1 = 'ORDER1';
    const ORDER_1_PHASE_1 = 'ORDER1_PHASE1';
    const ORDER_2 = 'ORDER2';
    const ORDER_3 = 'ORDER3';
    const ORDER_3_PHASE_1 = 'ORDER3_PHASE1';
    const ORDER_4 = 'ORDER4';
    const ORDER_4_PHASE_1 = 'ORDER4_PHASE1';
    const ORDER_4_PHASE_2 = 'ORDER4_PHASE2';
    const ORDER_5 = 'ORDER5';
    const ORDER_5_PHASE_1 = 'ORDER5_PHASE1';
    const ORDER_5_PHASE_2 = 'ORDER5_PHASE2';

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var ClientSoapService
     */
    private $soapService;
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->soapService =  $kernel->getContainer()
            ->get(ClientSoapService::class);
    }

    public function testUpsertClientWithoutSourceReference()
    {
        $this->assertEquals('The reference \'code\' must be completed (CLT?)',$this->soapService->upsertClient(new \App\Service\SoapComplexType\Client()));
    }

    public function testUpsertClientWithDifferentsUpdate()
    {
        $sourceReference = uniqid("TestUpsert");

        // Create Client Without informations
        $client = new \App\Service\SoapComplexType\Client();
        $client->code = $sourceReference;
        $client->informations = new Checkpoint();
        $this->assertTrue($this->soapService->upsertClient($client));

        // Add Information
        $client = new \App\Service\SoapComplexType\Client();
        $client->code = $sourceReference;

        $checkpoint = new Checkpoint();
        $checkpoint->label = "Test Add Information after client creation";
        $client->informations = $checkpoint;
        $this->assertTrue($this->soapService->upsertClient($client));

        // Update Information
        $client = new \App\Service\SoapComplexType\Client();
        $client->code = $sourceReference;
        $checkpoint = new Checkpoint();
        $checkpoint->label = "Test Update Information after Add Information to Client";
        $client->informations = $checkpoint;
        $this->assertTrue($this->soapService->upsertClient($client));

    }


    public function testUpdateWithWrongClient(){

        $clientReference = uniqid('DONOTEXIST');
        $result = $this->soapService->updateClientCommands($clientReference, []);
        $this->assertEquals("Client $clientReference does not exist", $result);
    }

    public function testUpdateWithEmptyOrders(){

        $client = $this->createClient('emptyOrder', 'testUpdateWithEmptyOrders');
        $result = $this->soapService->updateClientCommands($client->getSourceReference(), []);
        $this->assertEquals("No Commands detected for Client ".$client->getSourceReference(), $result);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testCompleteScenario()
    {
        // Create new Client
        $client = $this->createClient($ref=uniqid(static::PREFIX_RESOURCE_NAME), "testUpdateWithOneNewOrder()");

        // Update new Client with only one Order
        $this->firstScenario_UpdateClientWithOnlyOneOrder($client);

        // Update same Client with 4 new Order
        //  1 Order with 0 PHASE
        //  1 Order with 0 PHASE
        //  1 Order with 2 PHASE With 0 checkpoint
        //  1 Order with 2 PHASE first with 1 checkout and seconds without checkout
        $this->secondScenario_UpdateClientWithFourOrdersWithInitialPhaseValues($client);


        // Update same Client with same last Orders but change PHASEs (test CRUD OrderPHASE)
        //  no change for first Order
        //  1 Order with 1 PHASE (0 to 1)
        //  1 Order with 1 PHASE (2 to 1)
        //  1 Order with 0 PHASE (2 to 0)
        $this->thirdScenario_UpdateClientWithFourOrdersWithSecondPhaseValues($client);
    }

    /**
     * @param Client $client
     * @throws \Doctrine\ORM\ORMException
     */
    private function firstScenario_UpdateClientWithOnlyOneOrder(Client $client)
    {
        // ASSERT INITIAL STANCE OF CLIENT
        $this->assertCount(0,$client->getOrders());

        // PREPARE ORDERS
        $commands = [];
        $commands[] = $this->getFirstCommand();

        // CALL SERVICE
        $this->assertTrue($this->soapService->updateClientCommands($client->getSourceReference(), $commands));

        // ASSERT DATABASE

        // - check Order
        $this->assertCount(1,$client->getOrders());
        $firstOrder = $client->getOrders()[0];
        $this->assertNotNull($firstOrder->getId());
        $this->assertEquals(static::ORDER_1, $firstOrder->getSourceReference());

        // - check PHASE
        $this->assertCount(1, $firstOrder->getPhases());
        $firstPhase = $firstOrder->getPhases()[0];
        $this->assertNotNull( $firstPhase->getId());
        $this->assertEquals(static::ORDER_1_PHASE_1, $firstPhase->getSourceReference());


    }

    /**
     * @param Client $client
     * @throws \Doctrine\ORM\ORMException
     */
    private function secondScenario_UpdateClientWithFourOrdersWithInitialPHASEValues(Client $client)
    {
        // ASSERT INITIAL DATA
        $this->assertCount(1,$client->getOrders());

        // PREPARE 4 ORDERS
        $orders = [];
        $orders[] = $this->getSecondCommand();
        $orders[] = $this->getThirdCommand();
        $orders[] = $this->getFourthCommand();
        $orders[] = $this->getFifthCommand();

        // CALL SERVICE
        $this->assertTrue($this->soapService->updateClientCommands($client->getSourceReference(), $orders));

        // ASSERT DATA
        // - check Order
        $this->assertCount(5,$client->getOrders());

        $checkedResource = [];
        foreach ($client->getOrders() as $order){
            $this->assertNotNull($order->getId());
            $this->assertNull($order->getUpdatedAt());
            $this->assertNotContains($order->getSourceReference(), $checkedResource, "Duplicate Order !!!");
            switch ($order->getSourceReference()){
                case static::ORDER_1:
                    $this->assertCount(1,$order->getPhases());
                    $this->assertEquals(static::ORDER_1_PHASE_1, $order->getPhases()[0]->getSourceReference());
                    break;
                case static::ORDER_2:
                    $this->assertCount(0,$order->getPhases());
                    break;
                case static::ORDER_3:
                    $this->assertCount(0,$order->getPhases());
                    break;
                case static::ORDER_4:
                    $this->assertCount(2,$order->getPhases());
                    break;
                case static::ORDER_5:
                    $this->assertCount(2,$order->getPhases());
                    break;
            }
            $checkedResource[] = $order->getSourceReference();
        }
    }

    /**
     * @param Client $client
     * @throws \Doctrine\ORM\ORMException
     */
    private function thirdScenario_UpdateClientWithFourOrdersWithSecondPhaseValues(Client $client)
    {
        // ASSERT INITIAL DATA
        $this->assertCount(5,$client->getOrders());

        // PREPARE Command
        $commands = [];
        $commands[] = $this->getSecondCommand();
        $commands[] = $this->getThirdOrderWithPhase();
        $commands[] = $this->getFourthOrderWithOnlyOnePhase();
        $commands[] = $this->getFifthOrderWithoutPhases();

        // CALL SERVICE
        $this->assertTrue($this->soapService->updateClientCommands($client->getSourceReference(), $commands));

        // ASSERT DATA
        // - check Command
        $this->assertCount(5,$client->getOrders());

        $checkedResource = [];
        foreach ($client->getOrders() as $order){

            $this->assertNotNull($order->getId());
            $this->assertNotContains($order->getSourceReference(), $checkedResource, "Duplicate Order !!!");
            switch ($order->getSourceReference()){
                case static::ORDER_1:
                    $this->assertNull($order->getUpdatedAt());
                    break;
                case static::ORDER_2:
                    $this->assertNotNull($order->getUpdatedAt());
                    $this->assertCount(0,$order->getPhases());
                    break;
                case static::ORDER_3:
                    $this->assertNotNull($order->getUpdatedAt());
                    $this->assertCount(1,$order->getPhases());
                    $this->assertEquals(static::ORDER_3_PHASE_1, $order->getPhases()[0]->getSourceReference());
                    break;
                case static::ORDER_4:
                    $this->assertNotNull($order->getUpdatedAt());
                    $this->assertCount(1,$order->getPhases());
                    $this->assertEquals(static::ORDER_4_PHASE_1, $order->getPhases()[0]->getSourceReference());
                    break;
                case static::ORDER_5:
                    $this->assertNotNull($order->getUpdatedAt());
                    $this->assertCount(0,$order->getPhases());
                    break;
            }
            $checkedResource[] = $order->getSourceReference();
        }
    }

    /**
     * @param $reference
     * @param $createdBy
     * @return Client
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function createClient($reference, $createdBy )
    {
        $client = new Client();
        $client->setCreatedBy(self::class.'__'.$createdBy);
        $client->setSourceReference($reference);

        $em = $this->entityManager;
        $em->persist($client);
        $em->flush();

        return $client;
    }

    /**
     * @return Command
     */
    private function getFirstCommand()
    {
        $phase = new Phase();
        $phase->ottp = static::ORDER_1_PHASE_1;

        $command = new Command();
        $command->ortr = static::ORDER_1;
        $command->phases = [ $phase ];

        return $command;
    }

    /**
     * 0 PHASE
     * @return Command
     */
    private function getSecondCommand()
    {
        $command = new Command();
        $command->ortr = static::ORDER_2;
        $command->phases = [];
        return $command;
    }

    /**
     * 0 PHASE to 1 PHASE
     * @return Command
     */
    private function getThirdOrderWithPhase()
    {
        $phase = new Phase();
        $phase->ottp = static::ORDER_3_PHASE_1;

        $command = $this->getThirdCommand();
        $command->phases = [$phase];

        return $command;
    }

    /**
     * 0 PHASE
     * @return Command
     */
    private function getThirdCommand()
    {
        $command = new Command();
        $command->ortr = static::ORDER_3;
        $command->phases = [];

        return $command;
    }

    /**
     * 2 PHASE without Checkpoint
     * @return Command
     */
    private function getFourthCommand()
    {
        // ORDER_4_PHASE_1
        $phase1 = new Phase();
        $phase1->ottp = static::ORDER_4_PHASE_1;

        // ORDER_4_PHASE_2
        $phase2 = new Phase();
        $phase2->ottp = static::ORDER_4_PHASE_2;

        // ORDER_4
        $command = new Command();
        $command->ortr = static::ORDER_4;
        $command->phases = [ 0 => $phase1, 1 => $phase2 ];

        return $command;
    }

    /**
     * 2 PHASE to 1 PHASE
     * @return Command
     */
    public function getFourthOrderWithOnlyOnePhase()
    {
        $command = $this->getFourthCommand();
        $remainingPhase = null;

        foreach ($command->phases as $phase) {
            if($phase->ottp === static::ORDER_4_PHASE_1 ){
                $remainingPhase = $phase;
            }
        }
        $command->phases = [$remainingPhase];

        return $command;
    }

    /**
     * 2 PHASE: only one with Checkpoint
     * @return Command
     */
    private function getFifthCommand()
    {
        // ORDER_5_PHASE_1
        $phase1 = new Phase();
        $phase1->ottp = static::ORDER_5_PHASE_1;

        // ORDER_5_PHASE_2
        $phase2 = new Phase();
        $phase2->ottp = static::ORDER_5_PHASE_2;

        // ORDER_5
        $command = new Command();
        $command->ortr = static::ORDER_5;
        $command->phases = [ $phase1, $phase2 ];

        return $command;
    }

    /**
     * 2 PHASE to 0 PHASE
     * @return Command
     */
    public function getFifthOrderWithoutPhases()
    {
        $order = $this->getFifthCommand();
        $order->phases = [];

        return $order;
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }

    /**
     * @return int
     */
    private function getRandomStatusId()
    {
        $allStatus = $this->entityManager->getRepository(OrderStatus::class)->findAll();
        array_map(
            function (OrderStatus $status) {
                return $status->getId();
            },
            $allStatus
        );
        return $allStatus[array_rand($allStatus, 1)];
    }
}