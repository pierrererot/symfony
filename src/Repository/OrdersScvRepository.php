<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 06/09/2018
 * Time: 14:51
 */

namespace App\Repository;

use App\Entity\SupplyChainVisibility;
use Symfony\Bridge\Doctrine\RegistryInterface;

class OrdersScvRepository extends AbstractCascadeEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SupplyChainVisibility::class);
    }
}