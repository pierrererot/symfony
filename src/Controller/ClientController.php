<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 09/04/2018
 * Time: 11:05
 */

namespace App\Controller;

use App\Entity\Client;
use App\Service\ClientSoapService;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Zend\Soap\AutoDiscover;
use Zend\Soap\Server;
use Zend\Soap\Wsdl\ComplexTypeStrategy\ArrayOfTypeComplex;

class ClientController
{
    /**
     * @Route("/soap/client")
     * @param ClientSoapService $service
     * @param Request $request
     * @return Response
     */
    public function soap( ClientSoapService $service, Request $request )
    {

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=UTF-8');

        $getParamWsdlValue = $request->query->get('wsdl',null);
        if (isset($getParamWsdlValue)) {

            $autoDiscover = new AutoDiscover(new ArrayOfTypeComplex());
            $autoDiscover->setClass(get_class($service));
            $autoDiscover->setUri(explode("?",$request->getUri())[0]);
            $wsdl = $autoDiscover->generate();
            $response->setContent($wsdl->toXml());

        } else {

            $server = new Server('client.wsdl');
            $server->setObject($service);
            $result = $server->handle();
            $response->setContent($result);
        }

       return $response;
    }

    /**
     * @Route("/client/search")
     * @param Request $request
     * @param RegistryInterface $doctrine
     * @return JsonResponse
     */
    public function search(RegistryInterface $doctrine ,Request $request)
    {
        $query = $request->request->get("query_filter");
        if(is_null($query)){
            return new JsonResponse();
        }

        $offset = $request->request->get("query_offset", 0);
        $limit = $request->request->get("query_limit", 10);
        $order = $request->request->get("query_order", json_encode(["recipient1" => "ASC"]));
        $repository = $doctrine->getRepository(Client::class);
        $result = $repository->search(
            $query,
            json_decode($order, true),
            $limit,
            $offset
        );
        return new JsonResponse($result);
    }
}