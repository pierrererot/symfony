<?php

namespace App\Controller;

use App\Entity\OrderComment;
use App\Entity\OrderStatus;
use App\Entity\Parameters;
use App\Entity\ReferentielActivity;
use App\Entity\ReferentielBenefit;
use App\Entity\ReferentielEAN;
use App\Entity\ReferentielExploitation;
use App\Entity\Trader;
use App\Form\OrderCommentType;
use App\Service\OrderSoapService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Orders;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use App\Form\OrdersManualType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Knp\Component\Pager\PaginatorInterface;
use Zend\Soap\AutoDiscover;
use Zend\Soap\Server;
use Zend\Soap\Wsdl\ComplexTypeStrategy\ArrayOfTypeComplex;

class OrdersController
{
    /**
     * @Route( "/orders/all", name="get_orders")
     */
    public function getOrders(RegistryInterface $doctrine, Environment $twig)
    {
        /**
         * @var $data = Orders[]
         */
        $data = $doctrine->getRepository(Orders::class)->findAll();
        return new Response($twig->render('Orders/tracking.html.twig', ["orders" => $data]));

    }

    /**
     * @Route("/orders/{order_id}", name="view_order", requirements={"order_id"="\d+"})
     */
    public function viewOrderAction($order_id, RegistryInterface $doctrine, Environment $twig, UrlGeneratorInterface $generatorInterface, Request $request, FormFactoryInterface $formFactory, TokenStorageInterface $tokenStorage)
    {
        $order = $doctrine->getRepository(Orders::class)->findOneBy(array('id' => $order_id));
        if (!$order) {
            return new RedirectResponse(
                $generatorInterface->generate('orders_manual')
            );
        }
        $orderComment = new OrderComment();
        $formBuilder = $formFactory->createBuilder(OrderCommentType::class, $orderComment);
        $form = $formBuilder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $orderComment->setOrder($order);
            $user = $tokenStorage->getToken()->getUser();
            $orderComment->setUser($user);
            $orderComment->setUpdatedAt(new \DateTime());
            $orderComment->setStatus('in_progress');
            $entityManager->persist($orderComment);
            try {
                $entityManager->flush();
                $request->getSession()->getFlashBag()->add('notice', 'New comment');
            } catch (\Exception $e) {
                $request->getSession()->getFlashBag()->add('danger', 'Comment not added');
            }
        }

        $comments = $doctrine->getRepository(OrderComment::class)->findbyOrder($order_id);
        return new Response($twig->render('orders/view_order.html.twig', array(
            'order' => $order,
            'comments' => isset($comments) ? $comments : null,
            'form' => $form->createView(),
        )));
    }

    /**
     * @Route("/new_manual_order", name="new_manual_order")
     */
    public function newManualOrderAction(Request $request, Environment $twig, RegistryInterface $doctrine, FormFactoryInterface $formFactory)
    {
        /** @var Parameters $parameters */
        $parameters = $doctrine->getRepository(Parameters::class)->findOne();
        $communicationOMP = $parameters->getCommunicationOMP();
        $order = new Orders();
        $formBuilder = $formFactory->createBuilder(OrdersManualType::class, $order);
        $form = $formBuilder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            try {
                $xml = $this->generateXML($formData);
                if ($communicationOMP) {
                    $this->CallSoapDex($xml);
                }
                $request->getSession()->getFlashBag()->add('notice', 'New order created');
            } catch (\Exception $e) {
                $request->getSession()->getFlashBag()->add('danger', 'Order not created');
            }
        }

        return new Response($twig->render('orders/new_manual_order.html.twig', array(
            'form' => $form->createView()
        )));
    }

    private function CallSoapDex($xml) {
        // TODO
    }

    private function generateXML(Orders $formData) {
        $xml_doc = new \DOMDocument('1.0', 'utf-8');
        $xml_doc->preserveWhiteSpace = false;
        $xml_doc->formatOutput = true;
        $timestamp = time();

        $affaire_node = $xml_doc->createElement('affaire');
        $affaire_node->setAttribute('id', $timestamp);
        $affaire_node->setAttribute('client', $formData->getClient()->getSourceReference());
        $xml_doc->appendChild($affaire_node);

        // TODO complete the current XML

        $xml_string = $xml_doc->save(__DIR__ . "\..\..\data\xml\orders_ " . $timestamp . ".xml");

        // TODO set correct XML according to manual order's data
        return $xml_string;
    }




    /**
     * @Route("/soap/order")
     * @param OrderSoapService $service
     * @param Request $request
     * @return Response
     */
    public function soap( OrderSoapService $service, Request $request )
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

            $server = new Server('order.wsdl');
            $server->setObject($service);
            $result = $server->handle();
            $response->setContent($result);
        }
        return $response;
    }

}
