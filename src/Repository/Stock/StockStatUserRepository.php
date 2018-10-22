<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 13/04/2018
 * Time: 11:38
 */

namespace App\Repository\Stock;

use Doctrine\Common\Persistence\ManagerRegistry;

class StockStatUserRepository extends AbstractMovementRepository
{
    const COLUMN_NB_MVT = 'nb_mvt';
    const COLUMN_BALANCE_PREPA = 'balance_prepa';
    const COLUMN_BALANCE_REGUL = 'balance_regul';
    const COLUMN_BALANCE_RECEP = 'balance_recep';
    const COLUMN_BALANCE_FUSION = 'balance_fusion';

    private $columns;
    private $groupBy;
    /**
     * @var BetweenDateCondition
     */
    private $betweenCondition;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry);

        $i = 0;
        $this->columns[] = new Column($i++, static::COLUMN_DEPOSIT_LABEL, new TextMultipleType('%[\w\s\-]+%'));
        $this->columns[] = new Column($i++, static::COLUMN_USERNAME, new TextMultipleType('%[\w\s\-]+%'));
        $this->columns[] = new Column($i++, static::COLUMN_NB_MVT, new TextType());
        $this->columns[] = new Column(
            $i++,
            static::COLUMN_DATE,
            new PeriodType('%(\d\d\/\d\d\/\d\d\d\d \d\d:\d\d)+%')
        );
        $this->columns[] = new Column($i++, static::COLUMN_BALANCE_RECEP, new CustomType());
        $this->columns[] = new Column($i++, static::COLUMN_BALANCE_FUSION, new CustomType());
        $this->columns[] = new Column($i++, static::COLUMN_BALANCE_REGUL, new CustomType());
        $this->columns[] = new Column($i, static::COLUMN_BALANCE_PREPA, new CustomType());

        $this->groupBy = " GROUP BY ".static::COLUMN_DEPOSIT_LABEL.", ".static::COLUMN_USERNAME;
    }

    public function setBetweenDateCondition($value)
    {
        preg_match_all('%(\d\d\/\d\d\/\d\d\d\d \d\d:\d\d)+%', $value, $dates);
        $this->betweenCondition = new BetweenDateCondition(static::COLUMN_DATE, $this, $dates[0][0], $dates[0][1]);
    }

    /**
     * @param $where
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getNumberOfRows($where)
    {
        $this->getGenericNumbersOfRows($where);
    }

    /**
     * @param Where $where
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getNumberOfFilteredRows($where)
    {
        $this->getGenericNumbersOfRows($where);
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
        $sql = self::prepareSelect()."
             ".self::prepareFrom()."
             $where
             ".$this->groupBy."
             ".self::prepareOderBy($orderBys)."
             ".self::prepareLimit($limit);

        $binds = array_merge(
            $this->getSelectValuesToBind(),
            $where->getValuesToBind()
        );
        return self::executeRawSql($sql, $binds);
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
        return "";
    }

    private function prepareSelect()
    {
        $condition = $this->betweenCondition;
        return "
            SELECT 
                ".static::COLUMN_DEPOSIT_LABEL.",
                ".static::COLUMN_USERNAME.", 
                null as ".static::COLUMN_DATE.",
                SUM(ABS(CAST(".static::COLUMN_ACTUAL_QUANTITY." as integer))) as ".static::COLUMN_NB_MVT. ",
                SUM(
                    CASE 
                        WHEN (
                            ".static::COLUMN_MOVEMENT_TYPE." like 'PREPA' 
                            AND  $condition ) THEN ABS(CAST(".static::COLUMN_ACTUAL_QUANTITY." as integer))
                         ELSE 0
                         END
                )as ".static::COLUMN_BALANCE_PREPA.",
                SUM(
                    CASE 
                        WHEN (
                        ".static::COLUMN_MOVEMENT_TYPE." like 'REGUL' 
                        AND $condition) THEN ABS(CAST(".static::COLUMN_ACTUAL_QUANTITY." as integer))
                         ELSE 0
                         END
                 )as ".static::COLUMN_BALANCE_REGUL.",
                SUM(
                    CASE 
                        WHEN (
                        ".static::COLUMN_MOVEMENT_TYPE." like 'RECEP' 
                        AND $condition) THEN ABS(CAST(".static::COLUMN_ACTUAL_QUANTITY." as integer))
                         ELSE 0
                         END
                 )as ".static::COLUMN_BALANCE_RECEP.",
                 SUM(
                    CASE 
                        WHEN (
                        ".static::COLUMN_MOVEMENT_TYPE." like 'FUSION' 
                        AND $condition) THEN ABS(CAST(".static::COLUMN_ACTUAL_QUANTITY." as integer))
                         ELSE 0
                         END
                 )as ".static::COLUMN_BALANCE_FUSION."      
            ";
    }



    /**
     * @return array
     */
    private function getSelectValuesToBind()
    {
        $values = $this->betweenCondition->getValues();
        return array_merge($values, $values, $values, $values);
    }

    /**
     * @param Where $where
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function getGenericNumbersOfRows(Where $where)
    {
        $nestedSqlQuery = self::prepareSelect()
             .self::prepareFrom()
             ."$where"
             .$this->groupBy;

        $binds = array_merge(
            $this->getSelectValuesToBind(),
            $where->getValuesToBind()
        );

        $sql = "SELECT COUNT(1) FROM (".$nestedSqlQuery.") as B";
        return self::executeCountRequest($sql, $binds);
    }
}
