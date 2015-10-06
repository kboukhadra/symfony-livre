<?php

namespace KhTestBundle\Controller;

use KhTestBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('KhTestBundle:Default:index.html.twig');
    }


    public function newAction(Request $request)
    {


        return $this->render('KhTestBundle:Default:new.html.twig');
    }


    public function sucessAction(){
        return new Response('Ok pour l\'enregistrement');
    }
}
