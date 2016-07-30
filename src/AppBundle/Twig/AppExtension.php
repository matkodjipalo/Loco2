<?php

namespace AppBundle\Twig;

use AppBundle\Entity\ToDoList;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('uncompletedTask', array($this, 'uncompletedTaskFilter')),
        );
    }

    public function uncompletedTaskFilter($tasks = [])
    {
        $uncompletedTasks = [];
        foreach ($tasks as $task) {
            if ($task->getIsCompleted() !== true) {
                $uncompletedTasks[] = $task;
            }
        }

        return $uncompletedTasks;
    }

    public function getName()
    {
        return 'app_extension';
    }
}