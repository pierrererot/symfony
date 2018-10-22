<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 15/05/2018
 * Time: 17:35
 */
namespace App\Repository;

interface UpdateCascadeInterface
{
    /**
     * @param $oldEntity
     * @param $newEntity
     * @return mixed
     */
    public function update(&$oldEntity, $newEntity);


}