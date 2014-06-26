<?php

namespace LeoAndLeo\GoogleBundle\Service;

use Google_Service_Tasks;
use HappyR\Google\ApiBundle\Services\GoogleClient;
use LeoAndLeo\ToDoListBundle\Entity\MainList;
use Symfony\Component\Security\Core\SecurityContext;

class MainListClient {

    /** @var Google_Service_Tasks the Service task class */
    private $service;

    /** @var ItemListClient the Item List Client service */
    private $itemListClient;

    /**
     * Constructor
     * @param $client GoogleClient
     * @param $security SecurityContext
     * @param $itemListClient
     */
    public function __construct($client, $security, $itemListClient) {
        $this->itemListClient = $itemListClient;
        $token = $security->getToken()->getUser();
        $googleClient = $client->getGoogleClient();
        $googleClient->setAccessToken($token);

        $this->service = new Google_Service_Tasks($googleClient);
    }

    /**
     * Get all the user tasks list
     * @return array
     */
    public function getAll() {
        $mainlists = array();
        $tasks = $this->service->tasklists->listTasklists()->getItems();
        foreach($tasks as $key => $value) {
            $mainlists[$key] = $this->buildTaskList($value);
            $itemlists = $this->itemListClient->getAll($mainlists[$key]->getId());
            foreach($itemlists as $item) {
                $mainlists[$key]->addItemlist($item);
            }
        }

        return $mainlists;
    }

    /**
     * Get a user task list
     * @param $id string the id of the task list
     * @return MainList
     */
    public function get($id) {
        $taskList = $this->service->tasklists->get($id);
        $taskList = $this->buildTaskList($taskList);
        $itemlists = $this->itemListClient->getAll($taskList->getId());
        foreach($itemlists as $item) {
            $taskList->addItemlist($item);
        }
        return $taskList;
    }

    /**
     * Add a user task list
     * @param $mainList MainList
     * @return MainList
     */
    public function insert($mainList) {
        $taskList = new \Google_Service_Tasks_TaskList();
        $taskList->setKind('tasks#taskList');
        $taskList->setTitle($mainList->getTitle());
        $date = new \DateTime();
        $date->format(\DateTime::RFC3339);
        $taskList->setUpdated($date);

        $taskList = $this->service->tasklists->insert($taskList);
        return $this->buildTaskList($taskList);
    }

    /**
     * Edit a user task list
     * @param $mainList MainList
     * @return MainList
     */
    public function update($mainList) {
        $taskList = new \Google_Service_Tasks_TaskList();
        $taskList->setKind('tasks#taskList');
        $taskList->setId($mainList->getId());
        $taskList->setTitle($mainList->getTitle());
        $date = new \DateTime();
        $date->format(\DateTime::RFC3339);
        $taskList->setUpdated($date);

        $taskList = $this->service->tasklists->update($mainList->getId(),$taskList);
        return $this->buildTaskList($taskList);
    }

    /**
     * Remove a user task list
     * @param $id string the id of the task list
     */
    public function delete($id) {
        $this->service->tasklists->delete($id);
    }

    /**
     * Convert the json Google return into MainList object
     * @param $taskList string
     * @return MainList
     */
    private function buildTaskList($taskList) {
        $list = new MainList();
        if(isset($taskList->id)){
            $list->setId($taskList->id);
        }
        if(isset($taskList->title)) {
            $list->setTitle($taskList->title);
        }
        return $list;
    }

} 