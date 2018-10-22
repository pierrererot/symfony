<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 04/09/2018
 * Time: 11:25
 */

namespace App\Service\SoapComplexType;

use Symfony\Component\Validator\Constraintsstring;

/**
 * Class Command
 * @package App\Service\SoapComplexType
 */
class Command
{
    /**
     * @var integer
     */
    public $ortr;

    /**
     * CMR - Lettre de voiture
     * @var string
     */
    public $cmr;

    /**
     * FAC -Numéro de facture
     * @var string
     */
    public $fac;

    /**
     * DTFAC - Date de facture
     * @var string
     */
    public $dtfac;

    /**
     * MTNET - Montant Hors Taxe
     * @var string
     */
    public $mtnet;

    /**
     * TITR - Code commercial
     * @var string
     */
    public $titr;

    /**
     * DDEV - Réference code devis
     * @var string
     */
    public $ddev;

    /**
     * PORD
     * @var string
     */
    public $pord;

    /**
     * DORD
     * @var string
     */
    public $dord;

    /**
     * NDORD
     * @var string
     */
    public $ndord;

    /**
     * Celui qui saisie
     * @var string
     */
    public $acom;

    /**
     * Reference dossier
     * @var string
     */
    public $refdos;

    /**
     * Commande client
     * @var string
     */
    public $cdeclt;

    /**
     * Reference client
     * @var string
     */
    public $refcde;

    /**
     * Code Activité
     * @var string
     */
    public $acti;

    /**
     * Code prestation
     * @var string
     */
    public $prst;

    /**
     * Code
     * @var string
     */
    public $refot;

    /**
     * Date de début
     * @var string
     */
    public $dtce1;

    /**
     * Date de fin
     * @var string
     */
    public $dtcd1;

    /**
     * Première séquence
     * @var string
     */
    public $seqce;

    /**
     * Dernière séquence
     * @var string
     */
    public $seqcd;

    /**
     * Société facturante
     * @var string
     */
    public $socf;

    /**
     * code
     * @var string
     */
    public $recep;

    /**
     * Code Portefeuille
     * @var string
     */
    public $tipf;

    /**
     * Ville Expedition
     * @var string
     */
    public $ve;

    /**
     * Ville Destination
     * @var string
     */
    public $vd;

    /**
     * Région/zone enlèvement
     * @var string
     */
    public $rege;

    /**
     * Région/zone destination
     * @var string
     */
    public $regd;

    /**
     * Code agence
     * @var string
     */
    public $agce;

    /**
     * Numéro d'affaire
     * @var string
     */
    public $affaire_id;

    /**
     * Code Statut
     * @var string
     */
    public $typ2_cst;

    /**
     * Date arrivée Enlèvement
     * @var string
     */
    public $rappDtarr_e;

    /**
     * Date départ Enlèvement
     * @var string
     */
    public $rappDtdep_e;

    /**
     * Date arrivée Destination
     * @var string
     */
    public $rappDtarr_d;

    /**
     * Date départ Destination
     * @var string
     */
    public $rappDtdep_d;

    /**
     * Site de d'enlèvement
     * @var \App\Service\SoapComplexType\Checkpoint | Checkpoint
     */
    public $siteElevement;

    /**
     * Site de destination
     * @var \App\Service\SoapComplexType\Checkpoint | Checkpoint
     */
    public $siteDestination;

    /**
     * Liste des Phases de la commandes (OTTP et info voya)
     * @var \App\Service\SoapComplexType\Phase[] | Phase[]
     */
    public $phases;

    /**
     * @var string
     */
    public $rdvDate;

}