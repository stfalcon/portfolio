<?php

namespace Application\PortfolioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Application\PortfolioBundle\Entity\Category;
use Application\PortfolioBundle\Form\CategoryForm;

class CategoryController extends Controller
{

    /**
     * List of categories
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $categories = $this->get('doctrine.orm.entity_manager')
                ->getRepository("PortfolioBundle:Category")->getAllCategories();

        return $this->render('PortfolioBundle:Category:index.html.twig', array(
            'categories' => $categories
        ));
    }

    /**
     * Create new category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        $category = new Category();
        $form = $this->get('form.factory')->create(new CategoryForm(), $category);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $em = $this->get('doctrine.orm.entity_manager');
                $em->persist($category);
                $em->flush();

                $this->get('request')->getSession()->setFlash('notice',
                        'Congratulations, your category is successfully created!');
                return new RedirectResponse($this->generateUrl('portfolioCategoryIndex'));
            }
        }

        return $this->render('PortfolioBundle:Category:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Edit category
     *
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\Response
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
                $em = $this->get('doctrine.orm.entity_manager');
                $em->persist($category);
                $em->flush();

                $this->get('request')->getSession()->setFlash('notice', 'Congratulations, your category is successfully updated!');
                return new RedirectResponse($this->generateUrl('portfolioCategoryIndex'));
            }
        }

        return $this->render('PortfolioBundle:Category:edit.html.twig', array(
            'form' => $form->createView(),
            'category' => $category
        ));
    }

    /**
     * View category
     *
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($slug)
    {
        $category = $this->_findCategoryBySlug($slug);

        return $this->render('PortfolioBundle:Category:view.html.twig', array(
            'category' => $category
        ));
    }

    /**
     * Delete category
     *
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($slug)
    {
        $category = $this->_findCategoryBySlug($slug);

        $em = $this->get('doctrine.orm.entity_manager');
        $em->remove($category);
        $em->flush();

        $this->get('request')->getSession()->setFlash('notice', 'Your category is successfully delete.');
        return new RedirectResponse($this->generateUrl('portfolioCategoryIndex'));
    }

    /**
     * Services widget
     *
     * @param \Application\PortfolioBundle\Entity\Project $project
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function servicesWidgetAction(\Application\PortfolioBundle\Entity\Project $project)
    {
        $categories = $this->get('doctrine.orm.entity_manager')
                ->getRepository("PortfolioBundle:Category")->getAllCategories();

        return $this->render('PortfolioBundle:Category:services.html.twig',
                array('categories' => $categories, 'currentProject' => $project));
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
