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
use App\Form\PostNewType;

class PostController extends AbstractController
{
  /**
   * @param EntytyManagerInterface $em
   * @return Response
   * @Route("/post", name="admin_post_index")
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
      10
    );

    return $this->render('post/index.html.twig', [
      'pagination' => $pagination
    ]);
  }

  /**
   * @param EntityManagerInterface $em
   * @param Request $request
   * @return Response
   * @Route("/post/new", name="app_post_new")
   */
  public function new(Request $request,
                      EntityManagerInterface $em): Response
  {
    $form = $this->createForm(PostNewType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        /** @var $post Post */
        $post = $form->getData();
        $post->setUser($this->getUser())
             ->setCreatedAt(new \DateTime())
             ->setUpdatedAt(new \DateTime());
        $em->persist($post);
        $em->flush();
      
        return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
    }
    return $this->render('post/new.html.twig', [
      'postForm' => $form->createView(),
    ]);	
  }
  
  /**
   * @param Post $post
   * @return Response
   * @Route("/post/{id}", name="app_post_show")
   */
  public function show(Post $post): Response
  {
    return $this->render('post/show.html.twig', [
      'post' => $post,
    ]);
  }
}

