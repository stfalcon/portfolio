<?php

namespace Application\PortfolioBundle\Controller;

use Application\PortfolioBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Client;

class DefaultController extends Controller
{

    /**
     * Categories/projects list
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
// create a new cURL handle
$ch = curl_init();

// set RSS URL
curl_setopt($ch, CURLOPT_URL, 'http://twitter.com/statuses/user_timeline/stfalcon.rss');

// set additional options
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// get the contents
$a = curl_exec($ch);
 
echo ($a);

// close cURL handle
curl_close($ch);
        exit;

        $em = $this->get('doctrine.orm.entity_manager');
        $query = $em->createQuery('SELECT c FROM PortfolioBundle:Category c');
        $categories = $query->getResult();

        return $this->render('PortfolioBundle:Default:index.html.php', array(
            'categories' => $categories
        ));
    }

}