<?php

namespace Application\PortfolioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Application\PortfolioBundle\Entity\Project;
use Application\PortfolioBundle\Entity\Category;
use Application\PortfolioBundle\Form\CategoryForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CategoryController extends Controller
{

    /**
     * List of categories
     *
     * @return array
     * @Route("/admin/portfolio/categories", name="portfolioCategoryIndex")
     * @Template()
     */
    public function indexAction()
    {
        $categories = $this->get('doctrine')->getEntityManager()
                ->getRepository("PortfolioBundle:Category")->getAllCategories();

        return array('categories' => $categories);
    }

    /**
     * Create new category
     *
     * @return array|RedirectResponse
     * @Route("/admin/portfolio/category/create", name="portfolioCategoryCreate")
     * @Template()
     */
    public function createAction()
    {
        $category = new Category();
        $form = $this->get('form.factory')->create(new CategoryForm(), $category);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getEntityManager();
                $em->persist($category);
                $em->flush();

                $this->get('request')->getSession()->setFlash('notice',
                        'Congratulations, your category is successfully created!');
                return new RedirectResponse($this->generateUrl('portfolioCategoryIndex'));
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * Edit category
     *
     * @param string $slug
     * @return array|RedirectResponse
     * @Route("/admin/portfolio/category/edit/{slug}", name="portfolioCategoryEdit")
     * @Template()
     */
    public function editAction($slug)
    {
        // try find category by slug
        $category = $this->_findCategoryBySlug($slug);
        $form = $this->get('form.factory')->create(new CategoryForm(), $category);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                // save category
                $em = $this->get('doctrine')->getEntityManager();
                $em->persist($category);
                $em->flush();

                $this->get('request')->getSession()->setFlash('notice', 'Congratulations, your category is successfully updated!');
                return new RedirectResponse($this->generateUrl('portfolioCategoryIndex'));
            }
        }

        return array('form' => $form->createView(), 'category' => $category);
    }

    /**
     * View category
     *
     * @param string $slug
     * @Route("/portfolio/{slug}", name="portfolioCategoryView")
     * @Template()
     */
//     * @Route("/{_locale}/portfolio/{slug}", name="portfolioCategoryView",
//     *      defaults={"_locale"="ru"}, requirements={"_locale"="ru|en"})
    public function viewAction($slug)
    {
        $category = $this->_findCategoryBySlug($slug);

        $paginator = \Zend\Paginator\Paginator::factory($category->getProjects()->toArray());
        $paginator->setCurrentPageNumber($this->get('request')->query->get('page', 1));
        $paginator->setItemCountPerPage(6);

        $breadcrumbs = $this->get('menu.breadcrumbs');
        $breadcrumbs->addChild('Услуги', $this->get('router')->generate('homepage'));
        $breadcrumbs->addChild($category->getName())->setIsCurrent(true);

        return $this->render('PortfolioBundle:Category:view.html.twig', array(
            'category' => $category,
            'paginator' => $paginator,
        ));
    }

    /**
     * Delete category
     *
     * @param string $slug
     * @return RedirectResponse
     * @Route("/admin/portfolio/category/delete/{slug}", name="portfolioCategoryDelete")
     */
    public function deleteAction($slug)
    {
        $category = $this->_findCategoryBySlug($slug);

        $em = $this->get('doctrine')->getEntityManager();
        $em->remove($category);
        $em->flush();

        $this->get('request')->getSession()->setFlash('notice', 'Your category is successfully delete.');
        return new RedirectResponse($this->generateUrl('portfolioCategoryIndex'));
    }

    /**
     * Services widget
     *
     * @param Category $category
     * @param Project $project
     * @return array
     * @Template()
     */
    public function servicesAction(Category $category, $project = null)
    {
        $categories = $this->get('doctrine.orm.entity_manager')
                ->getRepository("PortfolioBundle:Category")->getAllCategories();

        return array('categories' => $categories, 'currentProject' => $project, 'currentCategory' => $category);
    }

    /**
     * Try find category by slug
     * 
     * @param string $slug
     * @return Category
     */
    private function _findCategoryBySlug($slug)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $category = $em->getRepository("PortfolioBundle:Category")
                ->findOneBy(array('slug' => $slug));
        if (!$category) {
            throw new NotFoundHttpException('The category does not exist.');
        }

        return $category;
    }

}
