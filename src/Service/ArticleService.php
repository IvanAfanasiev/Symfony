<?php

declare(strict_types=1);
namespace App\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Entity\Image;

class ArticleService{
    
    public function __construct (
        private readonly EntityManagerInterface $entityManager,
        private string $imagesPath,
        private readonly string $imagesDirectoryPath,
        private readonly FormFactoryInterface   $formFactory
    ){ }

    public function transformDataForTwig(array $articles): array{
        $transformedData = [];

        foreach($articles as $article){

            $images = $article->getImages();
            if (!$images->isEmpty()) {
                $imagePath = $images->first()->getPath();
            } else {
                $imagePath = '/img/images.jpeg';
            }

            $transformedData['articles'][]=[
                'id'=>$article->getId(),
                'title'=>$article->getTitle(),
                'date'=>$article->getDateAdded(),
                'content'=>$article->getContent(),
                'imagePath'=>$imagePath,
                'link'=>'article/'.$article->getId(),
            ];
        }
        return $transformedData;
    }

    public function createArticleForm(Article $article): FormInterface
    {
        return $this->formFactory->create(ArticleType::class, $article);
    }
    public function handleArticleForm(Request $request, Article $article): bool
    {
        $form = $this->createArticleForm($article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(!$article->getdateAdded()) {
                $article->setdateAdded(new \DateTime());
            }

            $imageFiles = $form->get('images')->getData();

            foreach ($imageFiles as $imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = bin2hex(random_bytes(6)).'.'.$imageFile->guessExtension();
                $imageFile->move($this->imagesPath, $newFilename);

                $image = new Image();
                $image->setPath($this->imagesDirectoryPath . $newFilename);
                $image->setArticle($article);
                $image->setTitle($originalFilename);

                $this->entityManager->persist($image);
            }

            $this->entityManager->persist($article);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }

}



?>