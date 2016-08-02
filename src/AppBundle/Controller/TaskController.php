<?php

namespace AppBundle\Controller;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\ToDoListFormType;

use AppBundle\Entity\Task;
use AppBundle\Entity\ToDoList;
use Doctrine\Common\Collections\ArrayCollection;

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
        $user = $this->get('security.token_storage')->getToken()->getUser();

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
