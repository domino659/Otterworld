<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\AppAuthentificator;

class UserController extends AbstractController
{
  /**
   * @param UserRepository $userRepository
   * @return Response
   * @Route("/user", name="admin_user_index")
   */
  public function index(UserRepository $userRepository): Response
  {
    $users = $userRepository->findAll();

    return $this->render('user/index.html.twig', [
      'users' => $users,
    ]);
  }

  /**
   * @param EntityManagerInterface $em
   * @param Request $request
   * @return Response
   * @Route("/user/sign-in", name="sign_in")
   */
  public function new(Request $request,
                      EntityManagerInterface $em,
                      UserPasswordHasherInterface $hasher,
                      UserAuthenticatorInterface $authenticator,
                      AppAuthentificator $appAuthenticator): Response
  {
    if ($request->isMethod('POST')) {
      if (!empty($request->request->get('password'))
        && !empty($request->request->get('password_comfirm'))
        && !empty($request->request->get('email'))
        && !empty($request->request->get('username'))) {
          if ($request->request->get('password') === $request->request->get('password_comfirm')
            && $this->isCsrfTokenValid('register_form', $request->request->get('csrf'))) {

            $user = new User();
            $user->setUsername($request->request->get('username'))
              ->setEmail($request->request->get('email'))
              ->setPassword($hasher->hashPassword($user, $request->request->get('password')))
              // TODO CreatedAt UpdatedAt default value in user Entity
              ->setCreatedAt(new \DateTime())
              ->setUpdatedAt(new \DateTime());
          
            $em->persist($user);
            $em->flush();
          return $authenticator->authenticateUser($user, $appAuthenticator, $request);
          }
          return $this->render('user/new.html.twig', ['error' => 'Passwords do not match']);
        }
        return $this->render('user/new.html.twig', ['error' => 'All fields are required']);
    }
    return $this->render('user/new.html.twig');  
  }
  
  // TODO Add update by specific value and not update everithing
  /**
   * @param User $user
   * @param EntityManagerInterface $em
   * @param Request $request
   * @return Response
   * @Route("/user/{email}/update", name="app_user_update")
   */
  public function update(User $user,
                        EntityManagerInterface $em,
                        UserPasswordHasherInterface $hasher,
                        Request $request): Response
  {
    if ($request->isMethod('POST')) {
      if (!empty($request->request->get('username'))
        && !empty($request->request->get('email'))
        && !empty($request->request->get('password'))
        && !empty($request->request->get('password_comfirm'))) {
          if ($request->request->get('password') === $request->request->get('password_comfirm')
            && $this->isCsrfTokenValid('update_form', $request->request->get('csrf'))) {

          $user->setUsername($request->request->get('username'))
            ->setEmail($request->request->get('email'))
            ->setPassword($hasher->hashPassword($user, $request->request->get('password')));
          
          $em->flush();
          
          return $this->redirectToRoute('app_user_show', ['email' => $user->getEmail()]);
        }
        return $this->render('user/update.html.twig', ['error' => 'Passwords do not match']);
      }
      return $this->render('user/update.html.twig', ['error' => 'All fields are required']);
    }
    return $this->render('user/update.html.twig', ['user' => $user]);
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

  /** @Route("/users/{id}/votes", name="app_user_votes", methods="POST")
   * @return Response
   */
  public function userVotes(User $user, Request $request, EntityManagerInterface $em): Response
  {
    $votes = $request->request->get('votes');
    if ($votes === 'up') {
      $user->upVotes();
    }
    elseif ($votes === 'down') {
      $user->downVotes();
    }

    $em->flush();
    return $this->redirectToRoute('app_user_show' , ['email' => $user->getEmail()]);
  }
}