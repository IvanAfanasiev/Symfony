<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\ArticleService;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Image;

class BlogController extends AbstractController
{
    public function __construct(
        private ArticleRepository $articleRepository,
        private ArticleService $articleService,
    ){}

    #[Route('/main', name:'MainPage')]   
    public function MainPage(): Response{
        $newArts = $this->articleRepository->findAll();
        // dump($newArt);
        $parameters = [];
        if($newArts){
            $parameters = $this->articleService->transformDataForTwig($newArts);
        }

        return $this->render('blog/articles.html.twig', ['articles'=>$parameters]);//['articles'=>$newArts]);
        // return new Response("mainPage");
    }

    #[Route('/article/{id}', name:'ArticlePage')]   
    public function getOneArtcile(int $id) : Response{
        $article = $this->articleRepository->find($id);
        return $this->render('article.html.twig', ['article'=>$article, 'images' => $article->getImages()->getValues()]);
        
    }

    #[Route('/last', name:'LastArticle')]   
    public function getLastArtcile() : Response{
        $newArt = $this->articleRepository->getLast();
        return $this->render('article.html.twig', ['article'=>$newArt]);
    }
    #[Route('/search', name:'FindArticle', methods: ['GET'])]   
    public function findArtcile(Request $request) : Response{

        // GET from search form
        $value=$request->query->get('search-query');

        $newArts = $this->articleRepository->findOne($value);
        $parameters = [];
        if($newArts){
            $parameters = $this->articleService->transformDataForTwig($newArts);
        }

        return $this->render('blog/articles.html.twig', ['articles'=>$parameters]);
    }

    //CREATE
    #[Route('/add', name:'createArticle', methods: ['POST'])]   
    public function artcileCreator(Request $request) : Response{
        $article = new Article();

        if ($this->articleService->handleArticleForm($request, $article)) {
            return $this->redirectToRoute('ArticlePage' , ['id' => $article->getId()]);
        }

        return new Response(null, Response::HTTP_BAD_REQUEST);
    }
    #[Route('/add', name:'NewArticle', methods: ['GET'])]   
    public function createArtcileForm(Request $request) : Response{
        $form = $this->articleService->createArticleForm(new Article());
        return $this->render('addArticle.html.twig', ['form' => $form->createView()]);
    }

    //EDIT
    #[Route('/article/{id}/edit', name: 'editArticleForm', methods: ['GET'])]
    public function editArticleForm(Int $id): Response
    {
        $article = $this->articleRepository->find($id);
        $form = $this->articleService->createArticleForm($article);

        return $this->render('editArticle.html.twig', ['form' => $form->createView(), 'article' => $article, 'article' => $article, 'images' => $article->getImages()->getValues()]);
    }
    #[Route('/article/{id}/edit', name: 'editArticle', methods: ['POST'])]
    public function articleEditor(Request $request, Int $id): Response
    {
        $article = $this->articleRepository->find($id);

        if ($this->articleService->handleArticleForm($request, $article)) {
            return $this->redirectToRoute('ArticlePage', ['id' => $article->getId()]);
        }

        return $this->redirectToRoute('editArticleForm', ['id' => $article->getId()]);
    }

    //DELETE
    #[Route('article/{id}/delete', name: 'deleteArticle')]
    public function deleteArticle(Int $id, EntityManagerInterface $entityManager): Response
    {
        $article = $this->articleRepository->find($id);
        $entityManager->remove($article);
        $entityManager->flush();
        return $this->redirectToRoute('MainPage');
    }

    // #[Route('/email')]
    // public function sendEmail(MailerInterface $mailer): Response
    // {
    //     $email = (new Email())
    //         ->from('iafanasiev@edu.cdv.pl')
    //         ->to('iafanasiev@edu.cdv.pl')
    //         ->subject('Symfony Mailer')
    //         ->text('Test email')
    //         ->html('<p>See Twig integration for better HTML integration!</p>');

    //     $mailer->send($email);

    //     // ...
    // }


    #[Route('/article/deleteImage/{id}', name: 'deleteImage', methods: ['GET'])]
    public function deleteImage(Int $id, Image $image, EntityManagerInterface $entityManager): Response
    {
        $image = $this->imageRepository->find($id);
        $articleId = $image->getArticle()->getId();
        $entityManager->remove($image);
        $entityManager->flush();
        return $this->redirectToRoute('editArticleForm', ['id' => $articleId]);
    }
}


?>