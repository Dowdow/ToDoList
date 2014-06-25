<?php

namespace LeoAndLeo\GoogleBundle\Service;

use Google_Service_Tasks;
use HappyR\Google\ApiBundle\Services\GoogleClient;
use LeoAndLeo\ToDoListBundle\Entity\MainList;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\SecurityContext;

class MainListClient {

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
     * Get all the user tasks list
     */
    public function getAll() {
        $mainlists = array();
        $tasks = $this->service->tasklists->listTasklists()->getItems();
        var_dump($tasks);
        foreach($tasks as $key => $value) {
            $mainlist = new MainList();
            $mainlist->setId($value->id);
            $mainlist->setTitle($value->title);
            $mainlists[$key] = $mainlist;
        }

        return $mainlists;
    }

    public function get($id) {

    }

    public function insert() {

    }

    public function update($id) {

    }

    public function delete($id) {

    }

} 