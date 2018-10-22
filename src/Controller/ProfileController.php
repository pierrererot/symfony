<?php

namespace App\Controller;

use App\Entity\Agency;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UsersType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Users;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController {

    /**
     * @Route("/profile", name="profile")
     */
    public function indexAction(Request $request, Environment $twig, RegistryInterface $doctrine, FormFactoryInterface $formFactory, UserInterface $user, UserPasswordEncoderInterface $encoder)
    {
        $clients = $doctrine->getRepository(Users::class)->getClients($user->getUsername());
        $clients = $this->getAgencies($doctrine, $clients);
        $formBuilder = $formFactory->createBuilder(UsersType::class, $user);
        $form = $formBuilder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $user = $doctrine->getRepository(Users::class)->findOneBy(array('username' => $form->get("username")->getData()));
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);
            $entityManager->persist($user);
            try {
                $entityManager->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Password updated');
            } catch (\Exception $e) {
                $request->getSession()->getFlashBag()->add('danger', 'Password not updated');
            }
        }

        return new Response($twig->render('profile/index.html.twig', array(
            'clients' => $clients,
            'posts' => $user,
            'form' => $form->createView()
        )));
    }

    private function getAgencies($doctrine, $clients) {
        foreach ($clients as $client) {
            $client->agencies = $doctrine->getRepository(Agency::class)->getAgencies($client->getId());
        }
        return $clients;
    }

}
