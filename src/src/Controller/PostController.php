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
  
  /**
   * @return Response
   * @Route("/user/new", name="app_user_new")
   */
  public function new(): Response
  {
    return $this->render('post/new.html.twig');
  }
  
    /**
   * @param EntityManagerInterface $em
   * @param Request $request
   * @return Response
   * @Route("/user/create", name="app_user_create", methods="POST")
   */
  public function create(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $hasher): Response
  {
    $loggedUser = $this->getUser();

    $post = new User();
    $user->setUsername($request->request->get('username'))
      ->setEmail($request->request->get('email'))
      ->setPassword($hasher->hashPassword($user, 'password'))
      ->setIsAdmin(false)
      ->setCreatedAt(new \DateTime())
      ->setUpdatedAt(new \DateTime());

    $em->persist($user);
    $em->flush();

    return $this->redirectToRoute('app_user_show', ['email' => $user->getEmail()]);
  }
}
