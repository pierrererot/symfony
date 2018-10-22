<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 18/09/2018
 * Time: 16:03
 */

namespace App\Controller;


use App\Service\ReferentielSoapService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Zend\Soap\AutoDiscover;
use Zend\Soap\Server;
use Zend\Soap\Wsdl\ComplexTypeStrategy\ArrayOfTypeComplex;

class ReferentielController
{
    /**
     * @Route("/soap/referentiel")
     * @param ReferentielSoapService $service
     * @param Request $request
     * @return Response
     */
    public function soap( ReferentielSoapService $service, Request $request )
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/xml; charset=UTF-8');

        $getParamWsdlValue = $request->query->get('wsdl',null);
        if (isset($getParamWsdlValue)) {

            $autoDiscover = new AutoDiscover(new ArrayOfTypeComplex());

            $autoDiscover->setClass(get_class($service));
            $autoDiscover->setUri(explode("?",$request->getUri())[0]);
            $wsdl = $autoDiscover->generate();
            $response->setContent($wsdl->toXml());

        } else {

            $server = new Server('referentiel.wsdl');
            $server->setObject($service);
            $result = $server->handle();
            $response->setContent($result);
        }

        return $response;
    }
}