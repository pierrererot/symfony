<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 13/04/2018
 * Time: 11:38
 */

namespace App\Repository\Stock;

use Doctrine\Common\Persistence\ManagerRegistry;

class StockStatusRepository extends AbstractMovementRepository
{
    const COLUMN_IN_NETWORK_AT = 'in_network_at';
    const COLUMN_LAST_MOVEMENT_AT = 'last_movement_at';
    const COLUMN_LAST_MOVEMENT_QTY = 'last_movement_quantity';
    const COLUMN_BALANCE_PREPA = 'balance_prepa';
    const COLUMN_BALANCE_REGUL = 'balance_regul';
    const COLUMN_BALANCE_RECEP = 'balance_recep';
    const COLUMN_BALANCE_FUSION = 'balance_fusion';
    const COLUMN_WAREHOUSE_DURATION = 'warehouse_duration';
    const COLUMN_FIRST_ITEM = 'first_item';
    const COLUMN_FIRST_EXTERNAL_REFERENCE = 'first_external_reference';
    const COLUMN_CALC_NB_J_NET = "nb_j_net";
    const COLUMN_CALC_NB_J_FACTU = "nb_j_fact";
    const COLUMN_CALC_AMOUNT = "amount";

    const BILL_TYPE_J = '2';
    const BILL_TYPE_M = '1';

    private $date;

    private $columns;

    private $countSelectDateToBind;
    private $countGroupByDateToBind;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry);

        $format = function ($value) {
            $date = new \DateTime($value);

            return $date->format('d/m/Y H:i');
        };
        $i = 0;
        $this->columns[] = new Column($i++, static::COLUMN_DEPOSIT_LABEL,
            new TextMultipleType('%[\w\s\-]+%', EqualCondition::class));
        $this->columns[] = new Column($i++, static::COLUMN_CLIENT_CODE,
            new TextMultipleType('%[\w\s\-]+%', EqualCondition::class));
        $this->columns[] = new Column($i++, static::COLUMN_CLIENT_LABEL,
            new TextMultipleType('%[\w\s\-]+%', EqualCondition::class));
        $this->columns[] = new Column($i++, static::COLUMN_DEAL, new TextType());
        $this->columns[] = new Column($i++, static::COLUMN_ACTUAL_QUANTITY, new TextType());
        $this->columns[] = new Column($i++, static::COLUMN_DATE, new CustomType());
        $this->columns[] = new Column($i++, static::COLUMN_IN_NETWORK_AT, new CustomType(), $format);
        $this->columns[] = new Column($i++, static::COLUMN_LAST_MOVEMENT_AT, new CustomType(), $format);
        $this->columns[] = new Column($i++, static::COLUMN_BALANCE_RECEP, new CustomType());
        $this->columns[] = new Column($i++, static::COLUMN_BALANCE_FUSION, new CustomType());
        $this->columns[] = new Column($i++, static::COLUMN_BALANCE_REGUL, new CustomType());
        $this->columns[] = new Column($i++, static::COLUMN_BALANCE_PREPA, new CustomType());
        $this->columns[] = new Column($i++, static::COLUMN_WAREHOUSE_DURATION, new CustomType());
        $this->columns[] = new Column($i++, static::COLUMN_MOVEMENT, new TextMultipleType('%[\w\s\-]+%'));
        $this->columns[] = new Column($i++, static::COLUMN_FIRST_ITEM, new CustomType());
        $this->columns[] = new Column($i++, static::COLUMN_ITEMS, new TextMultipleType('%[\w\s\-]+%'));
        $this->columns[] = new Column($i++, static::COLUMN_FIRST_EXTERNAL_REFERENCE, new CustomType());
        $this->columns[] = new Column($i++, static::COLUMN_EXTERNAL_ORDER_REFERENCE,
            new TextMultipleType('%[\w\s\-]+%', EqualCondition::class));
        $this->columns[] = new Column($i++, static::COLUMN_NB, new CustomType());
        $this->columns[] = new Column($i++, static::COLUMN_COLOR, new CustomType());
        $this->columns[] = new Column($i++, static::COLUMN_DF, new CustomType());
        $this->columns[] = new Column($i++, static::COLUMN_BILL_TYPE, new CustomType());
        $this->columns[] = new Column($i++, static::COLUMN_UNITARY_PRICE, new CustomType());
        $this->columns[] = new Column($i++, static::COLUMN_LAST_MOVEMENT_QTY, new CustomType());
        $this->columns[] = new Column($i++, static::COLUMN_CALC_NB_J_NET, new CustomType());
        $this->columns[] = new Column($i++, static::COLUMN_CALC_NB_J_FACTU, new CustomType());
        $this->columns[] = new Column($i, static::COLUMN_CALC_AMOUNT, new CustomType());
    }

    /***
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = addslashes($date);
    }

    /**
     * @param $where
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getNumberOfRows($where)
    {
        return self::getGenericNumbersOfRows($where);
    }

    /**
     * @param Where $where
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getNumberOfFilteredRows($where)
    {
        return self::getGenericNumbersOfRows($where);
    }

    /**
     * @return string
     */
    public function prepareSelect()
    {
        $counter = $this->countSelectDateToBind;

        $sql = "SELECT c.".static::COLUMN_CLIENT_CODE.", c.".static::COLUMN_CLIENT_LABEL.", b.".static::COLUMN_DEPOSIT_LABEL.", ".static::COLUMN_DEAL.",
         CAST( ".self::prepareSelectActualQuantity($counter)."  AS INTEGER ) as ".static::COLUMN_ACTUAL_QUANTITY.",
        null as ".static::COLUMN_DATE.",
        min(".static::COLUMN_DATE.") as ".static::COLUMN_IN_NETWORK_AT.",
        max(".static::COLUMN_DATE.") as ".static::COLUMN_LAST_MOVEMENT_AT.",
        SUM(
            CASE
                WHEN ('PREPA' = ".static::COLUMN_MOVEMENT_TYPE." ) THEN ".static::COLUMN_ACTUAL_QUANTITY."  
                ELSE 0
            END
        )as ".static::COLUMN_LAST_MOVEMENT_QTY.",
        SUM(
            CASE 
                WHEN (".static::COLUMN_MOVEMENT_TYPE." like 'PREPA' AND  extract(epoch from ".static::COLUMN_DATE." - ".self::getBindStrToDate().") < 0 ) THEN ".static::COLUMN_ACTUAL_QUANTITY."
                 ELSE 0
            END
         )as ".static::COLUMN_BALANCE_PREPA.",
        SUM(
            CASE 
                WHEN (".static::COLUMN_MOVEMENT_TYPE." like 'REGUL' AND extract(epoch from ".static::COLUMN_DATE." - ".self::getBindStrToDate().")  < 0 ) THEN ".static::COLUMN_ACTUAL_QUANTITY."
                 ELSE 0
            END
         )as ".static::COLUMN_BALANCE_REGUL.",
        SUM(
            CASE 
                WHEN (".static::COLUMN_MOVEMENT_TYPE." like 'RECEP' AND extract(epoch from ".static::COLUMN_DATE." - ".self::getBindStrToDate().")  < 0 ) THEN ".static::COLUMN_ACTUAL_QUANTITY."
                 ELSE 0
            END
        )as ".static::COLUMN_BALANCE_RECEP.",
        SUM(
            CASE 
                WHEN (".static::COLUMN_MOVEMENT_TYPE." like 'FUSION' AND extract(epoch from ".static::COLUMN_DATE." - ".self::getBindStrToDate().")  < 0 ) THEN ".static::COLUMN_ACTUAL_QUANTITY."
                ELSE 0
            END
        )as ".static::COLUMN_BALANCE_FUSION.",
        ".self::prepareSelectWarehouseDuration($counter)." as ".static::COLUMN_WAREHOUSE_DURATION.",
        null as ".static::COLUMN_MOVEMENT.",
        MAX(".static::COLUMN_ITEMS.") as ".static::COLUMN_ITEMS.",
        split_part( MAX(".static::COLUMN_ITEMS."), ',', 1) as ".static::COLUMN_FIRST_ITEM.",       
        STRING_AGG(".static::COLUMN_EXTERNAL_ORDER_REFERENCE." , ',') as ".static::COLUMN_EXTERNAL_ORDER_REFERENCE.",
        SPLIT_PART( STRING_AGG(".static::COLUMN_EXTERNAL_ORDER_REFERENCE." , ','), ',', 1) as ".static::COLUMN_FIRST_EXTERNAL_REFERENCE.",
        SUM(".static::COLUMN_NB."::integer) as ".static::COLUMN_NB.",
        SUM(".static::COLUMN_COLOR."::integer) as  ".static::COLUMN_COLOR.",
        MAX(".static::COLUMN_DF.") as  ".static::COLUMN_DF.",
        CASE 
            WHEN MAX(bill_type) = '".self::BILL_TYPE_M."' THEN 'M'
			WHEN MAX(bill_type) = '".self::BILL_TYPE_J."' THEN 'J'
			ELSE ''
		END ".static::COLUMN_BILL_TYPE.",
        MAX(".static::COLUMN_UNITARY_PRICE."::float) as  ".static::COLUMN_UNITARY_PRICE.",
        ".self::prepareSelectCalcNbDayNet($counter)." as  ".static::COLUMN_CALC_NB_J_NET.",
        ".self::prepareSelectCalcNbDayFactu($counter)." as  ".static::COLUMN_CALC_NB_J_FACTU.",
        ".self::prepareSelectCalcAmount($counter)." as  ".static::COLUMN_CALC_AMOUNT."
      ";

        $this->countSelectDateToBind = $counter +4;
        return $sql;
    }

    /**
     * @return string
     */
    public function getBindStrToDate()
    {
        return "";
    }

    /**
     * @return string
     */
    private function prepareGroupBy()
    {
        $counter = $this->countGroupByDateToBind;
        $start  = \DateTime::createFromFormat('d/m/Y H:i', $this->date)->modify('first day of this month');
        $end    = \DateTime::createFromFormat('d/m/Y H:i', $this->date);

        $sql =  " 
            GROUP BY b.".static::COLUMN_DEPOSIT_LABEL
            .", c.".static::COLUMN_CLIENT_LABEL
            .", c.".static::COLUMN_CLIENT_CODE
            .", ".static::COLUMN_DEAL
            ." HAVING  ".self::prepareSelectActualQuantity($counter)."  > 0 "
            ."OR ("
            ."MAX(".static::COLUMN_ACTUAL_QUANTITY.") = 0 "
            ."AND MAX(".static::COLUMN_DATE.") ".
            "BETWEEN '".$start->format("Y-m-d 00:00")."' "
            ."AND '".$end->format("Y-m-d H:i")."' ) "
            ."AND ".self::prepareSelectWarehouseDuration($counter)." >= 0
        ";

        $this->countGroupByDateToBind = $counter;
        return $sql;
    }

    /**
     * @return array
     */
    private function getSelectValuesToBind()
    {
        return array_fill(0, $this->countSelectDateToBind, $this->date);
    }
    /**
     * @return array
     */
    private function getGroupByValuesToBind()
    {
        return array_fill(0, $this->countGroupByDateToBind, $this->date);
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
        $this->countSelectDateToBind = 0;
        $this->countGroupByDateToBind = 0;

        $sql = self::prepareSelect()."
             ".self::prepareFrom()."
             $where
             ".self::prepareGroupBy()."
             ".self::prepareOderBy($orderBys)."
             ".self::prepareLimit($limit);

        $binds = array_merge(
            $this->getSelectValuesToBind(),
            $where->getValuesToBind(),
            $this->getGroupByValuesToBind()
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
     * @param Where $where
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function getGenericNumbersOfRows(Where $where)
    {
        $this->countSelectDateToBind = 0;
        $this->countGroupByDateToBind = 0;

        $nestedSqlQuery = self::prepareSelect()."
             ".self::prepareFrom()."
             $where
             ".self::prepareGroupBy();

        $sql = "SELECT count(1) FROM (".$nestedSqlQuery.") as B";

        $binds = array_merge(
            $this->getSelectValuesToBind(),
            $where->getValuesToBind(),
            $this->getGroupByValuesToBind()
        );

        return self::executeCountRequest($sql, $binds);
    }

    private function prepareSelectWarehouseDuration(&$counter)
    {
        $counter = $counter + 3;
        return " abs(
            trunc(
                extract(
                    epoch from 
                    min(".static::COLUMN_DATE.")
                    -
                    ( 
                        CASE 
                            WHEN ('RECEP' = ( SELECT B.".static::COLUMN_MOVEMENT_TYPE." FROM ".static::TABLE_NAME." as B WHERE max(A.".static::COLUMN_DATE.") = B.".static::COLUMN_DATE." ORDER BY ".static::COLUMN_DATE." DESC LIMIT 1)) THEN  ".self::getBindStrToDate()." 
                            WHEN ( extract(epoch from max(".static::COLUMN_DATE.") -  ".self::getBindStrToDate().") < 0) THEN max(".static::COLUMN_DATE.")
                            ELSE  ".self::getBindStrToDate()." 
                        END
                    )
                )
            )
            /86400
        )::integer ";
    }

    private function prepareSelectActualQuantity(&$counter)
    {
        $counter = $counter + 3;
        return "SUM(
            CASE
         WHEN (".static::COLUMN_MOVEMENT_TYPE." like 'RECEP' AND extract(epoch from ".static::COLUMN_DATE." - ".self::getBindStrToDate().")  < 0 ) THEN ".static::COLUMN_ACTUAL_QUANTITY."
         WHEN (".static::COLUMN_MOVEMENT_TYPE." like 'PREPA' AND extract(epoch from ".static::COLUMN_DATE." - ".self::getBindStrToDate().")  < 0 ) THEN (-1 * ".static::COLUMN_ACTUAL_QUANTITY.")
         WHEN (".static::COLUMN_MOVEMENT_TYPE." like 'REGUL' AND extract(epoch from ".static::COLUMN_DATE." - ".self::getBindStrToDate().")  < 0 ) THEN ".static::COLUMN_ACTUAL_QUANTITY."
         ELSE 0
          END
        )";
    }

    private function prepareSelectCalcNbDayNet(& $counter)
    {
        return "
        CASE 
			WHEN ".self::prepareSelectWarehouseDuration($counter)."  - MAX(df)::integer <= 0 THEN 0
			ELSE ".self::prepareSelectWarehouseDuration($counter)."  - MAX(df)::integer
		END 
        ";
    }

    private function prepareSelectCalcNbDayFactu(& $counter)
    {
        return "
            CASE WHEN ".self::prepareSelectCalcNbDayFactuTotal($counter)." > ".self::prepareSelectCalcNbDayNet($counter)." THEN ".self::prepareSelectCalcNbDayNet($counter)."
            ELSE ".self::prepareSelectCalcNbDayFactuTotal($counter)."
            END
            ";
    }

    private function prepareSelectCalcNbDayFactuTotal(&$counter)
    {
        $counter = $counter+2;
        return "
            CASE 
                WHEN (".self::prepareSelectWarehouseDuration($counter)." = 0 
                    AND max(".static::COLUMN_DATE.") < ".$this->getBindStrToDate()." 
                    AND max(".static::COLUMN_DATE.") < ".self::prepareSelectLastDayOfMonthOfSelectedDate($counter)."
                ) THEN date_part('day',max(".static::COLUMN_DATE."))
            ELSE date_part('day',".$this->getBindStrToDate().")
            END
        ";
    }

    private function prepareSelectLastDayOfMonthOfSelectedDate(&$counter)
    {
        $counter++;
        return " cast(date_trunc('month', ".$this->getBindStrToDate().") + '1 month'::interval as date) - 1 ";
    }

    private function prepareSelectCalcAmount(&$counter)
    {
        return "
            CASE
                WHEN MAX(".self::COLUMN_BILL_TYPE.") = '".self::BILL_TYPE_J."' THEN ".self::prepareSelectNbSupportForAmount($counter)." * MAX(".self::COLUMN_UNITARY_PRICE.")::float * ".self::prepareSelectCalcNbDayFactu($counter)."::float
	            WHEN MAX(".self::COLUMN_BILL_TYPE.") = '".self::BILL_TYPE_M."' THEN ".self::prepareSelectNbSupportForAmount($counter)." * MAX(".self::COLUMN_UNITARY_PRICE.")::float
	            ELSE '0'
            END
        ";
    }

    private function prepareSelectNbSupportForAmount(&$counter)
    {
        return "
        CASE 
	        WHEN ".self::prepareSelectActualQuantity($counter)." = 0 THEN ".self::prepareSelectCalcNbDayFactu($counter)."
	        ELSE ".self::prepareSelectActualQuantity($counter)."
        END
        ";
    }
}
