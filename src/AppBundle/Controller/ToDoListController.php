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
        if ($request->isXMLHttpRequest()) {
            return $this->renderPartOfHomePage($request);
        }

        return $this->renderWholeHomePage($request);
    }

    /**
     * @Route("/{id}/view", name="view_todolist")
     */
    public function showAction($id, Request $request)
    {
        $toDoList = $this->getRepo()->findOneById($id);

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

        $formHandler = $this->get('todo_list_form_handler');

        if ($formHandler->handle($form, $request, $this->getUser())) {
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

    private function renderWholeHomePage(Request $request)
    {
        return $this->render('todo_list/homepage.html.twig', [
            'toDoLists' => $this->getRepo()->findByAuthor($this->getUser()),
        ]);
    }

    private function renderPartOfHomePage(Request $request)
    {
        $orderBy = $request->query->get('orderBy');
        $orderDirection = $request->query->get('orderDirection');

        $toDoLists = $this->getRepo()->findByAuthor($this->getUser(), $orderBy, $orderDirection);

        return $this->render('todo_list/homepage_ajax_part.html.twig', [
            'toDoLists' => $toDoLists,
        ]);
    }

    private function getRepo()
    {
        return $this->getDoctrine()->getRepository('AppBundle:ToDoList');
    }

    private function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }
}
