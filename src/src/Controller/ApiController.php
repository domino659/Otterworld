<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

class ApiController extends AbstractController
{
  /**
   * @Route("/api/users", name="api")
   */
  public function index(UserRepository $userRepository): Response
  {
    $users = $userRepository->findAllAskedUserByAlphabeticalOrder();
    return $this->json($users, 200, [], ['groups' => ['main']]);
  }
}