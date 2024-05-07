<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Form\TagType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/tag', name:'admin_tag_')]
class TagController extends AbstractController
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * @var TagRepository $tagRepository
     */
    private $tagRepository;

    public function __construct(
        EntityManagerInterface $em,
        TagRepository $tagRepository,
    ){
        $this->em = $em;
        $this->tagRepository = $tagRepository;
    }

    #[Route('/', name: 'list')]
    public function list(): Response
    {
        $tags = $this->tagRepository->findAll();

        return $this->render('admin/tag/list.html.twig', [
            'tags' => $tags
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request): Response|RedirectResponse
    {
        $tag = new Tag();

        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            

            $this->em->persist($tag);
            $this->em->flush();

            return $this->redirectToRoute('admin_tag_list');
        }

        return $this->render('admin/tag/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function update(Request $request, Tag $tag): Response|RedirectResponse
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($tag);
            $this->em->flush();
        }

        return $this->render('admin/tag/update.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Tag $tag): RedirectResponse
    {
        $this->em->remove($tag);
        $this->em->flush();

        return $this->redirectToRoute('admin_tag_list');
    }
}
