<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\UserRepository;
use App\Repository\PostRepository;
use App\Repository\QuestionRepository;

class SearchController extends AbstractController
{
    /**
     * @Route("/search_user", name="search_user")
     */
    public function search_user(UserRepository $userRepository, Request $request): Response
    {
        $search = $request->query->get('u');
        $users = $userRepository->searchByName($search);
        
        return $this->render('user/index.html.twig', [
            'search_user' => $users,
        ]);
    }

    /**
     * @Route("/search_post", name="search_post")
     */
    public function search_post(PostRepository $postRepository, Request $request): Response
    {
        $search = $request->query->get('p');
        $posts = $postRepository->searchByTitle($search);
        
        return $this->render('post/index.html.twig', [
            'search_post' => $posts,
        ]);
    }

    /**
     * @Route("/search_question", name="search_question")
     */
    public function search_question(QuestionRepository $questionRepository, Request $request): Response
    {
        $search = $request->query->get('q');
        $questions = $questionRepository->searchByTitle($search);
        
        return $this->render('question/index.html.twig', [
            'search_question' => $questions,
        ]);
    }
}
