<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 27/04/2018
 * Time: 10:36
 */

namespace App\Repository;

use App\Entity\Client;
use App\Entity\Orders;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use PhpParser\Node\Stmt\Switch_;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ClientRepository extends AbstractCascadeEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Client::class);
    }
    /**
     * @param Client $client
     * @param Orders[] | ArrayCollection $newOrders
     * @return string
     * @throws \Doctrine\ORM\ORMException
     */
    public function updateClientWithOrders(Client $client, $newOrders)
    {
        $em = $this->getEntityManager();
        $orders = $client->getOrders();
        $this->upsertChildCollection($orders, $newOrders);
        $client->setUpdatedBy('updateClientWithOrders');

        try{
            $em->persist($client);
            $em->flush();
            return true;
        }
        catch (\Exception $e) {
            die($e);
        }
    }

    public function getAgencies() {
        return $this->findBy(array(), array('id' => 'ASC'));
        $qb = $this->createQueryBuilder('cl')
            ->select('cl, a')
            ->leftJoin('App:Agency', 'ag', 'WITH', 'ag.client = cl.id')
            //->where('a.client = :client_id')
            //->setParameter('client_id', $client_id)
            ->getQuery();

        if (sizeof($qb->execute())) {
            return $qb->execute();
        } else {
            return null;
        }
    }

    /**
     * @param  $filter
     * @param array $order
     * @param $limit
     * @param $offset
     * @return Client[]
     */
    public function search($filter, array $order, $limit=10, $offset=0)
    {
        $queryBuilder =  $this->createQueryBuilder('cl')
            ->leftJoin('cl.checkpoint', 'ch')
            ->leftJoin('ch.address', 'add')
            ->andWhere("lower(cl.sourceReference) LIKE :searchTerm
                OR lower(add.city) LIKE :searchTerm
                OR lower(add.recipient1) LIKE :searchTerm
            ");

        foreach ($order as $orderBy){
            foreach ($orderBy as $sort => $direction) {
                switch ($sort) {
                    case "recipient1":
                    case "recipient2":
                    case "recipient3":
                    case "street1":
                    case "street2":
                    case "street3":
                    case "postcode":
                    case "city":
                    case "country":
                        $queryBuilder->addOrderBy("add.".$sort, $direction);
                        break;
                    case "reference":
                        $queryBuilder->addOrderBy("cl.sourceReference", $direction);
                        break;
                    case "siret":
                        $queryBuilder->addOrderBy("cl.siret", $direction);
                        break;
                    default:
                        break;
                }
            }
        }

        return $queryBuilder
            ->setParameter('searchTerm',strtolower('%'.$filter.'%'))
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->execute();
    }

    public function findByUser($userId)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c, u')
            ->leftJoin('c.users', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
        ;

        return $qb->execute();
    }
}