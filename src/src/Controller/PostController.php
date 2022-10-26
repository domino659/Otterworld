<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Post;
use App\Repository\PostRepository;

class PostController extends AbstractController
{
  /**
   * @param EntytyManagerInterface $em
   * @return Response
   * @Route("/post", name="app_post_index")
   */
  public function index(PostRepository $postRepository): Response
  {
    $posts = $postRepository->findAll();
    return $this->render('post/index.html.twig', [
      'posts' => $posts,
    ]);
  }

    /**
   * @param Post $post
   * @return Response
   * @Route("/post/{id}", name="app_post_show")
   */
  public function show(Post $post) : Response
  {
    return $this->render('post/show.html.twig', [
      'post' => $post,
    ]);
  }
  
}
