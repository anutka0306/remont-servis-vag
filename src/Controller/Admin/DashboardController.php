<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Brand;
use App\Controller\Admin\BrandCrudController;
use App\Entity\Model;
use App\Controller\Admin\ModelCrudController;
use App\Entity\ServiceCategory;
use App\Controller\Admin\ServiceCategoryCrudController;
use App\Entity\Service;
use App\Controller\Admin\ServiceCrudController;
use App\Entity\Promotion;
use App\Controller\Admin\PromotionCrudController;
use App\Entity\User;
use App\Controller\Admin\UserCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGeneratorInterface::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Remont Service Vag');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Марки', 'fa fa-car', Brand::class)
            ->setController(BrandCrudController::class);
        yield MenuItem::linkToCrud('Модели', 'fa fa-car', Model::class)
            ->setController(ModelCrudController::class);
        yield MenuItem::linkToCrud('Категории услуг', 'fa fa-pen', ServiceCategory::class)
            ->setController(ServiceCategoryCrudController::class);
        yield MenuItem::linkToCrud('Услуги', 'fa fa-pen', Service::class)
            ->setController(ServiceCrudController::class);
        yield MenuItem::linkToCrud('Акции', 'fa fa-tag', Promotion::class)
            ->setController(PromotionCrudController::class);
        yield MenuItem::linkToCrud('Пользователи', 'fa fa-user', User::class)
            ->setController(UserCrudController::class);
        yield MenuItem::linkToCrud('Блог', 'fa fa-newspaper', BlogPost::class)
            ->setController(BlogPostCrudController::class);

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('vendor/quill/quill.snow.css')
            ->addCssFile('vendor/quill/resize.css')
            ->addJsFile('vendor/quill/quill.js')
            ->addJsFile('vendor/quill/resize.js')
            ->addJsFile('js/admin-quill.js');
    }
}
