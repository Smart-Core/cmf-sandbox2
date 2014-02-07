<?php

namespace SmartCore\Module\Blog\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WidgetController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
/*        $this->bundleName           = 'BlogModule';

        $this->articleServiceName   = 'smart_blog.article';
        $this->categoryServiceName  = 'smart_blog.category';
        $this->tagServiceName       = 'smart_blog.tag';
        $this->routeTag             = 'smart_blog_tag';*/
    }

    /**
     * @param integer $limit
     * @return Response
     */
    public function archiveMonthlyAction($limit = 24)
    {
        /** @var \SmartCore\Module\Blog\Service\ArticleService $articleService */
        $articleService = $this->get('smart_blog.article');
        $archive        = $articleService->getCache()->fetch('archive_monthly');

        if (false === $archive) {
            $archive = $this->renderView('BlogModule:Widget:archive_articles.html.twig', [
                'archiveMonthly' => $articleService->getArchiveMonthly($limit),
            ]);

            $articleService->getCache()->save('archive_monthly', $archive);
        }

        return new Response($archive);
    }

    /**
     * @return Response
     */
    public function categoryTreeAction()
    {
        /** @var \SmartCore\Module\Blog\Service\CategoryService $categoryService */
        $categoryService = $this->get('smart_blog.category');
        $categoryTree    = $categoryService->getCache()->fetch('knp_menu_category_tree');

        if (false === $categoryTree) {
            $categoryTree = $this->renderView('BlogModule:Widget:category_tree.html.twig', [
                'categoryClass' => $categoryService->getCategoryClass(),
            ]);
            $categoryService->getCache()->save('knp_menu_category_tree', $categoryTree);
        }

        return new Response($categoryTree);
    }

    /**
     * @return Response
     */
    public function tagCloudAction()
    {
        /** @var \SmartCore\Module\Blog\Service\TagService $tagService */
        $tagService = $this->get('smart_blog.tag');
        $cloud      = $tagService->getCache()->fetch('tag_cloud_zend');

        if (false === $cloud) {
            $cloud = $tagService->getCloudZend('smart_blog.tag')->render();
            $tagService->getCache()->save('tag_cloud_zend', $cloud);
        }

        return new Response($cloud);
    }
}
