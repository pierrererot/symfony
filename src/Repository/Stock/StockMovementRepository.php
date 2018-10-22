<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 13/04/2018
 * Time: 11:38
 */

namespace App\Repository\Stock;

use App\Entity\Movement;
use DataTable\ResponseData;
use DataTable\RepositoryInterface;
use DataTable\DataTableService;
use DataTable\src\Column\Column;
use DataTable\src\Column\Type\PeriodType;
use DataTable\src\Column\Type\TextMultipleType;
use DataTable\src\Column\Type\TextType;
use DataTable\src\Conditions\EqualCondition;
use DataTable\src\Limit;
use DataTable\src\OrderBy;
use DataTable\src\Where;
use Doctrine\Common\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Self_;

class StockMovementRepository extends AbstractMovementRepository
{
    private $columns;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry);

        $format = function ($value) {
            $date = new \DateTime($value);

            return $date->format('d/m/Y H:i');
        };
        $i=0;
        $this->columns[] = new Column($i++, static::COLUMN_DEPOSIT_LABEL, new TextMultipleType('%[\w\s\-]+%',EqualCondition::class));
        $this->columns[] = new Column($i++, static::COLUMN_CLIENT_CODE, new TextMultipleType('%[\w\s\-]+%', EqualCondition::class));
        $this->columns[] = new Column($i++, static::COLUMN_CLIENT_CODE, new TextMultipleType('%[\w\s\-]+%', EqualCondition::class));
        $this->columns[] = new Column($i++, static::COLUMN_INTERNAL_ORDER_REFERENCE, new TextType());
        $this->columns[] = new Column($i++, static::COLUMN_EXTERNAL_ORDER_REFERENCE, new TextType());
        $this->columns[] = new Column($i++, static::COLUMN_ACTUAL_QUANTITY, new TextType());
        $this->columns[] = new Column(
            $i++,
            static::COLUMN_DATE,
            new PeriodType('%(\d\d\/\d\d\/\d\d\d\d \d\d:\d\d)+%'),
            $format
        );
        $this->columns[] = new Column($i++, static::COLUMN_MOVEMENT_TYPE, new TextMultipleType('%[\w\s\-]+%', EqualCondition::class));
        $this->columns[] = new Column($i++, static::COLUMN_MOVEMENT, new TextMultipleType('%[\w\s\-]+%',EqualCondition::class));
        $this->columns[] = new Column($i++, static::COLUMN_CONDITION, new TextMultipleType('%[\w\s\-]+%',EqualCondition::class));
        $this->columns[] = new Column($i++, static::COLUMN_USERNAME, new TextMultipleType('%[\w\s\-]+%'));
        $this->columns[] = new Column($i, static::COLUMN_ITEMS, new TextType());
    }

    /**
     * @param $where
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getNumberOfRows($where)
    {
        return $this->getGenericNumbersOfRows($where);
    }

    /**
     * @param Where $where
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getNumberOfFilteredRows($where)
    {
        return $this->getGenericNumbersOfRows($where);
    }

    /**
     * @param Where $where
     * @param Limit $limit
     * @param OrderBy[] $orderBys
     * @return ResponseData[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getAllByParams($where, $limit, $orderBys)
    {
        $sql = "SELECT * "
            .self::prepareFrom()
            ." $where "
            .self::prepareOderBy($orderBys)
            .self::prepareLimit($limit);

        return self::executeRawSql($sql, $where->getValuesToBind());
    }

    /**
     * @return Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return string
     */
    public function getBindStrToDate()
    {
        return " TO_TIMESTAMP( ".DataTableService::BIND_OPERATOR." , 'DD/MM/YYYY HH24:MI')";
    }

    /**
     * @param Movement $entity
     * @throws \Doctrine\ORM\ORMException
     */
    public function replace(Movement $entity){

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @param array Movement[] $entities
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteEntities( array $entities){

        foreach ($entities as $entity){
            $this->getEntityManager()->remove($entity);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @param Where $where
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function getGenericNumbersOfRows(Where $where)
    {
        $sql = "SELECT  count(1) ".self::prepareFrom()." $where";
        return self::executeCountRequest($sql, $where->getValuesToBind());
    }
}
