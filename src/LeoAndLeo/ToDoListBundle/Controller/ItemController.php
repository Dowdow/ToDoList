<?php

namespace LeoAndLeo\ToDoListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use LeoAndLeo\ToDoListBundle\Entity\ItemList;
use LeoAndLeo\ToDoListBundle\Form\ItemListType;

class ItemController extends Controller
{
    public function itemIdAction($id, $idItem) {
        $em = $this->getDoctrine()->getManager();
        $itemRepo = $em->getRepository('LeoAndLeoToDoListBundle:ItemList');

        $item = $itemRepo->findOneById($idItem);
        if($item == null || ($item->getMainList()->getId() !== $id)) {
            throw new NotFoundHttpException();
        }

        return $this->render('LeoAndLeoToDoListBundle:Page:itemid.html.twig', array('item' => $item));
    }

    public function itemAddAction($id) {
        $em = $this->getDoctrine()->getManager();
        $listRepo = $em->getRepository('LeoAndLeoToDoListBundle:MainList');
        $list = $listRepo->findOneById($id);

        if(!$list) {
            throw new NotFoundHttpException();
        }

        // Creation of the form
        $itemlist = new ItemList();
        $form = $this->createForm(new ItemListType(), $itemlist);

        // Exploitation of the form
        $request = $this->get('request');
        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            if($form->isValid()){
                $itemlist->setMainlist($list);
                $em->persist($itemlist);
                $em->flush();
                return $this->redirect($this->generateUrl('leo_and_leo_to_do_list.item_item_id', array('id' => $id, 'idItem' => $itemlist->getId())));
            }
        }

        return $this->render('LeoAndLeoToDoListBundle:Page:itemadd.html.twig', array('form' => $form->createView()));
    }

}
