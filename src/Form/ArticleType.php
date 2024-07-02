<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['attr' => ['placeholder' => 'tilte']])
            ->add('content', TextareaType::class, ['attr' => ['rows' => 8, 'placeholder' => 'content']])
            ->add('images', FileType::class, [
                'label' => 'Add new images',
                'mapped' => false,
                'multiple' => true,
                'required' => false,
                'attr' => ['class' => 'image-input'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save changes']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}


?>