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

        return $this->render('PortfolioBundle:Project:index.html.php', array(
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
        $em = $this->get('doctrine.orm.entity_manager');
        
        $project = new Project();

        // project form
        $form = new \Application\PortfolioBundle\Form\Project(
                'project', $project, $this->get('validator'), array('em' => $em));
        
        if ('POST' === $this->get('request')->getMethod()) {
            $form->bind($this->get('request')->request->get('project'));
            if ($form->isValid()) {
                // create project
                $em->persist($project);
                $em->flush();

                $this->get('request')->getSession()->setFlash('notice',
                        'Congratulations, your project is successfully created!');
                return $this->redirect($this->generateUrl('portfolioProjectIndex'));
            }
        }

        return $this->render('PortfolioBundle:Project:create.html.php', array(
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
        $form = new \Application\PortfolioBundle\Form\Project(
                'project', $project, $this->get('validator'), array('em' => $em));
        
        if ('POST' === $this->get('request')->getMethod()) {
            $form->bind($this->get('request')->request->get('project'));

            if ($form->isValid()) {
                // save project
                $em->persist($project);
                $em->flush();

                $this->get('request')->getSession()->setFlash('notice',
                        'Congratulations, your project is successfully updated!');
                return $this->redirect($this->generateUrl('portfolioProjectIndex'));
            }
        }
        
        return $this->render('PortfolioBundle:Project:edit.html.php', array(
            'form' => $form,
            'project' => $project
        ));
    }

    /**
     * Delete project
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        // try find project by id
        $project = $em->find("PortfolioBundle:Project", $id);
        if (!$project) {
            throw new NotFoundHttpException('The project does not exist.');
        }

        $em->remove($project);
        $em->flush();

        $this->get('request')->getSession()->setFlash('notice',
                'Your project is successfully delete.');
        return $this->redirect($this->generateUrl('portfolioProjectIndex'));
    }
}
