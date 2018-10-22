<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 10/04/2018
 * Time: 11:43
 */

namespace App\Service;

use App\Service\SoapComplexType\Command;

interface ClientServiceInterface
{
    /**
     * @param string $clientCode
     * @param array | Command[] $commands
     * @return mixed
     */
    public function updateClientCommands($clientCode, array $commands);
}