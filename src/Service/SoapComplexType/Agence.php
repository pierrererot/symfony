<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 04/09/2018
 * Time: 16:48
 */

namespace App\Service\SoapComplexType;

/**
 * Class Agence
 * @package App\Service\SoapComplexType
 */
class Agence
{
    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $database;

    /**
     * @var \App\Service\SoapComplexType\Checkpoint | Checkpoint
     */
    public $informations;
}