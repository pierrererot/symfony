<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 04/09/2018
 * Time: 16:48
 */

namespace App\Service\SoapComplexType;

/**
 * Class Client
 * @package App\Service\SoapComplexType
 */
class Client
{
    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $siren;

    /**
     * @var string
     */
    public $siret;

    /**
     * @var string
     */
    public $tvaIntraCom;

    /**
     * @var string
     */
    public $factureur;

    /**
     * @var string
     */
    public $facture;

    /**
     * @var \App\Service\SoapComplexType\Checkpoint | Checkpoint
     */
    public $informations;
}