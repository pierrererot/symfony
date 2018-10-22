<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 10/04/2018
 * Time: 15:15
 */

namespace App\Controller\Stock;

use App\Entity\Movement;
use App\Repository\Stock\AbstractMovementRepository;
use App\Repository\Stock\StockStatusRepository;
use App\Security\User\AbstractUser;
use App\Service\SessionFilterService;
use DataTable\DataTableService;
use DataTable\src\Column\Column;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /***
     * @var Environment
     */
    protected $twig;

    /**
     * @var RegistryInterface
     */
    protected $doctrine;

    /**
     * @var AbstractMovementRepository
     */
    protected $repository;

    /**
     * AbstractController constructor.
     * @param RegistryInterface $doctrine
     * @param Environment $twig
     * @param AbstractMovementRepository $repository
     * @param SessionFilterService $sessionFilterService
     */
    public function __construct(RegistryInterface $doctrine, Environment $twig, AbstractMovementRepository $repository, SessionFilterService $sessionFilterService, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->twig = $twig;
        $this->doctrine = $doctrine;

        if(!$authorizationChecker->isGranted(AbstractUser::ROLE_ADMIN)) {
            $classMetaDataOfSourceEntity = $doctrine->getManager()->getClassMetadata(Movement::class);
            if ($clause = $sessionFilterService->getSessionFilter()->getDataTableClause($classMetaDataOfSourceEntity)) {
                $repository->addDefaultClause($clause);
            }
        }
        $this->repository = $repository;
    }

    /**
     * @param $service  DataTableService
     * @param array $columnLabels
     * @return string
     */
    protected function returnCsv(DataTableService $service, array $columnLabels)
    {
        $exportedColumnList = [];
        $exportedFields = [];
        $exportedData = [];

        // ColumnList
        foreach ($service->getRepository()->getColumns() as $column) {
            if (isset($columnLabels[$column->getName()])) {
                /**
                 * ID = [ COLUMN , LABEL ]
                 * This format is used for separate Dates in two column and create their labels
                 */
                $exportedColumnList[$column->getId()] = ["column" => $column, "label" => $columnLabels[$column->getName()]];
            }
        }

        // Data
        $datas = $service->getAllResponseData();
        foreach ($datas as $data) {
            $fields = explode(";", $data->csvSerialize());
            foreach ($exportedColumnList as $exportedColumnListRow) {
                /**
                 * @var $column Column
                 */
                $column = $exportedColumnListRow["column"];
                switch ($column->getName()) {
                    case StockStatusRepository::COLUMN_CREATED_AT:
                    case StockStatusRepository::COLUMN_DATE:
                    case StockStatusRepository::COLUMN_IN_NETWORK_AT:
                    case StockStatusRepository::COLUMN_LAST_MOVEMENT_AT:
                        $stringDate = $fields[$column->getId()];
                        $datetime = \DateTime::createFromFormat("d/m/Y H:i", $stringDate);
                        $date = $datetime->format('d/m/Y');
                        $time = $datetime->format('H:i');
                        $exportedFields[$column->getId()*10] = $date;
                        $exportedFields[$column->getId()*10 +1] = $time;
                        break;
                    default:
                        $exportedFields[$column->getId()*10] = $fields[$column->getId()];
                }
            }
            $exportedData[] = implode(";", $exportedFields) . "\r\n";
        }

        // Export
        $output = implode(
                ";",
                array_map(
                    function ( array $exportedColumnListRow) {
                        $column = $exportedColumnListRow["column"];
                        $label = $exportedColumnListRow["label"];
                        switch ($column->getName()) {
                            case StockStatusRepository::COLUMN_CREATED_AT:
                            case StockStatusRepository::COLUMN_IN_NETWORK_AT:
                            case StockStatusRepository::COLUMN_LAST_MOVEMENT_AT:
                                return  "Date $label;Heure $label";
                                break;
                            case StockStatusRepository::COLUMN_DATE:
                                return  "Date;Heure";
                                break;
                            default:
                                return $label;
                                break;
                        }
                    },
                    $exportedColumnList
                )
            ). "\r\n";

        $output .= "";
        foreach ($exportedData as $data) {
            $output .= $data;
        }
        return $output;
    }
}
