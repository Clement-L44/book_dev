<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Traits\DateTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/user', name:'admin_user_')]
class UserController extends AbstractController
{
    use DateTrait;

    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
    ){
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    #[Route('/', name: 'list')]
    public function list(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('admin/user/list.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request): Response|RedirectResponse
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user->setCreatedAt($this->now());

            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function update(Request $request, User $user): Response|RedirectResponse
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user->setUpdatedAt($this->now());

            $this->em->persist($user);
            $this->em->flush();
        }

        return $this->render('admin/user/update.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(User $user): RedirectResponse
    {
        $this->em->remove($user);
        $this->em->flush();

        return $this->redirectToRoute('admin_user_list');
    }
}
