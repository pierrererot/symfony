<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 12/09/2018
 * Time: 09:44
 */

namespace App\Tests\integration;


use App\Entity\OrderProduct;
use App\Entity\Orders;
use App\Entity\ScvStep;
use App\Entity\SupplyChainVisibility;
use App\Service\OrderSoapService;
use App\Service\SoapComplexType\Product;
use DataTable\src\Request\Order;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OrderSoapServiceTest  extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var OrderSoapService
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
            ->get(OrderSoapService::class);
    }

    public function testReplaceScv()
    {
        // Create Order without Scv
        $sourceReference = uniqid("replaceScv");
        $order = $this->createOrder($sourceReference);
        $this->persistOrder($order);
        $bddOrder = $this->searchOrderBySourceReference($sourceReference);

        // Check BDD
        $this->assertNotNull($bddOrder->getId());
        $this->assertInstanceOf(SupplyChainVisibility::class,$bddOrder->getSupplyChainVisibility());
        $this->assertCount(0, $bddOrder->getSupplyChainVisibility()->getSteps()->toArray());

        // Update Order with SCV
        $steps[] = new \App\Service\SoapComplexType\ScvStep();
        $steps[] = new \App\Service\SoapComplexType\ScvStep();

        $this->assertTrue($this->soapService->replaceScv($bddOrder->getSourceReference(), $steps));

        // Check BDD
        $bddOrderWithFirstScv = $this->searchOrderBySourceReference($sourceReference);
        $this->assertEquals($bddOrder->getId(), $bddOrderWithFirstScv->getId());
        $this->assertEquals($bddOrder->getSupplyChainVisibility()->getId(), $bddOrderWithFirstScv->getSupplyChainVisibility()->getId());
        $this->assertCount(2, $bddOrderWithFirstScv->getSupplyChainVisibility()->getSteps());
        $stepIds = array_map(
            function (ScvStep $step){
                $this->assertNotNull($step->getId());
                return $step->getId();
            },
            $bddOrderWithFirstScv->getSupplyChainVisibility()->getSteps()->toArray()
        );

        // Replace SCV
        $steps2[] = new \App\Service\SoapComplexType\ScvStep();
        $this->assertTrue($this->soapService->replaceScv($bddOrder->getSourceReference(), $steps2));

        // Check BDD
        $bddOrderUpdated = $this->searchOrderBySourceReference($sourceReference);
        $this->assertEquals($bddOrder->getId(), $bddOrderUpdated->getId());
        $this->assertCount(1, $bddOrderUpdated->getSupplyChainVisibility()->getSteps());
        $stepIds2 = array_map(
            function (ScvStep $step){
                $this->assertNotNull($step->getId());
                return $step->getId();
            },
            $bddOrderUpdated->getSupplyChainVisibility()->getSteps()->toArray()
        );
        $this->assertFalse(in_array($stepIds2[0], $stepIds));
    }

    public function testReplaceProducts()
    {
        // Create Order without Product
        $sourceReference = uniqid("replaceProduct");
        $order = $this->createOrder($sourceReference);
        $this->persistOrder($order);
        $bddOrder = $this->searchOrderBySourceReference($sourceReference);

        // Check BDD
        $this->assertNotNull($bddOrder->getId());
        $this->assertCount(0,$bddOrder->getProducts());

        // Set produtcs
        $products[] = new Product();
        $products[] = new Product();

        $this->assertTrue($this->soapService->replaceOrderProducts($bddOrder->getSourceReference(), $products));

        // Check BDD
        $bddOrderWithFirstScv = $this->searchOrderBySourceReference($sourceReference);
        $this->assertEquals($bddOrder->getId(), $bddOrderWithFirstScv->getId());
        $this->assertCount(2, $bddOrderWithFirstScv->getProducts());
        $stepIds = array_map(
            function (OrderProduct $p){
                $this->assertNotNull($p->getId());
                return $p->getId();
            },
            $bddOrderWithFirstScv->getProducts()->toArray()
        );

        // Replace SCV
        $products2[] = new Product();
        $this->assertTrue($this->soapService->replaceOrderProducts($bddOrder->getSourceReference(), $products2));

        // Check BDD
        $bddOrderUpdated = $this->searchOrderBySourceReference($sourceReference);

        $this->assertEquals($bddOrder->getId(), $bddOrderUpdated->getId());
        $this->assertCount(1, $bddOrderUpdated->getProducts());
        $stepIds2 = array_map(
            function (OrderProduct $p){
                $this->assertNotNull($p->getId());
                return $p->getId();
            },
            $bddOrderUpdated->getProducts()->toArray()
        );

        $this->assertFalse(in_array($stepIds2[0], $stepIds));

    }


    private function createOrder($sourceReference)
    {
        $order = new Orders();
        $order->setSourceReference($sourceReference);
        $order->setCreatedBy(self::class);

        return $order;
    }

    /**
     * @param $order
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function persistOrder($order)
    {
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    /**
     * @param $sourceReference
     * @return Orders|null|object
     */
    private function searchOrderBySourceReference($sourceReference)
    {
        $orderRepository = $this->entityManager->getRepository(Orders::class);
        return $orderRepository->findOneBy(["sourceReference" => $sourceReference]);
    }

}