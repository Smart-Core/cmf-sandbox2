<?php

namespace SmartCore\Module\Blog\Controller;

use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SmartCore\Bundle\CMSBundle\Pagerfanta\SimpleDoctrineORMAdapter;

class ArticleController extends Controller
{
    /**
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($slug)
    {
        $article = $this->getArticleService()->getBySlug($slug);

        if (!$article) {
            throw $this->createNotFoundException();
        }

        return $this->render('BlogModule:Article:show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @param Request $requst
     * @param integer $page
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $requst, $page = null)
    {
        if (null === $page and $requst->query->has('page')) {
            $page = $requst->query->get('page');
        }

        if ($page == 1) {
            return $this->redirect($this->generateUrl('smart_blog.article.index'));
        }

        if (null === $page) {
            $page = 1;
        }

        $articleService = $this->getArticleService();

        $pagerfanta = new Pagerfanta(new SimpleDoctrineORMAdapter($articleService->getFindByCategoryQuery()));
        $pagerfanta->setMaxPerPage($articleService->getItemsCountPerPage());

        try {
            $pagerfanta->setCurrentPage($page);
        } catch (NotValidCurrentPageException $e) {
            return $this->redirect($this->generateUrl('smart_blog.article.index'));
        }

        return $this->render('BlogModule:Article:index.html.twig', [
            'pagerfanta' => $pagerfanta,
        ]);
    }

    /**
     * @param Request $requst
     * @param integer $year
     * @param integer $month
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function archiveMonthlyAction(Request $requst, $year = 1970, $month = 1)
    {
        $firstDate = new \Datetime($year . '-' . $month . '-1');
        $lastDate  = clone $firstDate;
        $lastDate->modify('+1 month');

        $articleService = $this->getArticleService();

        $pagerfanta = new Pagerfanta(new SimpleDoctrineORMAdapter($articleService->getFindByDateQuery($firstDate, $lastDate)));
        $pagerfanta->setMaxPerPage($articleService->getItemsCountPerPage());

        try {
            $pagerfanta->setCurrentPage($requst->query->get('page', 1));
        } catch (NotValidCurrentPageException $e) {
            return $this->redirect($this->generateUrl('smart_blog.article.index'));
        }

        return $this->render('BlogModule:Article:archive_list.html.twig', [
            'pagerfanta' => $pagerfanta,
            'year'       => $year,
            'month'      => $month,
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction(Request $request, $id)
    {
        $articleService = $this->getArticleService();
        $article        = $articleService->get($id);

        if (null === $article) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm($this->get('smart_blog.article.edit.form.type'), $article);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $article = $form->getData();
                $articleService->update($article);

                return $this->redirect($this->generateUrl('smart_blog.article.show', ['slug' => $article->getSlug()] ));
            }
        }

        return $this->render('BlogModule:Article:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $articleService = $this->getArticleService();
        $article        = $articleService->create();

        // @todo эксперименты с событиями.
        $form = $this->createForm($this->get('smart_blog.article.create.form.type'), $article);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $articleService->update($article, false);

                return $this->redirect($this->generateUrl('smart_blog.article.show', ['slug' => $article->getSlug()] ));
            }
        }

        return $this->render('BlogModule::Article:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return \SmartCore\Module\Blog\Service\ArticleService
     */
    protected function getArticleService()
    {
        return $this->get('smart_blog.article');
    }
}
