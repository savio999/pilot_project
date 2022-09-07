<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\RegistrationController;

class HomeController extends AbstractController
{


    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {        
        if ($this->getUser()) {
            return $this->redirectToRoute('list.view');
        }

        return $this->redirectToRoute('app_register');
    }

    /**
     * @Route("/test_page", name="test_page")
     */
    public function testPage(){
        
    }
}
