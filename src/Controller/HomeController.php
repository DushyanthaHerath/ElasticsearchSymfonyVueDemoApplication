<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home_FE")
     * @Route("/{route}", name="vue_pages", requirements={"route"="^(?!(api|token|authorize)).+"})
     */
    public function index() {
        return $this->render('app.html.twig');
    }
}
