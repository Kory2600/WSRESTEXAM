<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


#[Route(name: 'app_profile_')]
class UserController extends AbstractController
{

    public function __construct(
        private UserRepository $userRepository,
    ) { }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/profil/{name}', name: 'show')]
    public function show(Request $request, string $name): Response {
        $user = $this->userRepository->findOneFullBy($name);

        if ($user === null) {
            $this->addFlash(
                'danger',
            );
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(
            UserType::class,
            $user,
            ['is_creation' => false]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->save($user, true);
            $this->addFlash(
                'success',
            );
        }

        return $this->render('back/userShow.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/login_check', name: 'register')]
    public function register(Request $request): Response {
        $form = $this->createForm(UserType::class, new User());
        $form->handleRequest($request); // => traite les $_POST du form

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->save($form->getData(), true);
            $this->addFlash(
                'success',
            );
            return $this->redirectToRoute('app_home');
        }

        return $this->render('back/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
