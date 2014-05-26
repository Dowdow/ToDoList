<?php

namespace LeoAndLeo\ToDoListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ItemList
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="LeoAndLeo\ToDoListBundle\Entity\Repository\ItemListRepository")
 */
class ItemList
{
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
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deadline", type="datetime")
     */
    private $deadline;

    /**
     * @var boolean
     *
     * @ORM\Column(name="done", type="boolean")
     */
    private $done;

    /**
     * @ORM\ManyToOne(targetEntity="LeoAndLeo\ToDoListBundle\Entity\MainList", inversedBy="itemlists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mainlist;

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
     * @return ItemList
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
     * Set content
     *
     * @param string $content
     * @return ItemList
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set deadline
     *
     * @param \DateTime $deadline
     * @return ItemList
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Set done
     *
     * @param boolean $done
     * @return ItemList
     */
    public function setDone($done)
    {
        $this->done = $done;

        return $this;
    }

    /**
     * Get done
     *
     * @return boolean
     */
    public function getDone()
    {
        return $this->done;
    }

    /**
     * Set mainlist
     *
     * @param \LeoAndLeo\ToDoListBundle\Entity\MainList $mainlist
     * @return ItemList
     */
    public function setMainlist(\LeoAndLeo\ToDoListBundle\Entity\MainList $mainlist)
    {
        $this->mainlist = $mainlist;

        return $this;
    }

    /**
     * Get mainlist
     *
     * @return \LeoAndLeo\ToDoListBundle\Entity\MainList
     */
    public function getMainlist()
    {
        return $this->mainlist;
    }
}
