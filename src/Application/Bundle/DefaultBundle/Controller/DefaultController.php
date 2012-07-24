<?php

namespace Application\Bundle\DefaultBundle\Controller;

use Stfalcon\Bundle\PortfolioBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Default controller. For single actions for project
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
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
    public function indexAction()
    {
        $categories = $this->get('doctrine')->getEntityManager()
                ->getRepository("StfalconPortfolioBundle:Category")->getAllCategories();

        //\Zend\Feed\Reader\Reader::setCache($this->get('knp_zend_cache.manager')->getCache('slow_cache'));

        try {
            $feed = \Zend\Feed\Reader\Reader::import('http://www.google.com/reader/public/atom/user%2F14849984795491019190%2Fstate%2Fcom.google%2Fbroadcast');
        } catch (\Zend\Http\Client\Adapter\Exception\RuntimeException $e) {
            $feed = array();
        }

        return array('categories' => $categories, 'feed' => $feed);
    }

    /**
     * Show last twitter messages
     *
     * @param int $count count of twitter messages
     *
     * @return array()
     * @Template()
     */
    public function twitterAction($count = 1)
    {
        $statuses[] = (object) array(
            'text' => (string) 'Unable to Connect to tcp://api.twitter.com:80',
            'time' => (string) \time()
        );

//        $cache = $this->get('knp_zend_cache.manager')->getCache('slow_cache');
//        if (false === ($statuses = $cache->load('dc_twitter_' . $count))) {
//            try {
         
                $twitter = new \Zend_Service_Twitter();

                // @todo: add try/catch
                $result = $twitter->statusUserTimeline(array('id' => 'stfalcon', 'count' => $count, 'include_rts' => true));
                $statuses = array();
                foreach ($result->status as $status) {
                    $time = new \DateTime($status->created_at);
                    $time->setTimezone(new \DateTimeZone('Europe/Kiev'));

                    $statuses[] = (object) array(
                        'text' => (string) $status->text,
                        'time' => $time
                    );
                }
//                $cache->save($statuses, 'dc_twitter_' . $count);
//            } catch (\Zend_Http_Client_Adapter_Exception $e) {
//                $statuses = array();
//                $statuses[] = (object) array(
//                    'text' => (string) 'Unable to Connect to tcp://api.twitter.com:80',
//                    'time' => new \DateTime("now")
//                );
//            }
//        }

        return array('statuses' => $statuses);
    }

    /**
     * Contacts page
     *
     * @return array()
     * @Template()
     * @Route("/contacts", name="contacts")
     */
    public function contactsAction()
    {
        // @todo: refact
        if ($this->has('application_default.menu.breadcrumbs')) {
            $breadcrumbs = $this->get('application_default.menu.breadcrumbs');
            $breadcrumbs->addChild('Контакты')->setCurrent(true);
        }

        return array();
    }

}