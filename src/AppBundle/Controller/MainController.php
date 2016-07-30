<?php

namespace AppBundle\Controller;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepageAction(Request $request)
    {
        $orderBy = $request->query->get('orderBy');
        $orderDirection = $request->query->get('orderDirection');

        $toDoLists = $this->getDoctrine()
            ->getRepository('AppBundle:ToDoList')
            ->findCustom($orderBy, $orderDirection);

        return $this->render('main/homepage.html.twig', [
            'toDoLists' => $toDoLists,
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

        return $this->render('main/homepage.html.twig', [
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
