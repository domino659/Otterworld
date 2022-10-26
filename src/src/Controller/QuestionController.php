<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Question;
use App\Repository\QuestionRepository;

use App\Service\MarkdownHelper;

class QuestionController extends AbstractController
{
  /**
   * @param EntytyManagerInterface $em
   * @return Response
   * @Route("/question", name="app_question_index")
   */
  public function index(QuestionRepository $questionRepository): Response
  {
    $questions = $questionRepository->findAll();
    return $this->render('question/index.html.twig', [
      'questions' => $questions,
    ]);
  }
}