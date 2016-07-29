<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="todo_list")
 */
class ToDoList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $author;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="toDoList")
     */
    private $toDoList;

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
     * Set name
     *
     * @param string $name
     *
     * @return ToDoList
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set author
     *
     * @param \AppBundle\Entity\User $author
     *
     * @return ToDoList
     */
    public function setAuthor(\AppBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \AppBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->toDoList = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add toDoList
     *
     * @param \AppBundle\Entity\Task $toDoList
     *
     * @return ToDoList
     */
    public function addToDoList(\AppBundle\Entity\Task $toDoList)
    {
        $this->toDoList[] = $toDoList;

        return $this;
    }

    /**
     * Remove toDoList
     *
     * @param \AppBundle\Entity\Task $toDoList
     */
    public function removeToDoList(\AppBundle\Entity\Task $toDoList)
    {
        $this->toDoList->removeElement($toDoList);
    }

    /**
     * Get toDoList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getToDoList()
    {
        return $this->toDoList;
    }
}
