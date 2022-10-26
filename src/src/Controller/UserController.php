<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\AppAuthentificator;
use App\Form\UserNewFormType;
use App\Form\UserUpdateFormType;

class UserController extends AbstractController
{
  /**
   * @param UserRepository $userRepository
   * @return Response
   * @Route("/user", name="admin_user_index")
   */
  public function index(UserRepository $userRepository, Request $request, PaginatorInterface $paginator): Response
  // {
  //   $users = $userRepository->findAll();

  //   return $this->render('user/index.html.twig', [
  //     'users' => $users,
  //   ]);
  // }

  {
    $search = $request->query->get('u');
    $users = $userRepository->findAllAskedUserByAlphabeticalOrderPaginate();
    // dd($users);
    $pagination = $paginator->paginate(
      $users, /* query NOT result */
      $request->query->getInt('page', 1), /*page number*/
      10 /*limit per page*/
    );
    // dd($pagination);
    return $this->render('user/index.html.twig', [
      'pagination' => $pagination
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
      $form = $this->createForm(UserNewFormType::class);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        /** @var $user User */
        $user = $form->getData();

        $user->setPassword($hasher->hashPassword($user, $form['password']->getData()))
        ->setCreatedAt(new \DateTime())
        ->setUpdatedAt(new \DateTime());
        
        $em->persist($user);
        $em->flush();
        
        return $authenticator->authenticateUser($user, $appAuthenticator, $request);
        }
        return $this->render('user/new.html.twig', [
          'userForm' => $form->createView(),
        ]);	
    }
  
  // TODO Add update by specific value and not update everithing will correct duplicate email error
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
    $form = $this->createForm(UserUpdateFormType::class);
    
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      /** @var $user User */
      $data = $form->getData();

      $plainPassword = $form['password']->getData();
      $user->setEmail($form['email']->getData())
           ->setUsername($form['username']->getData())
           ->setPassword($hasher->hashPassword($user, $form['password']->getData()))
           ->setUpdatedAt(new \DateTime());
           
      $em->flush();
      
      return $this->redirectToRoute('app_user_show', ['email' => $user->getEmail()]);
      }
    return $this->render('user/update.html.twig', [
      'userForm' => $form->createView(),
    ]);	
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
    
    return $this->redirectToRoute('index');
  }

  /**
   * @param User $user
   * @return Response
   * @Route("/user/{email}", name="app_user_show")*
   * @IsGranted("USER_VIEW", subject="user")
   */
  public function show(User $user) : Response
  {
    // $this->denyAccessUnlessGranted('USER_VIEW', $user);

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