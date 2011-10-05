<?php

namespace Application\Bundle\DefaultBundle\Controller;

use Stfalcon\Bundle\PortfolioBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{

    /**
     * Categories/projects lilowerst
     *
     * @return array()
     * @Cache(expires="tomorrow")
     * @Route("/", name="homepage")
     * @Template()
     */
//     * @Route("/{_locale}", name="homepage", defaults={"_locale"="ru"}, requirements={"_locale"="ru|en"})
    public function indexAction()
    {
        $categories = $this->get('doctrine')->getEntityManager()
                ->getRepository("StfalconPortfolioBundle:Category")->getAllCategories();

        \Zend\Feed\Reader\Reader::setCache($this->get('knp_zend_cache.manager')->getCache('slow_cache'));
        $feed = \Zend\Feed\Reader\Reader::import('http://www.google.com/reader/public/atom/user%2F14849984795491019190%2Fstate%2Fcom.google%2Fbroadcast');
//        $feed = array();
        
        return array('categories' => $categories, 'feed' => $feed);
    }

    /**
     * Show last twitts
     *
     * @param int $count
     * @return array()
     * @Template()
     */
    public function twitterAction($count = 1)
    {
        $statuses[] = (object) array(
            'text' => (string) 'Unable to Connect to tcp://api.twitter.com:80',
            'time' => (string) \time()
        );

        $cache = $this->get('knp_zend_cache.manager')->getCache('slow_cache');
        if (false === ($statuses = $cache->load('dc_twitter_' . $count))) {
            try {
                $twitter = new \Zend_Service_Twitter();

                // @todo: add try/catch
                $result = $twitter->statusUserTimeline(array('id' => 'stfalcon', 'count' => $count, 'include_rts' => true));
                $statuses = array();
                foreach ($result->status as $status) {
                    $statuses[] = (object) array(
                        'text' => (string) $status->text,
                        'time' => (string) $status->created_at
                    );
                }
                $cache->save($statuses, 'dc_twitter_' . $count);
            } catch (\Zend_Http_Client_Adapter_Exception $e) {
                $statuses = array();
                $statuses[] = (object) array(
                    'text' => (string) 'Unable to Connect to tcp://api.twitter.com:80',
                    'time' => (string) \time()
                );
            }
        }

        return array('statuses' => $statuses);
    }

    /**
     * Contacts page
     *
     * @return array()
     * @Template()
     * @Route("/contacts", name="contacts")
     */
//     * @Route("/{_locale}/contacts", name="contacts", defaults={"_locale"="ru"}, requirements={"_locale"="ru|en"})
    public function contactsAction()
    {
        // @todo: refact
        $breadcrumbs = $this->get('menu.breadcrumbs');
        $breadcrumbs->addChild('Контакты')->setIsCurrent(true);

        return array();
    }

}