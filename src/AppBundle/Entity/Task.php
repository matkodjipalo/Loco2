<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="task")
 */
class Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ToDoList", inversedBy="tasks")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $toDoList;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $priority;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $deadlineDt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCompleted = false;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set priority.
     *
     * @param string $priority
     *
     * @return Task
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority.
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set deadlineDt.
     *
     * @param \DateTime $deadlineDt
     *
     * @return Task
     */
    public function setDeadlineDt($deadlineDt)
    {
        $this->deadlineDt = $deadlineDt;

        return $this;
    }

    /**
     * Get deadlineDt.
     *
     * @return \DateTime
     */
    public function getDeadlineDt()
    {
        return $this->deadlineDt;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Task
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set toDoList.
     *
     * @param \AppBundle\Entity\ToDoList $toDoList
     *
     * @return Task
     */
    public function setToDoList(\AppBundle\Entity\ToDoList $toDoList = null)
    {
        $this->toDoList = $toDoList;

        return $this;
    }

    /**
     * Get toDoList.
     *
     * @return \AppBundle\Entity\ToDoList
     */
    public function getToDoList()
    {
        return $this->toDoList;
    }

    /**
     * Set isCompleted.
     *
     * @param bool $isCompleted
     *
     * @return Task
     */
    public function setIsCompleted($isCompleted)
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    /**
     * Get isCompleted.
     *
     * @return bool
     */
    public function getIsCompleted()
    {
        return $this->isCompleted;
    }
}
