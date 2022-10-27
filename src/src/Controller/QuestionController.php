<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\Repository\PostRepository;
use App\Form\QuestionNewType;

class QuestionController extends AbstractController
{
  /**
   * @param EntytyManagerInterface $em
   * @return Response
   * @Route("/question", name="admin_question_index")
   */
  public function index(QuestionRepository $questionRepository): Response
  {
    $questions = $questionRepository->findAll();
    return $this->render('question/index.html.twig', [
      'questions' => $questions,
    ]);
  }

  /**
   * @param EntityManagerInterface $em
   * @param Request $request
   * @return Response
   * @Route("/question/{id}/new", name="app_question_new")
   */
  public function new(int $id, Request $request,
                      EntityManagerInterface $em,
                      PostRepository $postRepository): Response
  {
    $form = $this->createForm(QuestionNewType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $post = $postRepository->findPostById($id);
      /** @var $question Question */

      $question = $form->getData();
      $question->setUser($this->getUser())
               ->setPost($post)
               ->setCreatedAt(new \DateTime())
               ->setUpdatedAt(new \DateTime());
      $em->persist($question);
      $em->flush();
      
      return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
    }
    return $this->render('question/new.html.twig', [
      'questionForm' => $form->createView(),
    ]);	
  }
}