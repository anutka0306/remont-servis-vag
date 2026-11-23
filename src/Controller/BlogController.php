<?php

namespace App\Controller;

use App\Repository\BlogPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog_index')]
    public function index(BlogPostRepository $repository): Response
    {
        $posts = $repository->findBy(
            ['isActive' => true],
            ['publishedAt' => 'DESC']
        );

        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/blog/{slug}', name: 'blog_show')]
    public function show(
        string $slug,
        BlogPostRepository $repository
    ): Response {
        $post = $repository->findOneBy([
            'slug' => $slug,
            'isActive' => true,
        ]);

        if (!$post) {
            throw $this->createNotFoundException('Статья не найдена');
        }

        return $this->render('blog/show.html.twig', [
            'post' => $post,
        ]);
    }
}