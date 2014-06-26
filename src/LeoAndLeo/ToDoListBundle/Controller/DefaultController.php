<?php

namespace LeoAndLeo\ToDoListBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('LeoAndLeoToDoListBundle:Default:index.html.twig');
    }

} 