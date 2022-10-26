<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

class ApiController extends AbstractController
{
  /**
   * @Route("/api", name="api")
   */
  public function index(UserRepository $UserRepository): Response
  {
    // return $this->json(['message' => 'Welcome to your new controller!']);

    $users = $UserRepository->findAllAskedUserByNewest();
    return $this->json($users, 200, [], ['groups' => ['main']]);
  }
}