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
        $query = $em->createQuery('SELECT p FROM PortfolioBundle:Project p ORDER BY p.date DESC');
        $projects = $query->getResult();

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

        return $this->render('PortfolioBundle:Project:edit.html.php', array(
            'form' => $form,
            'project' => $project
        ));
    }

    public function viewAction($categorySlug, $projectSlug)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        // try find project by slug
        $project = $em->getRepository("PortfolioBundle:Project")
                ->findOneBy(array('slug' => $projectSlug));
        
        if (!$project) {
            throw new NotFoundHttpException('The project does not exist.');
        }

        // try find category by slug
        $category = $em->getRepository("PortfolioBundle:Category")
                ->findOneBy(array('slug' => $categorySlug));

        if (!$project) {
            throw new NotFoundHttpException('The project does not exist.');
        }

        $breadcrumbs = $this->get('menu.breadcrumbs');
        $breadcrumbs->addChild('Портфолио', $this->get('router')->generate('homepage'));
        $breadcrumbs->addChild($project->getName())->setIsCurrent(true);

        $response = new Response();
        $response->setMaxAge(600);
        $response->setPublic();
        $response->setSharedMaxAge(600);

        return $this->render('PortfolioBundle:Project:view.html.php', array(
            'project' => $project,
            'category' => $category,
        ), $response);
    }

    /**
     *
     * @param Category $category
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function nearbyProjectsAction($category, $project)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        // get all projects from this category
        $query = $em->createQuery('SELECT p FROM PortfolioBundle:Project p JOIN p.categories c WHERE c.id = ?1 ORDER BY p.date DESC');
        $query->setParameter(1, $category->getId());
        $projects = $query->getResult();

        // get next and previous projects from this category
        $i = 0; $previousProject = null; $nextProject = null;
        foreach ($projects as $p) {
            if ($project->getId() == $p->getId()) {
                $previousProject = isset($projects[$i-1]) ? $projects[$i-1] : null;
                $nextProject     = isset($projects[$i+1]) ? $projects[$i+1] : null;
                break;
            }
            $i++;
        }

        return $this->render('PortfolioBundle:Project:nearby-projects.html.php',
                array(
                    'category' => $category,
                    'previousProject' => $previousProject,
                    'nextProject' => $nextProject,
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
        return new RedirectResponse($this->generateUrl('portfolioProjectIndex'));
    }

}
