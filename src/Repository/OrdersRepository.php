<?php

namespace App\Repository;

use App\Entity\OrderProduct;
use App\Entity\Orders;
use App\Entity\SupplyChainVisibility;
use Symfony\Bridge\Doctrine\RegistryInterface;

class OrdersRepository extends AbstractCascadeEntityRepository implements UpdateCascadeInterface
{
    private $defaultClauses;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Orders::class);
        $this->defaultClauses = [];
    }

    public function getDistinctValue($fieldName)
    {
        $result = $this->createQueryBuilder('o')
            ->select('DISTINCT o.'.$fieldName)
            ->getQuery()
            ->getArrayResult()
        ;

        return array_map(
            function ($row){
                return $row[key($row)];
            },
            $result
        );
    }

    public function getDistinctValueFromBenefit($fieldName)
    {
        $result = $this->createQueryBuilder('a')
            ->select('DISTINCT b.'.$fieldName)
            ->join('a.benefit', 'b', 'WITH', 'b.id = a.benefit')
            ->getQuery()
            ->getArrayResult()
        ;

        return array_map(
            function ($row){
                return $row[key($row)];
            },
            $result
        );
    }

    public function addDefaultClause(Clause $clause)
    {
        $this->defaultClauses[] = $clause;
    }

    public function getDefaultClauses()
    {
        return $this->defaultClauses;
    }

    public function findAllById()
    {
        return $this->findBy(array(), array('id' => 'ASC'));
    }

    public function listOrders()
    {
        $qb = $this->createQueryBuilder('o')
            ->select()
            ->orderBy('o.id', 'ASC')
            ->getQuery();
        return $qb->execute();
    }

    /**
     * @param $oldEntity Orders
     * @param $newEntity Orders
     * @throws \Doctrine\ORM\ORMException
     */
    public function update(&$oldEntity, $newEntity)
    {
        //orders
        $oldEntity
            ->setDeal($newEntity->getDeal())
            ->setStatus($newEntity->getStatus())
            ->setAgency($newEntity->getAgency())
            ->setActivity($newEntity->getActivity())
            ->setBillDate($newEntity->getBillDate())
            ->setBillingCompany($newEntity->getBillingCompany())
            ->setBillNumber($newEntity->getBillNumber())
            ->setBillPreTaxAmount($newEntity->getBillPreTaxAmount())
            ->setClientOrderClient($newEntity->getClientOrderClient())
            ->setClientOrderReference($newEntity->getClientOrderReference())
            ->setContractor($newEntity->getContractor())
            ->setExploitation($newEntity->getExploitation())
            ->setFinalCheckpoint($newEntity->getFinalCheckpoint())
            ->setFinalArrivingAt($newEntity->getFinalLeavingAt())
            ->setFinalLeavingAt($newEntity->getFinalArrivingAt())
            ->setFinalDate($newEntity->getFinalDate())
            ->setFinalCity($newEntity->getFinalCity())
            ->setFinalRegion($newEntity->getFinalRegion())
            ->setFinalSeqc($newEntity->getFinalSeqc())
            ->setInitialCheckpoint($newEntity->getInitialCheckpoint())
            ->setInitialArrivingAt($newEntity->getInitialArrivingAt())
            ->setInitialLeavingAt($newEntity->getInitialLeavingAt())
            ->setInitialDate($newEntity->getInitialDate())
            ->setInitialCity($newEntity->getInitialCity())
            ->setInitialRegion($newEntity->getInitialRegion())
            ->setInitialSeqc($newEntity->getInitialSeqc())
            ->setInputOperator($newEntity->getInputOperator())
            ->setFolderReference($newEntity->getFolderReference())
            ->setModelInternationalConsigment($newEntity->getModelInternationalConsigment())
            ->setNPrincipal($newEntity->getNPrincipal())
            ->setPrincipal($newEntity->getPrincipal())
            ->setContractor($newEntity->getContractor())
            ->setQuotationReference($newEntity->getQuotationReference())
            ->setRecep($newEntity->getRecep())
            ->setRefot($newEntity->getRefot())
            ->setRdvAt($newEntity->getRdvAt())
            ->setUpdatedBy(self::class)
        ;

        // Phases - Delete
        $newStepsList = $this->getReferencesFromCascadeEntityCollection($newEntity->getPhases());
        foreach ($oldEntity->getPhases() as $oldPhase) {
            if(!in_array($oldPhase->getSourceReference(),$newStepsList)){
                $this->getEntityManager()->remove($oldPhase);
            }
            $this->getEntityManager()->flush();
        }

        // Steps - Upsert
        foreach ($newEntity->getPhases() as $newPhase) {
            $newPhase->setOrders($oldEntity);
        }
        $phases = $oldEntity->getPhases();
        $this->upsertChildCollection($phases, $newEntity->getPhases());
        $this->getEntityManager()->persist($oldEntity);
    }

    /**
     * @return Column[]
     */
    public function getColumns()
    {
        $columns = [];
        $i = 0;

        // VISIBLE
        // Id 0
        $columns[] = new Column($i++, 'a.id', new TextType());

        // Ref Commande 1
        $columns[] = new Column($i++, 'a.client_order_reference', new TextType());

        // Ref Client 2
        $columns[] = new Column($i++, 'a.client_order_client', new TextType());

        // Type de Mouvement 3
        $columns[] = new Column($i++, 'd.label', new TextType());

        // Origine 4-5-6
        $columns[] = new Column($i++, 'g.ens1', new TextType());
        $columns[] = new Column($i++, 'g.postcode', new TextType());
        $columns[] = new Column($i++, 'g.city', new TextType());

        // Destination 7-8-9
        $columns[] = new Column($i++, 'i.ens1', new TextType());
        $columns[] = new Column($i++, 'i.postcode', new TextType() );
        $columns[] = new Column($i++, 'i.city', new TextType() );

        // Statut 10
        $columns[] = new Column($i++, 'b.label', new TextType() );

        // Date Opération 11
        $columns[] = new Column($i++, 'a.operation_date', new PeriodType('%(\d\d\/\d\d\/\d\d\d\d \d\d:\d\d)+%'));

        // POUR LES FILTRES
        // Reference company 12
        $columns[] = new Column($i++, 'a.source_reference', new TextType() );

        // Affaire 13
        $columns[] = new Column($i++, 'a.deal', new TextType() );

        // Agence (TIPF => exploitation) 14
        $columns[] = new Column($i++, 'e.label_extranet', new TextType() );

        // RDV prit (OUI/NON) 15
        $columns[] = new Column($i++, 'a.rdv', new IsNullOrNotNullType() );

        // Nature opération (PRST FM0) 16
        $columns[] = new Column($i++, 'd.fm0_apt', new TextType() );

        // CP operation ( like '44%' ) 17
        $columns[] = new Column($i++, 'k.postcode', new TextType() );

        // Client 18
        $columns[] = new Column($i++, 'c.source_reference', new TextType() );

        return $columns;
    }

    /**
     * @param $where Where
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getNumberOfRows($where)
    {
        return $this->getGenericNumberOfRows($where);
    }

    /**
     * @param Where $where
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getNumberOfFilteredRows($where)
    {
        return $this->getGenericNumberOfRows($where);
    }

    /**
     * @param Where $where
     * @param Limit $limit
     * @param OrderBy[] $orderBys
     * @return ResponseData[]|null
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getAllByParams($where, $limit, $orderBys)
    {
        $sql =  self::prepareSelect()
                .self::prepareFrom()
                .$where
                .self::prepareOrderBy($orderBys)
                .self::prepareLimit($limit)
        ;
        $result = $this->getEntityManager()->getConnection()->prepare($sql);
        $result->execute($where->getValuesToBind());

        $response = [];
        while ($row = $result->fetch()) {
            $data = new ResponseData();

            foreach (self::getColumns() as $column) {

                switch ($column->getName()){
                    case 'a.source_reference':
                        $value = $row['reference_altead'];
                        break;
                    case 'd.label':
                        $value = $row['type_mvt'];
                        break;
                    case 'g.ens1':
                        $value = $row['initial_recipient1'];
                        break;
                    case 'g.postcode':
                        $value = $row['initial_post_code'];
                        break;
                    case 'g.city':
                        $value = $row['initial_city'];
                        break;
                    case 'i.ens1':
                        $value = $row['final_recipient1'];
                        break;
                    case 'i.postcode':
                        $value = $row['final_post_code'];
                        break;
                    case 'i.city':
                        $value = $row['final_city'];
                        break;
                    case 'k.postcode':
                        $value = $row['operation_post_code'];
                        break;
                    case 'k.city':
                        $value = $row['operation_city'];
                        break;
                    default:
                        $value = $row[substr($column->getName(),2)];
                        break;
                }

                if ($column->getReturnFormatter()) {
                    $formatter = $column->getReturnFormatter();
                    $value = $formatter($value);
                }
                $data->addValue($value);
            }
            $response[] = $data;
        }
        return $response;

    }


    /**
     * @param string
     * @return string
     */
    public function getBindStrToDate()
    {
        return "";
    }

    /**
     * @param $where Where
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    private function getGenericNumberOfRows($where)
    {
        $sql =  " SELECT count(1)
            ".$this->prepareFrom()."
            $where";

        $result = $this->getEntityManager()->getConnection()->prepare($sql);
        $result->execute($where->getValuesToBind());

        if ($result) {
            $all = $result->fetchAll(\PDO::FETCH_COLUMN, 0);
            return $all[0];
        }
        return 0;
    }

    /**
     * @return string
     */
    private function prepareSelect()
    {
        return " SELECT 
                a.id,
                a.client_order_reference,
                a.client_order_client,
                d.label as type_mvt,
                g.recipient1 as initial_recipient1,
                g.postcode as initial_post_code,
                g.city as initial_city,
                i.recipient1 as final_recipient1,
                i.postcode as final_post_code,
                i.city as final_city,
                b.label,
                a.operation_date,
                a.source_reference as reference_altead,
                a.deal,
                e.label_extranet,
                CASE 
                    WHEN a.rdv is null THEN false
                    ELSE true
                END as rdv,
                d.fm0_apt,
                k.postcode as operation_post_code,
                k.city as operation_city,
                c.source_reference
        ";
    }

    /**
     * @return string
     */
    private function prepareFrom()
    {
        return  " 
            FROM orders a
            LEFT JOIN orders_status b ON a.status_id = b.id
            LEFT JOIN client c ON a.client_id = c.id
            LEFT JOIN referentiel_benefit d ON a.benefit_id = d.id
            LEFT JOIN referentiel_exploitation e ON a.exploitation_id = e.id
            LEFT JOIN checkpoint f ON a.initial_checkpoint_id = f.id
                LEFT JOIN address g ON f.address_id = g.id
            LEFT JOIN checkpoint h ON a.final_checkpoint_id = h.id
                LEFT JOIN address i ON h.address_id = i.id
            LEFT JOIN checkpoint j ON a.operation_checkpoint_id = j.id
                LEFT JOIN address k ON j.address_id = k.id
            LEFT JOIN agency l ON a.agency_id = l.id 
        ";
    }

    /**
     * @param $orderBys OrderBy[]
     * @return string
     */
    private function prepareOrderBy($orderBys)
    {
        $result = ' ';
        if (!empty($orderBys)) {
            $result .= "ORDER BY ";
            $result .= implode(",", $orderBys);
        }
        return $result;
    }

    /**
     * @param $limit Limit
     * @return string
     */
    private function prepareLimit($limit)
    {
        if (!is_null($limit)) {
            return " LIMIT ".$limit->getLength()." OFFSET ".$limit->getStart();
        }
        return "";
    }

    /**
     * @param Orders $order
     * @param $products OrderProduct[]
     * @throws \Doctrine\ORM\ORMException
     */
    public function replaceProducts(Orders $order, array $products)
    {
        foreach ($order->getProducts() as $oldProduct)
        {
            $this->getEntityManager()->remove($oldProduct);
        }
        $order->setProducts($products);
    }

    /**
     * @param Orders $order
     * @param SupplyChainVisibility $scv
     * @throws \Doctrine\ORM\ORMException
     */
    public function replaceScv(Orders $order, SupplyChainVisibility $scv)
    {
        $oldScv = $order->getSupplyChainVisibility();
        foreach ($oldScv->getSteps() as $oldStep)
        {
            $this->getEntityManager()->remove($oldStep);
        }
        $oldScv->setSteps($scv->getSteps());
    }
}
