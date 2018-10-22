<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 10/04/2018
 * Time: 11:46
 */

namespace App\Service;

use App\Entity\OrderProduct;
use App\Entity\Orders;
use App\Entity\SupplyChainVisibility;
use App\Repository\OrdersRepository;


class OrderSoapService extends AbstractSoapService
{
    /**
     * @param string $orderCode
     * @param App\Service\SoapComplexType\ScvStep[] | \App\Service\SoapComplexType\ScvStep[] $scv
     * @return bool|string
     * @throws \Doctrine\ORM\ORMException
     */
    public function replaceScv($orderCode, $scv)
    {
        /**
         * @var OrdersRepository $orderRepository
         */
        $orderRepository = $this->doctrine->getRepository(Orders::class);

        /**
         * @var Orders $order
         */
        $order = $orderRepository->findOneBy(["sourceReference" => $orderCode]);
        if(!$order){
            return "Order $orderCode does not exist";
        }

        $newScv = new SupplyChainVisibility();
        $newSteps = [];
        foreach ($scv as $step)
        {
            $scvStep = new \App\Entity\ScvStep();
            $step->date?$scvStep->setDoAt(\DateTime::createFromFormat('YmdHis', substr($step->date,0,14))):$scvStep->setDoAt(null);
            $step->label?$scvStep->setLabel($step->label):null;
            $step->statusCode?$scvStep->setStatusCode($step->statusCode):null;
            $step->statusLabel?$scvStep->setStatusLabel($step->statusLabel):null;
            $newSteps[] = $scvStep;
        }
        $newScv->setSteps($newSteps);
        $orderRepository->replaceScv($order, $newScv);

        $this->doctrine->getEntityManager()->persist($order);
        $this->doctrine->getEntityManager()->flush();

        return true;
    }


    /**
     * @param string $orderCode
     * @param \App\Service\SoapComplexType\Product[]  $products
     * @return bool;
     * @throws \Doctrine\ORM\ORMException
     */
    public function replaceOrderProducts($orderCode, array $products)
    {
        /**
         * @var OrdersRepository $orderRepository
         */
        $orderRepository = $this->doctrine->getRepository(Orders::class);

        /**
         * @var Orders $order
         */
        $order = $orderRepository->findOneBy(["sourceReference" => $orderCode]);
        if(!$order){
            return "Order $orderCode does not exist";
        }

        $newProductOrders = $this->serializeProductsToOrderProducts($products);
        if(!$newProductOrders){
            return "No Products detected for Order $orderCode";
        }

        $orderRepository->replaceProducts($order, $newProductOrders);

        $this->doctrine->getEntityManager()->persist($order);
        $this->doctrine->getEntityManager()->flush();

        return true;
    }

    /**
     * @param \App\Service\SoapComplexType\Product[] $products
     * @return null
     */
    private function serializeProductsToOrderProducts( array $products)
    {
        $orderProducts = [];

        if( ! count($products) > 0){
            return null;
        }

        foreach ($products as $product)
        {
            $orderProduct = new OrderProduct();

            $orderProduct->setCode($product->code);
            $orderProduct->setDesignation($product->designation);
            $orderProduct->setWeight($product->poids);
            $orderProduct->setHeight($product->hauteur);
            $orderProduct->setLength($product->longueur);
            $orderProduct->setWidth($product->largeur);
            $orderProduct->setSerialNumber($product->numeroDeSerie);
            $orderProduct->setFm0($product->fm0);
            $orderProduct->setFm1($product->fm1);
            $orderProduct->setFm2($product->fm2);
            $orderProduct->setQuantity($product->quantity);

            $orderProducts[] = $orderProduct;
        }
        return $orderProducts;
    }
}