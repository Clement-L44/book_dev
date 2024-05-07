<?php

namespace App\Controller\Admin;

use App\Entity\Profil;
use App\Form\ProfilType;
use App\Traits\DateTrait;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/profil', name:'admin_profil_')]
class ProfilController extends AbstractController
{
    use DateTrait;

    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * @var ProfilRepository $profilRepository
     */
    private $profilRepository;

    public function __construct(
        EntityManagerInterface $em,
        ProfilRepository $profilRepository,
    ){
        $this->em = $em;
        $this->profilRepository = $profilRepository;
    }

    #[Route('/', name: 'list')]
    public function list(): Response
    {
        $profils = $this->profilRepository->findAll();

        return $this->render('admin/profil/list.html.twig', [
            'profils' => $profils
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request): Response|RedirectResponse
    {
        $profil = new Profil();

        $form = $this->createForm(ProfilType::class, $profil);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($profil);
            $this->em->flush();

            return $this->redirectToRoute('admin_profil_list');
        }

        return $this->render('admin/profil/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function update(Request $request, Profil $profil): Response|RedirectResponse
    {
        $form = $this->createForm(ProfilType::class, $profil);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($profil);
            $this->em->flush();
        }

        return $this->render('admin/profil/update.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Profil $profil): RedirectResponse
    {
        $this->em->remove($profil);
        $this->em->flush();

        return $this->redirectToRoute('admin_profil_list');
    }
}
