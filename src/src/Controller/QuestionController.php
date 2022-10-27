<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\Repository\PostRepository;
use App\Form\QuestionNewType;
use App\Form\QuestionUpdateType;

class QuestionController extends AbstractController
{
  /**
   * @param EntytyManagerInterface $em
   * @return Response
   * @Route("/question", name="admin_question_index")
   */
  public function index(QuestionRepository $questionRepository,
                   Request $request,
                   PaginatorInterface $paginator): Response
  {
    $search = $request->query->get('q');
    $questions = $questionRepository->findAllAskedQuestionByCreatedAtOrderPaginate();
    $pagination = $paginator->paginate(
      $questions,
      $request->query->getInt('page', 1),
      10
    );

    return $this->render('question/index.html.twig', [
      'pagination' => $pagination
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
    $post = $postRepository->findPostById($id);
    $question = new Question();
    $form = $this->createForm(QuestionNewType::class, $question);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
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

  /**
   * @param Question $question
   * @param EntityManagerInterface $em
   * @param Request $request
   * @return Response
   * @Route("/question/{id}/update", name="app_question_update")
   */
  public function update(int $id,
                         Question $question,
                         EntityManagerInterface $em,
                         Request $request): Response
  {
    $question =  $em->getRepository(Question::class)->findOneBy(['id' => $id]);
    $form = $this->createForm(QuestionUpdateType::class, $question);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em->flush();
      // dd($question);
      return $this->redirectToRoute('app_post_show', ['id' => $question->getPost()->getId()]);
    }
    return $this->render('question/update.html.twig', [
      'questionForm' => $form->createView(),
      'question' => $question,
    ]);
  }

  /**
   * @param Question $question
   * @param EntityManagerInterface $em
   * @return Response
   * @Route("/question/{id}/delete", name="app_question_delete")
   */
  public function delete(Question $question, EntityManagerInterface $em): Response
  {
    $em->remove($question);
    $em->flush();
    
    return $this->redirectToRoute('app_post_show', ['id' => $question->getPost()->getId()]);
  }
}