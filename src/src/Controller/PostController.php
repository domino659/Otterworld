<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Entity\User;
use App\Entity\Post;
use App\Repository\PostRepository;
use App\Form\PostType;
use App\Service\UploadHelper;

class PostController extends AbstractController
{
  /**
   * @param EntytyManagerInterface $em
   * @return Response
   * @Route("app/post", name="post_index")
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
   * @Route("app/post/new", name="app_post_new")
   */
  public function new(Request $request,
                      EntityManagerInterface $em,
                      UploadHelper $helper): Response
  {
    $post = new Post();
    $form = $this->createForm(PostType::class, $post);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {      
      $post->setUser($this->getUser())
      ->setCreatedAt(new \DateTime())
      ->setUpdatedAt(new \DateTime());
      $newImage = $form['imageFile']->getData();
      if ($newImage) {
        $fileName = $helper->uploadPostImage($newImage);
        $post->setImageFilename($fileName);
      }
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
   * @Route("app/post/{id}/update", name="app_post_update")
   */
  public function update(int $id,
                         Post $post,
                         EntityManagerInterface $em,
                         Request $request,
                         UploadHelper $helper): Response
  {
    $post =  $em->getRepository(Post::class)->findOneBy(['id' => $id]);
    $form = $this->createForm(PostType::class, $post);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      /** @var $port Post */
      $data = $form->getData();      
      $newImage = $form['imageFile']->getData();
      if ($newImage) {
        $fileName = $helper->uploadPostImage($newImage);
        $post->setImageFilename($fileName);
      }
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
   * @Route("app/post/{id}/delete", name="app_post_delete")
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
   * @Route("app/post/{id}", name="app_post_show")
   */
  public function show(Post $post): Response
  {
    return $this->render('post/show.html.twig', [
      'post' => $post,
    ]);
  }
}


