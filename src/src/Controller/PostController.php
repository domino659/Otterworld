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
use App\Form\PostUpdateType;

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
    $post = new Post();
    $form = $this->createForm(PostNewType::class, $post);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
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
   * @param EntityManagerInterface $em
   * @param Request $request
   * @return Response
   * @Route("/post/{id}/update", name="app_post_update")
   */
  public function update(int $id,
                         Post $post,
                         EntityManagerInterface $em,
                         Request $request): Response
  {
    $post =  $em->getRepository(Post::class)->findOneBy(['id' => $id]);
    $form = $this->createForm(PostUpdateType::class, $post);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em->flush();

      return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
    }
    return $this->render('post/update.html.twig', [
      'postForm' => $form->createView(),
      'post' => $post,
    ]);
  }

  /**
   * @param Post $post
   * @param EntityManagerInterface $em
   * @return Response
   * @Route("/post/{id}/delete", name="app_post_delete")
   */
  public function delete(Post $post, EntityManagerInterface $em): Response
  {
    $em->remove($post);
    $em->flush();
    
    return $this->redirectToRoute('index');
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

