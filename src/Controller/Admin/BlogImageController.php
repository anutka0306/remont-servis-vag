<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class BlogImageController extends AbstractController
{
    #[Route('/admin/blog/upload-image', name: 'admin_blog_upload_image', methods: ['POST'])]
    public function upload(Request $request): JsonResponse
    {
        $file = $request->files->get('image');

        if (!$file) {
            return $this->json(['error' => 'Файл не передан'], 400);
        }

        if (!$file->isValid()) {
            return $this->json(['error' => 'Ошибка загрузки файла'], 400);
        }

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

        if (!in_array($file->getMimeType(), $allowedMimeTypes, true)) {
            return $this->json(['error' => 'Недопустимый формат изображения'], 400);
        }

        if ($file->getSize() > 5 * 1024 * 1024) {
            return $this->json(['error' => 'Максимальный размер — 5 МБ'], 400);
        }

        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/blog';

        $filename = bin2hex(random_bytes(16)) . '.' . $file->guessExtension();

        $file->move($uploadDir, $filename);

        return $this->json([
            'url' => '/uploads/blog/' . $filename
        ]);
    }
}
