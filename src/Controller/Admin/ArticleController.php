<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Traits\DateTrait;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/article', name:'admin_article_')]
class ArticleController extends AbstractController
{
    use DateTrait;

    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * @var ArticleRepository $articleRepository
     */
    private $articleRepository;

    public function __construct(
        EntityManagerInterface $em,
        ArticleRepository $articleRepository,
    ){
        $this->em = $em;
        $this->articleRepository = $articleRepository;
    }

    #[Route('/', name: 'list')]
    public function list(): Response
    {
        $articles = $this->articleRepository->findAll();

        return $this->render('admin/article/list.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request): Response|RedirectResponse
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $article->setCreatedAt($this->now());

            $this->em->persist($article);
            $this->em->flush();

            return $this->redirectToRoute('admin_article_list');
        }

        return $this->render('admin/article/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function update(Request $request, Article $article): Response|RedirectResponse
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $article->setUpdatedAt($this->now());

            $this->em->persist($article);
            $this->em->flush();
        }

        return $this->render('admin/article/update.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Article $article): RedirectResponse
    {
        $this->em->remove($article);
        $this->em->flush();

        return $this->redirectToRoute('admin_article_list');
    }
}
