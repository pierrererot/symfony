<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;


class HomeController {

    /**
     * @Route("", name="home")
     */
    public function indexAction(Environment $twig)
    {
        $variables = []; // TODO
        return new Response($twig->render('home/index.html.twig',
            $variables
        ));
    }

}
