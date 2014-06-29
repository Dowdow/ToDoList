<?php

namespace LeoAndLeo\GoogleBundle\Service;

use Google_Service_Tasks;
use HappyR\Google\ApiBundle\Services\GoogleClient;
use LeoAndLeo\ToDoListBundle\Entity\ItemList;
use LeoAndLeo\ToDoListBundle\Entity\MainList;
use Symfony\Component\Security\Core\SecurityContext;

class ItemListClient {

    /** @var Google_Service_Tasks the Service task class */
    private $service;

    /**
     * Constructor
     * @param $client GoogleClient
     * @param $security SecurityContext
     */
    public function __construct($client, $security) {
        $token = $security->getToken()->getUser();
        $googleClient = $client->getGoogleClient();
        $googleClient->setAccessToken($token);

        $this->service = new Google_Service_Tasks($googleClient);
    }


    /**
     * Get all the user tasks
     * @param $taskListId string the id of the task list parent
     * @return array
     */
    public function getAll($taskListId) {
        $itemlists = array();
        $tasks = $this->service->tasks->listTasks($taskListId)->getItems();
        foreach($tasks as $key => $value) {
            $itemlists[$key] = $this->buildTask($taskListId, $value);
        }

        return $itemlists;
    }

    /**
     * Get a user task
     * @param $taskListId string the id of the task list parent
     * @param $id string the id of the task
     * @return ItemList
     */
    public function get($taskListId,$id) {
        $task = $this->service->tasks->get($taskListId,$id);
        return $this->buildTask($taskListId, $task);
    }

    /**
     * Add a user task
     * @param $taskListId string the id of the task list parent
     * @param $itemList ItemList
     * @return ItemList
     */
    public function insert($taskListId, $itemList) {
        $task = new \Google_Service_Tasks_Task();
        $task->setKind('tasks#task');
        $task->setTitle($itemList->getTitle());
        $task->setNotes($itemList->getContent());
        $task->setDue($itemList->getDeadline()->format(\DateTime::RFC3339));
        $task->setDeleted(false);
        $task->setHidden(false);
        $task->setParent($taskListId);
        $date = new \DateTime();
        $date->format(\DateTime::RFC3339);
        $task->setUpdated($date);
        if($itemList->getDone()) {
            $task->setStatus('completed');
            $task->setCompleted($date);
        }

        $taskList = $this->service->tasks->insert($taskListId, $task);
        return $this->buildTask($taskListId, $taskList);
    }

    /**
     * Edit a user task
     * @param $taskListId string the id of the task list parent
     * @param $itemList ItemList
     * @return ItemList
     */
    public function update($taskListId, $itemList) {
        $task = new \Google_Service_Tasks_Task();
        $task->setKind('tasks#task');
        $task->setId($itemList->getId());
        $task->setTitle($itemList->getTitle());
        $task->setNotes($itemList->getContent());
        $task->setDue($itemList->getDeadline()->format(\DateTime::RFC3339));
        $task->setDeleted(false);
        $task->setHidden(false);
        $task->setParent($taskListId);
        $date = new \DateTime();
        $date->format(\DateTime::RFC3339);
        $task->setUpdated($date);
        if($itemList->getDone()) {
            $task->setStatus('completed');
            $task->setCompleted($date);
        } else {
            $task->setStatus('needsAction');
            $task->setCompleted($date);
        }

        $taskList = $this->service->tasks->update($taskListId, $itemList->getId(),$task);
        return $this->buildTask($taskListId, $taskList);
    }

    /**
     * Remove a user task
     * @param $taskListId string the id of the task list parent
     * @param $id string the id of the task
     */
    public function delete($taskListId, $id) {
        $this->service->tasks->delete($taskListId, $id);
    }

    /**
     * Convert the json Google return into ItemList object
     * @param $taskListId string
     * @param $task string
     * @return ItemList
     */
    private function buildTask($taskListId, $task) {
        $list = new ItemList();
        if(isset($task->id)){
            $list->setId($task->id);
        }
        if(isset($task->title)) {
            $list->setTitle($task->title);
        }
        if(isset($task->notes)) {
            $list->setContent($task->notes);
        }
        if(isset($task->due)) {
            $list->setDeadline(new \DateTime($task->due));
        }
        if(isset($task->completed)) {
            $list->setDone(true);
        }
        $mainList = new MainList();
        $mainList->setId($taskListId);
        $list->setMainlist($mainList);
        return $list;
    }

} 