<?php

namespace LeoAndLeo\ToDoListBundle\Controller;

use LeoAndLeo\ToDoListBundle\Entity\MainList;
use LeoAndLeo\ToDoListBundle\Form\MainListType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ListController extends Controller
{
    public function indexAction()
    {
        return $this->render('LeoAndLeoToDoListBundle:Page:index.html.twig');
    }

    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listRepo = $em->getRepository('LeoAndLeoToDoListBundle:MainList');

        $lists = $listRepo->findAll();

        return $this->render('LeoAndLeoToDoListBundle:Page:list.html.twig', array('lists' => $lists));
    }

    public function listAddAction()
    {
        $em = $this->getDoctrine()->getManager();

        // Creation of the form
        $mainlist = new MainList();
        $form = $this->createForm(new MainListType(), $mainlist);

        // Exploitation of the form
        $request = $this->get('request');
        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            if($form->isValid()){
                $em->persist($mainlist);
                $em->flush();
                return $this->redirect($this->generateUrl('leo_and_leo_to_do_list.list_list_id', array('id' => $mainlist->getId())));
            }
        }

        return $this->render('LeoAndLeoToDoListBundle:Page:listadd.html.twig', array('form' => $form->createView()));
    }

    public function listIdAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $listRepo = $em->getRepository('LeoAndLeoToDoListBundle:MainList');

        $list = $listRepo->findOneById($id);
        if($list == null) {
            throw new NotFoundHttpException();
        }

        return $this->render('LeoAndLeoToDoListBundle:Page:listid.html.twig', array('list' => $list));
    }
}
