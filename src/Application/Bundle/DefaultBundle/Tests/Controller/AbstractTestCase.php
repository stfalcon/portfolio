<?php

namespace Application\Bundle\DefaultBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Pagination test cases for PostController and TagController
 *
 * @author Alexandr Yaremyuk <sirmutuh@gmail.com>
 */
abstract class AbstractTestCase extends WebTestCase
{
 /**
 * Abstract pagination test method for both post an
 *
 */
    protected function paginationCheck($crawledUrl, $extraParamName, $extraParamValue, $postUnit, $postNumber)
    {
        $client = static::createClient();

        $crawler = $this->fetchCrawler(
                $this->getUrl($crawledUrl, array($extraParamName => $extraParamValue, 'page' => '1')), 'GET', true, true
        );

        $this->assertEquals(1, $crawler->filter('.pagination .current:contains("1")')->count());
        $this->assertEquals($postNumber, $crawler->filter('.'.$postUnit.'')->count());
        $this->assertEquals(5, $crawler->filter('.pagination span')->count());
        $this->assertEquals(0, $crawler->filter('span.first a')->count());
        $this->assertEquals(0, $crawler->filter('span.previous a')->count());


        $crawler = $this->fetchCrawler(
                $this->getUrl($crawledUrl, array($extraParamName => $extraParamValue, 'page' => '2')), 'GET', true, true
        );

        $this->assertEquals(1, $crawler->filter('.pagination .current:contains("2")')->count());
        $this->assertEquals(7, $crawler->filter('.pagination span')->count());
        $this->isLinkClickable($client, $crawler, 'next', 3);
        $this->isLinkClickable($client, $crawler, 'previous', 1);
        $this->isLinkClickable($client, $crawler, 'last', 3);
        $this->isLinkClickable($client, $crawler, 'first', 1);
        $this->isLinkClickableByNumber($client, $crawler, 1, 1);


        $crawler = $this->fetchCrawler(
                $this->getUrl($crawledUrl, array($extraParamName => $extraParamValue, 'page' => '3')), 'GET', true, true
        );

        $this->assertEquals(1, $crawler->filter('.pagination .current:contains("3")')->count());
        $this->isLinkClickableByNumber($client, $crawler, 2, 2);
        $this->assertEquals($postNumber, $crawler->filter('.'.$postUnit.'')->count());
        $this->assertEquals(0, $crawler->filter('span.last a')->count());
        $this->assertEquals(0, $crawler->filter('span.next a')->count());


    }

   private function isLinkClickable($client, $crawler, $linkname, $expectedInt)
   {
       $link = $crawler->filter('span.'.$linkname.' a')->link();
       $crawler = $client->click($link);
       $this->assertEquals($expectedInt, $client->getRequest()->attributes->get('page'));
   }

   private function isLinkClickableByNumber($client, $crawler, $number, $expectedInt)
   {
       $link = $crawler->selectLink(''.$number.'')->link();
       $crawler = $client->click($link);
       $this->assertEquals($expectedInt, $client->getRequest()->attributes->get('page'));
   }
}
