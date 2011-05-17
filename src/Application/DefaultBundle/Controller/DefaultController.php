<?php

namespace Application\DefaultBundle\Controller;

use Application\PortfolioBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

//    public function __postConstruct()
//    {
//        var_dump(111);
////    	$this->request = $this->container->get('request');
////        $this->router = $this->container->get('router');
////        $this->repository = $this->container->get('jobeet2.category.repository');
////        $this->templating = $this->container->get('templating');
////        $this->max_jobs_on_category = $this->container->getParameter('jobeet2.max_jobs_on_category');
//    }

    /**
     * Categories/projects lilowerst
     *
     * @return array()
     * @extra:Cache(expires="tomorrow")
     * @extra:Route("/{_locale}", name="homepage", defaults={"_locale"="ru"}, requirements={"_locale"="ru|en"})
     * @extra:Template()
     */
    public function indexAction()
    {
//        $gravatar = new \Bundle\GravatarBundle\GravatarApi();
//        echo $gravatar->getUrl('stepan.tanasiychuk@gmail.com');

//        var_dump($this->get('templating'));
//        $toolbox = $this->get('toolbox.string');
//        var_dump($toolbox->transform('stfalcon'));
//        exit;
        
        $categories = $this->get('doctrine')->getEntityManager()
                ->getRepository("PortfolioBundle:Category")->getAllCategories();

//        $cache = $this->get('zend.cache_manager')->getCache('slow_cache');
//        if (false === ($feed = $cache->load('dc_feed'))) {
//            $feed = \Zend_Feed::import('http://feeds.feedburner.com/stfalcon');
//            $cache->save($feed, 'dc_feed');
//        }
//
//        $response = new Response();
//        $response->setMaxAge(600);
//        $response->setPublic();
//        $response->setSharedMaxAge(600);
//        
//        return $this->render('DefaultBundle:Default:index.html.twig',
//                array('categories' => $categories, /*'feed' => $feed*/), $response);

        return array('categories' => $categories);
    }

    /**
     * Show last twitts
     *
     * @return array()
     * @extra:Template()
     */
    public function twitterAction($count = 1)
    {
        $statuses[] = (object) array(
            'text' => (string) 'Unable to Connect to tcp://api.twitter.com:80',
            'time' => (string) \time()
        );

//        $cache = $this->get('zend.cache_manager')->getCache('slow_cache');
//        if (false === ($statuses = $cache->load('dc_twitter_' . $count))) {
//            try {
//                $twitter = new \Zend_Service_Twitter();
//
//                // @todo: add try/catch
//                $result = $twitter->statusUserTimeline(array('id' => 'stfalcon', 'count' => $count));
//                $statuses = array();
//                foreach ($result->status as $status) {
//                    $statuses[] = (object) array(
//                        'text' => (string) $status->text,
//                        'time' => (string) $status->created_at
//                    );
//                }
//                $cache->save($statuses, 'dc_twitter_' . $count);
//            } catch (\Zend_Http_Client_Adapter_Exception $e) {
//                $statuses = array();
//                $statuses[] = (object) array(
//                    'text' => (string) 'Unable to Connect to tcp://api.twitter.com:80',
//                    'time' => (string) \time()
//                );
//            }
//        }

        return array('statuses' => $statuses);
    }

    /**
     * Contacts page
     *
     * @return array()
     * @extra:Template()
     * @extra:Route("/{_locale}/contacts", name="portfolioDefaultContacts", defaults={"_locale"="ru"}, requirements={"_locale"="ru|en"})
     */
    public function contactsAction()
    {
        // @todo: refact
        $breadcrumbs = $this->get('menu.breadcrumbs');
        $breadcrumbs->addChild('Контакты')->setIsCurrent(true);

        return array();
    }

}