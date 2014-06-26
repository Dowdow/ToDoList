<?php

namespace LeoAndLeo\ToDoListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * List
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="LeoAndLeo\ToDoListBundle\Entity\Repository\MainListRepository")
 */
class MainList {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="LeoAndLeo\ToDoListBundle\Entity\ItemList", mappedBy="mainlist", cascade={"remove"})
     */
    private $itemlists;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->itemlists = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param $id string
     * @return MainList
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return MainList
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Add itemlists
     *
     * @param \LeoAndLeo\ToDoListBundle\Entity\ItemList $itemlists
     * @return MainList
     */
    public function addItemlist(ItemList $itemlists)
    {
        $this->itemlists[] = $itemlists;
        $itemlists->setMainlist($this);
        return $this;
    }

    /**
     * Remove itemlists
     *
     * @param \LeoAndLeo\ToDoListBundle\Entity\ItemList $itemlists
     */
    public function removeItemlist(ItemList $itemlists)
    {
        $this->itemlists->removeElement($itemlists);
    }

    /**
     * Get itemlists
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItemlists()
    {
        return $this->itemlists;
    }
}
