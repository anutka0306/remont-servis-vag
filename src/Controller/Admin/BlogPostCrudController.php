<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\String\Slugger\SluggerInterface;

class BlogPostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BlogPost::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();

        yield TextField::new('title', 'Заголовок');

        yield TextField::new('slug', 'URL (slug)');

        yield TextareaField::new('excerpt', 'Краткое описание')
            ->hideOnIndex();

        yield TextareaField::new('content', 'Текст статьи')
            ->setFormTypeOption('attr', [
                'class' => 'js-quill-editor',
            ])
            ->hideOnIndex();

        yield ImageField::new('image', 'Обложка')
            ->setBasePath('uploads/blog')
            ->setUploadDir('public/uploads/blog')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false);

        yield TextField::new('metaTitle', 'SEO заголовок')
            ->hideOnIndex();

        yield TextareaField::new('metaDescription', 'SEO описание')
            ->hideOnIndex();

        yield DateTimeField::new('publishedAt', 'Дата публикации');

        yield DateTimeField::new('createdAt', 'Дата создания')
            ->hideOnForm();

        yield DateTimeField::new('updatedAt', 'Дата изменения')
            ->hideOnForm();

        yield BooleanField::new('isActive', 'Опубликована');
    }
}
