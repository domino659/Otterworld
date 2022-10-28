<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

use App\Entity\Post;
use App\Repository\PostRepository;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(): Response
    {
        return $this->render('index/homepage.html.twig');
    }
    
    /**
     * @param EntytyManagerInterface $em
     * @return Response
     * @Route("/index", name="index")
     */
    public function index(PostRepository $postRepository,
    Request $request,
    PaginatorInterface $paginator): Response
    {
        $search = $request->query->get('p');
        $posts = $postRepository->findAllAskedPostByCreatedAtOrderPaginate();
        $pagination = $paginator->paginate(
            $posts,
            $request->query->getInt('page', 1),
            5
        );
        
        return $this->render('index/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/law", name="law")
     */
    public function law(): Response
    {
        return $this->render('index/law.html.twig');
    }
}