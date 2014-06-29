<?php

namespace LeoAndLeo\ToDoListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use LeoAndLeo\ToDoListBundle\Entity\MainList;
use LeoAndLeo\ToDoListBundle\Form\MainListType;

class ListGoogleController extends Controller {

    public function listAction() {
        $client = $this->get('leo_and_leo_google.main_list_client');
        $lists = $client->getAll();
        return $this->render('LeoAndLeoToDoListBundle:Google:list.html.twig', array('lists' => $lists));
    }

    public function listIdAction($id) {
        $client = $this->get('leo_and_leo_google.main_list_client');
        $list = $client->get($id);
        if($list == null) {
            throw new NotFoundHttpException();
        }

        $request = $this->get('request');
        if($request->getMethod() == 'POST'){
            if($request->request->get('done') && $request->request->get('id')) {
                foreach($list->getItemlists() as $value) {
                    if($value->getId() == $request->request->get('id')) {
                        $value->inverseDone();
                        $itemclient = $this->get('leo_and_leo_google.item_list_client');
                        $itemclient->update($id, $value);
                        break;
                    }
                }
            }
        }

        return $this->render('LeoAndLeoToDoListBundle:Google:listid.html.twig', array('list' => $list));
    }

    public function listAddAction() {
        // Creation of the form
        $mainlist = new MainList();
        $form = $this->createForm(new MainListType(), $mainlist);

        // Exploitation of the form
        $request = $this->get('request');
        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            if($form->isValid()){
                $client = $this->get('leo_and_leo_google.main_list_client');
                $mainlist = $client->insert($mainlist);
                return $this->redirect($this->generateUrl('leo_and_leo_to_do_list.list_google_list_id', array('id' => $mainlist->getId())));
            }
        }

        return $this->render('LeoAndLeoToDoListBundle:Google:listadd.html.twig', array('form' => $form->createView()));
    }

    public function listEditAction($id) {
        $client = $this->get('leo_and_leo_google.main_list_client');

        // Creation of the form
        $mainlist = $client->get($id);
        if($mainlist == null) {
            throw new NotFoundHttpException();
        }
        $form = $this->createForm(new MainListType(), $mainlist);

        // Exploitation of the form
        $request = $this->get('request');
        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            if($form->isValid()){
                $mainlist = $client->update($mainlist);
                return $this->redirect($this->generateUrl('leo_and_leo_to_do_list.list_google_list_id', array('id' => $mainlist->getId())));
            }
        }

        return $this->render('LeoAndLeoToDoListBundle:Google:listedit.html.twig', array('form' => $form->createView()));
    }

    public function listRemoveAction($id) {
        $client = $this->get('leo_and_leo_google.main_list_client');

        $mainlist = $client->get($id);
        if($mainlist == null) {
            throw new NotFoundHttpException();
        }

        $request = $this->get('request');
        if($request->getMethod() == 'POST') {
            if($request->request->get('remove')) {
                $client->delete($id);
                return $this->redirect($this->generateUrl('leo_and_leo_to_do_list.list_google_list'));
            }
        }

        return $this->render('LeoAndLeoToDoListBundle:Google:listremove.html.twig');
    }

} 