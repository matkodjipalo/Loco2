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

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepageAction(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:ToDoList');

        if ($request->isXMLHttpRequest()) {
            $orderBy = $request->query->get('orderBy');
            $orderDirection = $request->query->get('orderDirection');

            $toDoLists = $repo->findCustom($orderBy, $orderDirection);

            return $this->render('todo_list/homepage_ajax_part.html.twig', [
                'toDoLists' => $toDoLists,
            ]);
        }

        return $this->render('todo_list/homepage.html.twig', [
            'toDoLists' => $repo->findCustom(null, null),
            'search' => ''
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
            $em->flush();

            $this->addFlash('success', 'ToDoList created!');

            return $this->redirectToRoute('homepage');

        }

        return $this->render('todo_list/new.html.twig', [
            'toDoListForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete_todolist")
     */
    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:ToDoList');
        $toDoList = $repo->findOneById($id);

        if (!$toDoList) {
            throw $this->createNotFoundException();
        }

        $em->remove($toDoList);
        $em->flush();

        return $this->render('main/homepage_ajax_part.html.twig', [
            'toDoLists' => $repo->findCustom(null, null),
            'search' => ''
        ]);
    }

    /**
     * @Route("/search", name="product_search")
     */
    public function searchAction(Request $request)
    {
        $search = $request->query->get('searchTerm');

        $products = $this->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->search($search);

        return $this->render('todo_list/homepage.html.twig', [
            'products' => $products,
            'search' => $search
        ]);
    }


    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction()
    {
        return $this->render('main/admin.html.twig');
    }

    /**
     * @Route("/_db/rebuild", name="db_rebuild")
     */
    public function dbRebuildAction()
    {
        $schemaManager = $this->get('schema_manager');
        $schemaManager->rebuildSchema();
        $schemaManager->loadFixtures();

        return new JsonResponse(array(
            'success' => true
        ));
    }
}
