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

  /**
   * @Route("/logout", name="app_logout")
   */
  public function logout(): void
  {
    // throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
  }
}