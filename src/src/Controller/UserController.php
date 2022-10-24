<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;
use App\Repository\UserRepository;

class UserController extends AbstractController
{
  /**
   * @param UserRepository $userRepository
   * @return Response
   * @Route("/user", name="app_user_index")
   */
  public function index(UserRepository $userRepository): Response
  {
    $users = $userRepository->findAll();

    return $this->render('user/index.html.twig', [
      'users' => $users,
    ]);
  }

  /**
   * @return Response
   * @Route("/user/sign-in", name="app_user_new")
   */
  public function new(): Response
  {
    return $this->render('user/sign-in.html.twig');
  }
  
  /**
   * @param EntityManagerInterface $em
   * @param Request $request
   * @return Response
   * @Route("/user/create", name="app_user_create", methods="POST")
   */
  public function create(EntityManagerInterface $em, Request $request): Response
  {
    $user = new User();
    $user->setPseudo($request->request->get('pseudo'))
      ->setEmail($request->request->get('email'))
      ->setPassword($request->request->get('password'));

    $em->persist($user);
    $em->flush();

    return $this->redirectToRoute('app_user_show', ['email' => $user->getEmail()]);
  }
  
  /**
   * @param User $user
   * @return Response
   * @Route("/user/{email}/modify", name="app_user_modify")
   */
  public function modify(User $user): Response
  {
    return $this->render('user/modify.html.twig', [
      'user' => $user,
    ]);
  }
  
  /**
   * @param User $user
   * @param EntityManagerInterface $em
   * @param Request $request
   * @return Response
   * @Route("/user/{email}/update", name="app_user_update", methods="POST")
   */
  public function update(User $user, EntityManagerInterface $em, Request $request): Response
  {
    $user->setPseudo($request->request->get('pseudo'))
      ->setEmail($request->request->get('email'))
      ->setPassword($request->request->get('password'));
      // ->setIsAdmin($request->request->get('is_admin'))
      // ->setVote($request->request->get('vote'));

    $em->flush();

    return $this->redirectToRoute('app_user_show', ['email' => $user->getEmail()]);
  }
  
  /**
   * @param User $user
   * @param EntityManagerInterface $em
   * @return Response
   * @Route("/user/{email}/delete", name="app_user_delete")
   */
  public function delete(User $user, EntityManagerInterface $em): Response
  {
    $em->remove($user);
    $em->flush();
    
    return $this->redirectToRoute('app_user_index');
  }

  /**
   * @param User $user
   * @return Response
   * @Route("/user/{email}", name="app_user_show")
   */
  public function show(User $user) : Response
  {
    return $this->render('user/show.html.twig', [
      'user' => $user,
    ]);
  }

  /** @Route("/users/{id}/vote", name="app_user_vote", methods="POST")
   * @return Response
   */
  public function userVote(User $user, Request $request, EntityManagerInterface $em): Response
  {
    $vote = $request->request->get('vote');
    // dd($vote);
    if ($vote === 'up') {
      // dd($user);
      $user->upVote();
    }
    elseif ($vote === 'down') {
      // dd($user);
      $user->downVote();
    }

    $em->flush();
    // dd($user);
    return $this->redirectToRoute('app_user_show' , ['email' => $user->getEmail()]);
  }
}