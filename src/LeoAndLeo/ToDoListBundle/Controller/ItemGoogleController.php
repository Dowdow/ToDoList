<?php

namespace LeoAndLeo\ToDoListBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use LeoAndLeo\ToDoListBundle\Entity\ItemList;
use LeoAndLeo\ToDoListBundle\Form\ItemListType;

class ItemGoogleController extends Controller {

    public function itemIdAction($id, $idItem) {
        $client = $this->get('leo_and_leo_google.item_list_client');

        $item = $client->get($id, $idItem);
        if($item == null || ($item->getMainlist()->getId() != $id)) {
            throw new NotFoundHttpException();
        }

        $request = $this->get('request');
        if($request->getMethod() == 'POST'){
            if($request->request->get('done')) {
                $item->inverseDone();
                $item = $client->update($id, $item);
            }
        }

        return $this->render('LeoAndLeoToDoListBundle:Google:itemid.html.twig', array('item' => $item));
    }

    public function itemAddAction($id) {
        $client = $this->get('leo_and_leo_google.main_list_client');

        $list = $client->get($id);
        if(!$list) {
            throw new NotFoundHttpException();
        }

        // Creation of the form
        $item = new ItemList();
        $form = $this->createForm(new ItemListType(), $item);

        // Exploitation of the form
        $request = $this->get('request');
        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            if($form->isValid()){
                $item->setMainlist($list);
                $client = $this->get('leo_and_leo_google.item_list_client');
                $item = $client->insert($id, $item);
                return $this->redirect($this->generateUrl('leo_and_leo_to_do_list.item_google_item_id', array('id' => $id, 'idItem' => $item->getId())));
            }
        }

        return $this->render('LeoAndLeoToDoListBundle:Google:itemadd.html.twig', array('form' => $form->createView()));
    }

    public function itemEditAction($id,$idItem) {
        $client = $this->get('leo_and_leo_google.item_list_client');

        $item = $client->get($id, $idItem);
        if($item == null || ($item->getMainlist()->getId() != $id)) {
            throw new NotFoundHttpException();
        }

        // Creation of the form
        $form = $this->createForm(new ItemListType(), $item);

        // Exploitation of the form
        $request = $this->get('request');
        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            if($form->isValid()){
                $item = $client->update($id, $item);
                return $this->redirect($this->generateUrl('leo_and_leo_to_do_list.item_google_item_id', array('id' => $id, 'idItem' => $item->getId())));
            }
        }

        return $this->render('LeoAndLeoToDoListBundle:Google:itemedit.html.twig', array('form' => $form->createView()));
    }

    public function itemRemoveAction($id,$idItem) {
        $client = $this->get('leo_and_leo_google.item_list_client');

        $item = $client->get($id, $idItem);
        if($item == null || ($item->getMainlist()->getId() != $id)) {
            throw new NotFoundHttpException();
        }

        $request = $this->get('request');
        if($request->getMethod() == 'POST') {
            if($request->request->get('remove')) {
                $client->delete($id, $idItem);
                return $this->redirect($this->generateUrl('leo_and_leo_to_do_list.list_google_list_id', array('id' => $id)));
            }
        }

        return $this->render('LeoAndLeoToDoListBundle:Google:itemremove.html.twig');
    }
} 