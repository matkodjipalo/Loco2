<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller
{
    /**
     * @Route("/{toDoListId}/tasks", name="task_list_for_todo-list")
     */
    public function ajaxListAction($toDoListId, Request $request)
    {
        if (!$request->isXMLHttpRequest()) {
            throw new \Exception("Error Processing Request", 1);
        }

        $repo = $this->getDoctrine()->getRepository('AppBundle:Task');

        $orderBy = $request->query->get('orderBy');
        $orderDirection = $request->query->get('orderDirection');

        $tasks = $repo->findBy(
            ['toDoList' => $toDoListId],
            [$orderBy => $orderDirection]
        );

        return $this->render('task/ajax_list.html.twig', [
            'tasks' => $tasks,
        ]);
    }
}
