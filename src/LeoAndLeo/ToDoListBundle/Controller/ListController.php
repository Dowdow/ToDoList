<?php

namespace LeoAndLeo\ToDoListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ListController extends Controller
{
    public function indexAction()
    {
        return $this->render('LeoAndLeoToDoListBundle:Page:index.html.twig');
    }
}
