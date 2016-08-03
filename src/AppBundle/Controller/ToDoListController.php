<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\ToDoListFormType;
use AppBundle\Entity\Task;
use AppBundle\Entity\ToDoList;
use Doctrine\Common\Collections\ArrayCollection;

class ToDoListController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepageAction(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:ToDoList');
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($request->isXMLHttpRequest()) {
            $orderBy = $request->query->get('orderBy');
            $orderDirection = $request->query->get('orderDirection');

            $toDoLists = $repo->findByAuthor($user, $orderBy, $orderDirection);

            return $this->render('todo_list/homepage_ajax_part.html.twig', [
                'toDoLists' => $toDoLists,
            ]);
        }

        return $this->render('todo_list/homepage.html.twig', [
            'toDoLists' => $repo->findByAuthor($user),
        ]);
    }

    /**
     * @Route("/{id}/view", name="view_todolist")
     */
    public function showAction($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:ToDoList');
        $toDoList = $repo->findOneById($id);

        if (!$toDoList) {
            throw $this->createNotFoundException();
        }

        return $this->render('todo_list/view.html.twig', [
            'toDoList' => $toDoList
        ]);
    }

    /**
     * @Route("/new", name="new_todolist")
     */
    public function newAction(Request $request)
    {
        $toDoList = new ToDoList();
        $form = $this->createForm(ToDoListFormType::class, $toDoList);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $toDoList = $form->getData();
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $toDoList->setAuthor($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($toDoList);
            foreach ($toDoList->getTasks() as $task) {
                $task->setToDoList($toDoList);
                $em->persist($task);
            }

            $em->flush();

            $this->addFlash('success', 'ToDoList created!');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('todo_list/new.html.twig', [
            'toDoListForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit_todolist")
     */
    public function editAction($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:ToDoList');
        $toDoList = $repo->findOneById($id);

        if (!$toDoList) {
            throw $this->createNotFoundException();
        }

        $originalTasks = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($toDoList->getTasks() as $task) {
            $originalTasks->add($task);
        }

        $editForm = $this->createForm(ToDoListFormType::class, $toDoList);

        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // remove the relationship between the tag and the Task
            foreach ($originalTasks as $task) {
                if (false === $toDoList->getTasks()->contains($task)) {
                    $task->setToDoList(null);
                    $em->remove($task);
                }
            }

            $toDoList = $editForm->getData();
            
            foreach ($toDoList->getTasks() as $task) {
                $task->setToDoList($toDoList);
                $em->persist($task);
            }
            $toDoList->removeTasks();
            $em->persist($toDoList);
            $em->flush();

            $this->addFlash('success', 'ToDoList edited!');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('todo_list/edit.html.twig', [
            'toDoListEditForm' => $editForm->createView()
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete_todolist")
     */
    public function deleteAction($id, Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:ToDoList');
        $toDoList = $repo->findOneById($id);

        if (!$toDoList) {
            throw $this->createNotFoundException();
        }

        $em->remove($toDoList);
        $em->flush();

        return $this->render('todo_list/homepage_ajax_part.html.twig', [
            'toDoLists' => $repo->findByAuthor($user),
            'search' => ''
        ]);
    }
}
