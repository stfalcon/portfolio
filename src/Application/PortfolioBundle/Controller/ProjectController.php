<?php

namespace Application\PortfolioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\PortfolioBundle\Entity\Project;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

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
        $query = $em->createQuery('SELECT p FROM Portfolio:Project p ORDER BY p.date DESC');
        $projects = $query->getResult();

        return $this->render('Portfolio:Project:index.html.php', array(
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
        
        $form = \Application\PortfolioBundle\Form\Project::create(
                $this->get("form.context"), 'project', array('em' => $em));
        $form->bind($this->get('request'), $project);

        if ($form->isValid()) {
            // create project
            $em->persist($project);
            $em->flush();

            $this->get('request')->getSession()->setFlash('notice',
                    'Congratulations, your project is successfully created!');
            return new RedirectResponse($this->generateUrl('portfolioProjectIndex'));
        }

        return $this->render('Portfolio:Project:create.html.php', array(
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
        $project = $em->find("Portfolio:Project", $id);
        if (!$project) {
            throw new NotFoundHttpException('The project does not exist.');
        }
        
        $form = \Application\PortfolioBundle\Form\Project::create(
                $this->get("form.context"), 'project', array('em' => $em));
        $form->bind($this->get('request'), $project);

        if ($form->isValid()) {
            // save project
            $em->persist($project);
            $em->flush();

            $this->get('request')->getSession()->setFlash('notice',
                    'Congratulations, your project is successfully updated!');
            return new RedirectResponse($this->generateUrl('portfolioProjectIndex'));
        }

        return $this->render('Portfolio:Project:edit.html.php', array(
            'form' => $form,
            'project' => $project
        ));
    }

    public function viewAction($categoryId, $projectId)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        // try find project by id
        $currentProject = $em->find("Portfolio:Project", $projectId);
        if (!$currentProject) {
            throw new NotFoundHttpException('The project does not exist.');
        }


        // get all projects from this category
        $query = $em->createQuery('SELECT p FROM Portfolio:Project p JOIN p.categories c WHERE c.id = ?1 ORDER BY p.date DESC');
        $query->setParameter(1, $categoryId);
        $projects = $query->getResult();

        // get next and previous projects from category
        $i = 0; $previousProject = null; $nextProject = null;
        foreach ($projects as $project) {
            if ($project->getId() == $currentProject->getId()) {
                $previousProject = isset($projects[$i-1]) ? $projects[$i-1] : null;
                $nextProject     = isset($projects[$i+1]) ? $projects[$i+1] : null;
                break;
            }
            $i++;
        }

        $response = new Response();
        $response->setMaxAge(600);
        $response->setPublic();
        $response->setSharedMaxAge(600);

        return $this->render('Portfolio:Project:view.html.php', array(
            'currentProject' => $currentProject,
            'categoryId' => $categoryId,
            'previousProject' => $previousProject,
            'nextProject' => $nextProject
        ), $response);
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
        $project = $em->find("Portfolio:Project", $id);
        if (!$project) {
            throw new NotFoundHttpException('The project does not exist.');
        }

        $em->remove($project);
        $em->flush();

        $this->get('request')->getSession()->setFlash('notice',
                'Your project is successfully delete.');
        return new RedirectResponse($this->generateUrl('portfolioProjectIndex'));
    }

}
