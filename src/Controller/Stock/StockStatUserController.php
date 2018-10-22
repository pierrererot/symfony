<?php

namespace App\Controller\Stock;

use App\Repository\Stock\StockStatUserRepository;
use App\Service\SessionFilterService;
use DataTable\DataTableRequest;
use DataTable\DataTableService;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

class StockStatUserController extends AbstractController
{
    protected static $columnLabel = [
        StockStatUserRepository::COLUMN_DEPOSIT_LABEL => "Dépot",
        StockStatUserRepository::COLUMN_USERNAME => "Utilisateur",
        StockStatUserRepository::COLUMN_NB_MVT => "Nb Support",
        StockStatUserRepository::COLUMN_BALANCE_RECEP => "RECEP",
        StockStatUserRepository::COLUMN_BALANCE_FUSION => "FUSION",
        StockStatUserRepository::COLUMN_BALANCE_REGUL => "REGUL",
        StockStatUserRepository::COLUMN_BALANCE_PREPA => "PREPA",
    ];

    /**
     * StockStatUserController constructor.
     * @param RegistryInterface $doctrine
     * @param Environment $twig
     * @param SessionFilterService $sessionFilterService
     */
    public function __construct(RegistryInterface $doctrine,  Environment $twig, SessionFilterService $sessionFilterService, AuthorizationCheckerInterface $authorizationChecker)
    {
        parent::__construct($doctrine, $twig, new StockStatUserRepository($doctrine), $sessionFilterService, $authorizationChecker);
    }

    /**
     * @Route( "/stock/stat-user/", name="stock_stat_user", methods={"GET"} )
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index()
    {
        $repository = $this->repository;
        $usernameList = $repository->getDistinctValueOfOneColumn("username");

        return new Response( $this->twig->render("stock/stat-user.html.twig",[
            "selects" => [
                "username" => $usernameList
            ],
        ]));
    }

    /**
     * @Route( "/stock/stat-user/data", name="stock_stat_user_data", methods={"POST"} )
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
     * @Route( "/stock/stat-user/export", name="stock_stat_user_export", methods={"POST"} )
     * @param Request $request
     * @return Response
     */
    public function export(Request $request)
    {
        return new Response($this->returnCsv($this->prepareService($request), self::$columnLabel));
    }

    /**
     * @param Request $request
     * @return DataTableService
     */
    private function prepareService(Request $request)
    {
        $request = new DataTableRequest($request->request->all());
        /** @var StockStatUserRepository $repository */
        $repository = $this->repository;
        $repository->setBetweenDateCondition($request->getColumns()[3]->getSearch()->getValue());
        return new DataTableService($repository, $request);
    }
}
