<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\UserRepository;

class SearchController extends AbstractController
{
    /**
     * @Route("/search_user", name="search_user")
     */
    public function search_user(UserRepository $userRepository, Request $request): Response
    {
        $search = $request->query->get('s');
        $users = $userRepository->searchByName($search);
        
        return $this->render('user/index.html.twig', [
            'search_user' => $users,
        ]);
    }
}
