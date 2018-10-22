<?php

namespace App\Controller;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;

class LoginController {

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request,  AuthenticationUtils $authenticationUtils, Environment $twig)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return new Response($twig->render('login/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error
        )));
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction() {

    }
}
