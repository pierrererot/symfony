<?php

namespace App\Controller\Stock;

use App\Repository\Stock\StockStatusRepository;
use App\Service\SessionFilterService;
use DataTable\DataTableRequest;
use DataTable\DataTableService;
use DataTable\src\Column\Column;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

class StockStatusController extends AbstractController
{
    protected static $columnLabel = [
        StockStatusRepository::COLUMN_CLIENT_CODE => "Code Client",
        StockStatusRepository::COLUMN_DEPOSIT_LABEL => "Dépot",
        StockStatusRepository::COLUMN_DEAL => "Commande d'entrée",
        StockStatusRepository::COLUMN_ACTUAL_QUANTITY => "NB supports",
        StockStatusRepository::COLUMN_IN_NETWORK_AT => "Entrée Réseau",
        StockStatusRepository::COLUMN_LAST_MOVEMENT_AT => "Dernier mouvement",
        StockStatusRepository::COLUMN_BALANCE_RECEP => "RECEP",
        StockStatusRepository::COLUMN_BALANCE_FUSION => "FUSION",
        StockStatusRepository::COLUMN_BALANCE_REGUL => "REGUL",
        StockStatusRepository::COLUMN_BALANCE_PREPA => "PREPA",
        StockStatusRepository::COLUMN_WAREHOUSE_DURATION => "Durée",
        StockStatusRepository::COLUMN_ITEMS => "Articles",
        StockStatusRepository::COLUMN_EXTERNAL_ORDER_REFERENCE => "Cde Client",
        StockStatusRepository::COLUMN_NB => "NB",
        StockStatusRepository::COLUMN_COLOR => "Couleur",
        StockStatusRepository::COLUMN_DF => "Franchise",
        StockStatusRepository::COLUMN_BILL_TYPE => "Type Facturation",
        StockStatusRepository::COLUMN_UNITARY_PRICE => "Prix Unitaire",
        StockStatusRepository::COLUMN_CALC_NB_J_NET => "NB jour Net",
        StockStatusRepository::COLUMN_CALC_NB_J_FACTU => "NB jour Facturable",
        StockStatusRepository::COLUMN_CALC_AMOUNT => "Montant",
    ];

    /**
     * StockStatusController constructor.
     * @param RegistryInterface $doctrine
     * @param Environment $twig
     * @param SessionFilterService $sessionFilterService
     */
    public function __construct(RegistryInterface $doctrine,  Environment $twig, SessionFilterService $sessionFilterService, AuthorizationCheckerInterface $authorizationChecker)
    {
        parent::__construct($doctrine, $twig, new StockStatusRepository($doctrine), $sessionFilterService, $authorizationChecker);
    }

    /**
     * @Route("/stock/status/", name="stock_status", methods={"GET"})
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index()
    {
        $repository = $this->repository;
        $movementList = $repository->getDistinctValueOfOneColumn("movement");

        return new Response( $this->twig->render("stock/status.html.twig",[
            "selects" => [
                "movement" => $movementList,
            ],
        ]));
    }

    /**
     * @Route( "/stock/status/data", name="stock_status_data", methods={"POST"} )
     * @param Request $request
     * @return Response
     */
    public function data(Request $request)
    {
        $response = new Response($this->prepareService($request)->getJsonResult());
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route( "/stock/status/export", name="stock_status_export", methods={"POST"} )
     * @param Request $request
     * @return Response
     */
    public function exportCsv(Request $request)
    {
        set_time_limit(600);
        return new Response($this->returnCsv($this->prepareService($request), self::$columnLabel));
    }

    /**
     * @param Request $request
     * @return DataTableService
     */
    private function prepareService(Request $request)
    {
        $request = new DataTableRequest($request->request->all());

        /** @var $repository StockStatusRepository */
        $repository = $this->repository;
        $repository->setDate($request->getColumns()[5]->getSearch()->getValue());
        return new DataTableService($repository, $request);
    }
}
