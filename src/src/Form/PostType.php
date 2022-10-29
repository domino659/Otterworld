<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $post = $options['data'] ?? null;
        $isEdit = $post && $post->getId();
        $imageContraints = [
            new Image([
                'maxSize' => '2M',
                'mimeTypes' => [
                    'image/jpeg',
                    'image/png'
                ],
                'mimeTypesMessage' => 'Please upload a valid image, only jpeg and png are allowed, (max size 2M)'
            ])
        ];

        if (!$isEdit) {
            $imageContraints[] = new NotNull();
        }

        $builder
            ->add('title')
            ->add('slug')
            ->add('content', TextType::class)
            ->add('price', NumberType::class)
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => $imageContraints
            ])
            ->add('Create', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
