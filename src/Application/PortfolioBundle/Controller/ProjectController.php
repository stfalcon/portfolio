<?php

namespace Application\PortfolioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\PortfolioBundle\Entity\Project;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectController extends Controller
{

    /**
     * Projects list
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $query = $em->createQuery('SELECT p FROM PortfolioBundle:Project p');
        $projects = $query->getArrayResult();

        return $this->render('PortfolioBundle:Project:index.php.html', array(
            'projects' => $projects
        ));
    }

    /**
     * Create new project
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        $project = new Project();

        $form = new \Application\PortfolioBundle\Form\Project('project', $project, $this->get('validator'));

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bind($this->get('request')->request->get('project'));

            if ($form->isValid()) {
                $em = $this->get('doctrine.orm.entity_manager');
                $em->persist($project);
                $em->flush();

                $this->get('request')->getSession()->setFlash('notice', 'Congratulations, your project is successfully created!');
                $this->redirect($this->generateUrl('portfolioProjectIndex'));
            }
        }

        return $this->render('PortfolioBundle:Project:create.php.html', array(
            'form' => $form
        ));
    }

    /**
     * Edit project
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        // try find project by id
        $project = $em->find("PortfolioBundle:Project", $id);
        if (!$project) {
            throw new NotFoundHttpException('The project does not exist.');
        }

        // project form
        $form = new \Application\PortfolioBundle\Form\Project('project', $project, $this->get('validator'));

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bind($this->get('request')->request->get('project'));

            if ($form->isValid()) {
                // save project
                $em->persist($project);
                $em->flush();

                $this->get('request')->getSession()->setFlash('notice', 'Congratulations, your project is successfully updated!');
                $this->redirect($this->generateUrl('portfolioProjectIndex'));
            }
        }
        
        return $this->render('PortfolioBundle:Project:edit.php.html', array(
            'form' => $form,
            'project' => $project
        ));
    }
}
