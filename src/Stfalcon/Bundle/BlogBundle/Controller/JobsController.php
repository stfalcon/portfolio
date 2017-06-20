<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class JobsController extends Controller
{
    /**
     * @Route("/jobs", name="jobs")
     */
    public function jobsAction()
    {
        $jobs = $this->get('doctrine')->getManager()->getRepository("StfalconBlogBundle:Jobs")->findAll();

    }
}