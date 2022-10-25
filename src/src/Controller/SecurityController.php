<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractController
{
  /**
   * @Route("/login", name="app_login")
   */
  public function login()
  {
    return $this->render('security/login.html.twig', ['controller_name' => 'SecurityController']);
  }
}