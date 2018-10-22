<?php

namespace App\Controller\Stock;

use App\Repository\Stock\StockMovementRepository;
use App\Service\SessionFilterService;
use App\Service\StockSoapService;
use DataTable\DataTableRequest;
use DataTable\DataTableService;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;
use Zend\Soap\AutoDiscover;
use Zend\Soap\Server;
use Zend\Soap\Wsdl\ComplexTypeStrategy\ArrayOfTypeComplex;

class StockMovementController extends AbstractController
{

    protected static $columnLabel = [
        StockMovementRepository::COLUMN_DEPOSIT_LABEL => "Dépot",
        StockMovementRepository::COLUMN_CLIENT_CODE => "Code Client",
        StockMovementRepository::COLUMN_INTERNAL_ORDER_REFERENCE => "Commande interne",
        StockMovementRepository::COLUMN_EXTERNAL_ORDER_REFERENCE => "Commande client",
        StockMovementRepository::COLUMN_ACTUAL_QUANTITY => "Nb supports",
        StockMovementRepository::COLUMN_DATE => "Date",
        StockMovementRepository::COLUMN_MOVEMENT_TYPE => "Type",
        StockMovementRepository::COLUMN_MOVEMENT => "Mouvement",
        StockMovementRepository::COLUMN_CONDITION => "Etat",
        StockMovementRepository::COLUMN_USERNAME => "Utilisateur",
        StockMovementRepository::COLUMN_ITEMS => "Articles",
    ];

    public function __construct(RegistryInterface $doctrine,  Environment $twig , SessionFilterService $sessionFilterService, AuthorizationCheckerInterface $authorizationChecker )
    {
        parent::__construct($doctrine, $twig, $doctrine->getRepository('App:Movement'), $sessionFilterService,  $authorizationChecker);
    }

    /**
     * @Route( "/stock/movement/", name="stock_movement", methods={"GET"} )
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index()
    {
        $repository = $this->repository;
        $movementTypeList = $repository->getDistinctValueOfOneColumn("movementType");
        $movementList = $repository->getDistinctValueOfOneColumn("movement");
        $conditionList = $repository->getDistinctValueOfOneColumn("condition");
        $usernameList = $repository->getDistinctValueOfOneColumn("username");

        return new Response( $this->twig->render("stock/movement.html.twig",[
            "selects" => [
                "movement_type" => $movementTypeList,
                "movement" => $movementList,
                "condition" => $conditionList,
                "username" => $usernameList
            ],
        ]));
    }

    /**
     * @Route( "/stock/movement/data", name="stock_movement_data", methods={"POST"} )
     * @param Request $request
     * @return Response
     */
    public function data(Request $request)
    {
        $request = new DataTableRequest($request->request->all());
        $service = new DataTableService($this->repository, $request);
        $response = new Response($service->getJsonResult());
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route( "/stock/movement/export", name="stock_movement_export", methods={"POST"} )
     * @param Request $request
     * @return Response
     */
    public function export(Request $request)
    {
        $request = new DataTableRequest($request->request->all());
        $service = new DataTableService($this->repository, $request);

        return new Response($this->returnCsv($service, static::$columnLabel));
    }

    /**
     * @Route("/stock/movement/soap")
     * @param StockSoapService $service
     * @param Request $request
     * @return Response
     */
    public function soap( StockSoapService $service, Request $request )
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=UTF-8');

        $getParamWsdlValue = $request->query->get('wsdl',null);
        if (isset($getParamWsdlValue)) {
            $autoDiscover = new AutoDiscover(new ArrayOfTypeComplex());
            $autoDiscover->setClass(get_class($service));
            $autoDiscover->setUri(explode("?",$request->getUri())[0]);
            $response->setContent($autoDiscover->toXml());
        } else {
            $server = new Server('stock.wsdl');
            $server->setObject($service);
            $result = $server->handle();
            $response->setContent($result);
        }
        return $response;
    }
}


