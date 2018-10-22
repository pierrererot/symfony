<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 30/07/2018
 * Time: 09:33
 */

namespace App\SqlFilter;

use App\Annotation\InternalUserFilterAnnotation;
use DataTable\src\Clause;
use DataTable\src\Conditions\InCondition;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class SessionFilter extends SQLFilter
{
    private $reader;

    public function setAnnotationReader(Reader $reader)
    {
        $this->reader = $reader;
    }
    /**
     * Gets the SQL query part to add to a query.
     *
     * @param ClassMetaData $targetEntity
     * @param string $targetTableAlias
     *
     * @return string The constraint SQL if there is available, empty string otherwise.
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        list($fieldName, $values) = $this->getFieldNameAndValues($targetEntity);

        if($fieldName){
            return $this->formatFilter($targetTableAlias, $fieldName, $values);
        }
        return '';
    }

    public function getDataTableClause(ClassMetadata $targetEntity)
    {
        list($fieldName, $values) = $this->getFieldNameAndValues($targetEntity);

        if($fieldName){
            $clause = new Clause();
            $clause->addCondition( new InCondition($fieldName, $values));
            return $clause;
        }
        return null;
    }

    /**
     * @param $alias
     * @param $field
     * @param $values
     * @return string
     */
    private function formatFilter($alias, $field, $values){
        return sprintf(" %s.%s IN ('%s')", $alias, $field, implode("','", $values));
    }

    /**
     * @param $name
     * @return bool|string
     */
    private function extractParameterWithoutSqlFormat($name)
    {
        return substr($this->getParameter($name),1,-1);
    }

    private function getFieldNameAndValues(ClassMetadata $targetEntity)
    {
        if (empty($this->reader)) {
            return ['',''];
        }

        try {
            $isInternal = $this->extractParameterWithoutSqlFormat("isInternal") === '1';
            $values =  json_decode($this->extractParameterWithoutSqlFormat("values"));
        } catch (\InvalidArgumentException $e) {
            return ['',''];
        }
        if($isInternal === true){

            // The Doctrine filter is called for any query on any entity
            // Check if the current entity is "user aware" (marked with an annotation)
            /** @var $internalUserFilter InternalUserFilterAnnotation*/
            $internalUserFilter = $this->reader->getClassAnnotation(
                $targetEntity->getReflectionClass(),
                'App\\Annotation\\InternalUserFilterAnnotation'
            );

            if (!$internalUserFilter) {
                return ['',''];
            }

            $fieldName = $internalUserFilter->targetFieldName;

            if (empty($fieldName)) {
                return ['',''];
            }

            return [$fieldName, $values];

        } else {

            /** @var $externalUserFilter InternalUserFilterAnnotation*/
            $externalUserFilter = $this->reader->getClassAnnotation(
                $targetEntity->getReflectionClass(),
                'App\\Annotation\\ExternalUserFilterAnnotation'
            );

            if (!$externalUserFilter) {
                return ['',''];
            }

            $fieldName = $externalUserFilter->targetFieldName;

            if (empty($fieldName)) {
                return ['',''];
            }

            return [$fieldName, $values];
        }
    }

}