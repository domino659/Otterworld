<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
  /**
   * @Route("/api", name="api_page")
   */
  public function index(): Response
  {
    return $this->json(['message' => 'Welcome to your new controller!']);
  }
}