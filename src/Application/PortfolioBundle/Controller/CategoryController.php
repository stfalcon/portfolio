<?php

namespace Application\PortfolioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\PortfolioBundle\Entity\Category;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{

    /**
     * Categories list
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $query = $em->createQuery('SELECT c FROM PortfolioBundle:Category c');
        $categories = $query->getArrayResult();

        return $this->render('PortfolioBundle:Category:index.html.php', array(
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

        $form = new \Application\PortfolioBundle\Form\Category('category', $category, $this->get('validator'));

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bind($this->get('request')->request->get('category'));

            if ($form->isValid()) {
                $em = $this->get('doctrine.orm.entity_manager');
                $em->persist($category);
                $em->flush();

                $this->get('request')->getSession()->setFlash('notice', 'Congratulations, your category is successfully created!');
                return $this->redirect($this->generateUrl('portfolioCategoryIndex'));
            }
        }

        return $this->render('PortfolioBundle:Category:create.html.php', array(
            'form' => $form
        ));
    }

    /**
     * Edit category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        // try find category by id
        $category = $em->find("PortfolioBundle:Category", $id);
//        var_dump(is_a($category->getProjects(), 'Traversable'));
        var_dump($category->getProjects()->toArray());
        exit;
        if (!$category) {
            throw new NotFoundHttpException('The category does not exist.');
        }

        // category form
        $form = new \Application\PortfolioBundle\Form\Category('category', $category, $this->get('validator'));

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bind($this->get('request')->request->get('category'));

            if ($form->isValid()) {
//                $project = $em->find("PortfolioBundle:Project", 1);
//                $category->addProject($project);
                
                // save category
                $em->persist($category);
                $em->flush();

                $this->get('request')->getSession()->setFlash('notice', 'Congratulations, your category is successfully updated!');
                return $this->redirect($this->generateUrl('portfolioCategoryIndex'));
            }
        }

        return $this->render('PortfolioBundle:Category:edit.html.php', array(
            'form' => $form,
            'project' => $category
        ));
    }

    /**
     * Delete category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        // try find category by id
        $category = $em->find("PortfolioBundle:Category", $id);
        if (!$category) {
            throw new NotFoundHttpException('The category does not exist.');
        }

        $em->remove($category);
        $em->flush();

        $this->get('request')->getSession()->setFlash('notice', 'Your category is successfully delete.');
        return $this->redirect($this->generateUrl('portfolioCategoryIndex'));
    }
}
