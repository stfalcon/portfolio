<?php

namespace Application\PortfolioBundle\Controller;

use Application\PortfolioBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    /**
     * Categories/projects list
     *
     * @Cache(expires="tomorrow")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $categories = $this->get('doctrine.orm.entity_manager')
                ->getRepository("PortfolioBundle:Category")->getAllCategories();

//        $cache = $this->get('zend.cache_manager')->getCache('my_template_cache');
//        if (false === ($feed = $cache->load('dc_feed'))) {
//            $feed = \Zend_Feed::import('http://feeds.feedburner.com/stfalcon');
//            $cache->save($feed, 'dc_feed');
//        }

        $response = new Response();
        $response->setMaxAge(600);
        $response->setPublic();
        $response->setSharedMaxAge(600);

        return $this->render('PortfolioBundle:Default:index.html.php',
                array('categories' => $categories, /*'feed' => $feed*/), $response);
    }

    public function twitterAction($count = 1)
    {
        $cache = $this->get('zend.cache_manager')->getCache('my_template_cache');
        if (false === ($statuses = $cache->load('dc_twitter_' . $count))) {
            $twitter = new \Zend_Service_Twitter();

            // @todo: add try/catch
            $result = $twitter->statusUserTimeline(array('id' => 'stfalcon', 'count' => $count));
            $statuses = array();
            foreach ($result->status as $status) {
                $statuses[] = (object) array(
                    'text' => (string) $status->text,
                    'time' => (string) $status->created_at
                );
            }
            $cache->save($statuses, 'dc_twitter_' . $count);
        }

//        $response = new Response();
//        $response->setMaxAge(600);
//        $response->setPublic();
//        $response->setSharedMaxAge(600);

//        return $this->render('PortfolioBundle:Default:twitter.html.php',
//                array('statuses' => $statuses), $response);
        return $this->render('PortfolioBundle:Default:twitter.html.php',
                array('statuses' => $statuses));
    }

    public function servicesAction($project)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $query = $em->createQuery('SELECT c FROM PortfolioBundle:Category c');
        $categories = $query->getResult();
        
        return $this->render('PortfolioBundle:Default:services.html.php',
                array('categories' => $categories, 'currentProject' => $project));
    }

    public function contactsAction()
    {
        $response = new Response();
        $response->setMaxAge(600);
        $response->setPublic();
        $response->setSharedMaxAge(600);

        // @todo: refact
        $breadcrumbs = $this->get('menu.breadcrumbs');
        $breadcrumbs->addChild('Контакты')->setIsCurrent(true);

        return $this->render('PortfolioBundle:Default:contacts.html.php',
                array(), $response);
    }

}