<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 12/04/2018
 * Time: 09:38
 */

namespace App\Repository\Stock;

use App\Entity\Movement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

abstract class AbstractMovementRepository extends ServiceEntityRepository
{
    const TABLE_NAME = "movement";

    const COLUMN_DEPOSIT_CODE = 'id';
    const COLUMN_DEPOSIT_LABEL = 'label';
    const COLUMN_CLIENT_CODE = 'source_reference';
    const COLUMN_CLIENT_LABEL = 'source_reference';
    const COLUMN_INTERNAL_ORDER_REFERENCE = 'internal_order_reference';
    const COLUMN_EXTERNAL_ORDER_REFERENCE = 'external_order_reference';
    const COLUMN_DATE = 'moved_at';
    const COLUMN_MOVEMENT = 'movement';
    const COLUMN_MOVEMENT_TYPE = 'movement_type';
    const COLUMN_TRAVEL_TYPE = 'travel_type';
    const COLUMN_ACTUAL_QUANTITY = 'actual_quantity';
    const COLUMN_ITEMS = 'items';
    const COLUMN_CONDITION = 'condition';
    const COLUMN_DEAL = 'deal';
    const COLUMN_USERNAME = 'username';
    const COLUMN_CREATED_AT = 'created_at';
    const COLUMN_NB_GOODS = 'goods';

    const COLUMN_NB = 'ref1';
    const COLUMN_COLOR = 'ref2';
    const COLUMN_DF = 'df';
    const COLUMN_BILL_TYPE = 'bill_type';
    const COLUMN_UNITARY_PRICE = 'unitary_price';

    /**
     * @var ManagerRegistry
     */
    protected $connexion;

    /**
     * @var Clause[]
     */
    protected $defaultClauses;

    /**
     * AbstractMovementRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movement::class);
        $this->connexion = $this->getEntityManager()->getConnection();

        $clause = new Clause();
        $clause->addCondition(new NotNullCondition(static::COLUMN_DATE));
        $this->defaultClauses = [$clause];
    }

    /**
     * @return array|Clause[]
     */
    public function getDefaultClauses()
    {
        return $this->defaultClauses;
    }

    /**
     * @param $fieldName
     * @param int $limit
     * @return array
     */
    public function getDistinctValueOfOneColumn($fieldName, $limit = 300)
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

    /**
     * @param Clause $clause
     */
    public function addDefaultClause(Clause $clause){
       $this->defaultClauses[] = $clause;
    }

    /**
     * @param Limit $limit
     * @return string
     */
    protected function prepareLimit($limit)
    {
        if (!is_null($limit)) {
            return " LIMIT ".$limit->getLength()." OFFSET ".$limit->getStart();
        }

        return "";
    }

    /**
     * @param OrderBy[] $orderBys
     * @return string
     */
    protected function prepareOderBy($orderBys)
    {
        $result = ' ';
        if (!empty($orderBys)) {
            $result .= "ORDER BY B.";
            $result .= implode(",", $orderBys);
        }
        return $result;
    }

    /**
     * @param string $sql
     * @param array $binds
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function executeCountRequest(string $sql, array $binds)
    {
        $result = $this->connexion->prepare($sql);
        $result->execute($binds);

        if ($result) {
            $all = $result->fetchAll(\PDO::FETCH_COLUMN, 0);
            return $all[0];
        }
        return 0;
    }

    /**
     * @param string $sql
     * @param array $binds
     * @return ResponseData[]
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function executeRawSql(string $sql, array $binds )
    {
        $result = $this->connexion->prepare($sql);
        $result->execute($binds);

//        var_dump($where->getValuesToBind());
//        var_dump($sql);

        $response = [];

        while ($row = $result->fetch()) {
            $data = new ResponseData();

            foreach ($this->getColumns() as $column) {
                $value = $row[$column->getName()];

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

    public function prepareFrom()
    {
        return " FROM ".static::TABLE_NAME." as A JOIN agency B ON A.agency_id = B.id JOIN CLIENT C on A.client_id = C.id";
    }
}
