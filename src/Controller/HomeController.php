<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route(path :"/", name : "home")]
    public function index(Request $request): Response{
        // var_dump($request);

        // dump($request);
        // die;

        // dd($request); // equivalent du var_dump mais plus jolie

        // return new Response("Hello " . $_GET['nom']);
        return $this->render('Home/index.html.twig');
    }
}
