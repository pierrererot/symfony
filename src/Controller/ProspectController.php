<?php

namespace App\Controller;

use App\Controller\Stock\AbstractController;
use App\Entity\OrderComment;
use App\Entity\OrderStatus;
use App\Entity\Parameters;
use App\Entity\Prospect;
use App\Entity\ReferentielActivity;
use App\Entity\ReferentielBenefit;
use App\Entity\ReferentielEAN;
use App\Entity\ReferentielExploitation;
use App\Entity\Trader;
use App\Entity\Users;
use App\Form\OrderCommentType;
use App\Form\ProspectConfirmType;
use App\Form\ProspectType;
use App\Security\User\DefaultUser;
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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProspectController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
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
     * Admin users only can create a new prospect
     * @Route("/new_prospect", name="new_prospect")
     */
    public function indexAction(Request $request, Environment $twig, RegistryInterface $doctrine, FormFactoryInterface $formFactory, PaginatorInterface $paginator, \Swift_Mailer $mailer)
    {
        $prospect = new Prospect();
        $formBuilder = $formFactory->createBuilder(ProspectType::class, $prospect);
        $form = $formBuilder->getForm();
        $form->handleRequest($request);
        $errorMessage = '';
        $completed = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $email = $form->get('email')->getData();
            $foundProspect = $doctrine->getRepository(Prospect::class)->findByEmail($email);
            $foundUser = $doctrine->getRepository(Users::class)->findByLogin($email);
            if (sizeof($foundUser) !== 0) {
                $errorMessage = 'Erreur - cet email est déjà enregistré';
            } else {
                try {
                    $hash = $this->generateHash(12);
                    $password = $this->generateHash(8);
                    $prospect->setEmail($email);
                    $prospect->setHash($hash);
                    $prospect->setPassword($password);
                    $entityManager = $doctrine->getManager();
                    $entityManager->persist($prospect);
                    $entityManager->flush();

                    // TODO : edit new email message
                    $message = (new \Swift_Message('Hello Email'))
                        ->setFrom('send@example.com')
                        ->setTo('send@example.com')
                        ->setBody('test', 'text/html');
                    $mailer->send($message);
                    $completed = true;

                } catch (\Exception $e) {
                    $request->getSession()->getFlashBag()->add('danger', 'Order not created');
                }
            }
        }
        return new Response($twig->render('prospect/index.html.twig', array(
            'hash' => isset($hash) ? $hash : null,
            'password' => isset($password) ? $password : null,
            'form' => $form->createView(),
            'message' => $errorMessage,
            'completed' => $completed,
        )));
    }

    private function generateHash($length)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }

    /**
     * Matches /prospect/*
     *
     * @Route("/prospect/{hash}", name="prospect")
     */
    public function show($hash, Request $request, Environment $twig, RegistryInterface $doctrine, FormFactoryInterface $formFactory, PaginatorInterface $paginator, \Swift_Mailer $mailer, UserPasswordEncoderInterface $encoder) {
        // RULE 1 - redirect to the homepage if the hash isn't correct
        $prospect = $doctrine->getRepository(Prospect::class)->findByHash($hash);
        if (sizeof($prospect) == 0) {
            return $this->redirectToRoute('login');
        }

        // RULE 2 - redirect to the homepage if the user is already registered
        $email = $prospect->getEmail();
        $foundUser = $doctrine->getRepository(Users::class)->findByLogin($email);
        if (sizeof($foundUser) !== 0) {
            return $this->redirectToRoute('login');
        }

        // Else - allow the prospect to become an user
        $user = new Users();
        $formBuilder = $formFactory->createBuilder(ProspectConfirmType::class, $user);
        $form = $formBuilder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // register the new user and delete the obselete prospect
            $manager = $doctrine->getEntityManager();
            $user->setCreatedAt(new \DateTime());
            $user->setEnabled(true);
            $user->setAdmin(false);
            $user->setLogin($email);
            $user->setUsername($email);
            $defaultUser = new DefaultUser($user);
            $encoded = $encoder->encodePassword($defaultUser, $form->get('password')->getData());
            $user->setPassword($encoded);
            $manager->remove($prospect);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('login');
        }

        return new Response($twig->render('prospect/confirm.html.twig', array(
            'hash' => isset($hash) ? $hash : null,
            'email' => $email,
            'form' => $form->createView(),
        )));
    }

}
