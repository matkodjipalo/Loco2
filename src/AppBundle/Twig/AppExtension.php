<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('uncompletedTask', array($this, 'uncompletedTaskFilter')),
        );
    }

    /**
     * Filtrira nedovršene taskove
     *
     * @param  \AppBundle\Entity\Task[] $tasks
     * @return array
     */
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
