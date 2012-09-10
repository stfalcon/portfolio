<?php

namespace Application\Bundle\DefaultBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Abstract pagination test cases
 *
 * @author Alexandr Yaremyuk <sirmutuh@gmail.com>
 */
abstract class AbstractTestCase extends WebTestCase {

    /**
     * Abstract check method for testing controllers with pagination
     *
     * @param string $crawledUrl routing
     * @param string $extraParamName routing parameter name
     * @param string $extraParamValue routing parameter value
     * @param string $postUnit css class of content element
     * @param int $postNumber maximum number of elements of content one page
     *
     */
    protected function paginationCheck($crawledUrl, $extraParamName, $extraParamValue, $postUnit, $postNumber) {
        $client = static::createClient();

        //Tests for a first paginated page
        $crawler = $this->fetchCrawler(
                $this->getUrl($crawledUrl, array($extraParamName => $extraParamValue, 'page' => '1')), 'GET', true, true
        );

        //check a current page link value
        $this->assertEquals(1, $crawler->filter('.pagination .current:contains("1")')->count());
        //check a maximum amount of content elements
        $this->assertEquals($postNumber, $crawler->filter('.' . $postUnit . '')->count());
        //check amount of pagination links on first page
        $this->assertEquals(5, $crawler->filter('.pagination span')->count());
        //first and previous link can't exist on first page
        $this->assertEquals(0, $crawler->filter('span.first a')->count());
        $this->assertEquals(0, $crawler->filter('span.previous a')->count());

        //Tests for a second paginated page
        $crawler = $this->fetchCrawler(
                $this->getUrl($crawledUrl, array($extraParamName => $extraParamValue, 'page' => '2')), 'GET', true, true
        );

        //check a current page link value
        $this->assertEquals(1, $crawler->filter('.pagination .current:contains("2")')->count());
        //check a maximum amount of pagination links
        $this->assertEquals(7, $crawler->filter('.pagination span')->count());
        //check clicking on next, previous, last and first pagination links from second page
        $this->isLinkClickable($client, $crawler, 'next', 3);
        $this->isLinkClickable($client, $crawler, 'previous', 1);
        $this->isLinkClickable($client, $crawler, 'last', 3);
        $this->isLinkClickable($client, $crawler, 'first', 1);
        //can we click on the first page?
        $this->isLinkClickableByNumber($client, $crawler, 1, 1);

        //Tests for a third (last) paginated page
        $crawler = $this->fetchCrawler(
                $this->getUrl($crawledUrl, array($extraParamName => $extraParamValue, 'page' => '3')), 'GET', true, true
        );

        //check a current page link value
        $this->assertEquals(1, $crawler->filter('.pagination .current:contains("3")')->count());
        //can we click on the second page?
        $this->isLinkClickableByNumber($client, $crawler, 2, 2);
        //last and next link can't exist on last page
        $this->assertEquals(0, $crawler->filter('span.last a')->count());
        $this->assertEquals(0, $crawler->filter('span.next a')->count());
    }

    /**
     * @param string $linkname Clicking on pagination link witch have a value of this variable.
     * Possible values are first, previous, next, last
     * @param int $expectedInt Expected number of pagination link
     *
     */
    private function isLinkClickable($client, $crawler, $linkname, $expectedInt) {
        $link = $crawler->filter('span.' . $linkname . ' a')->link();
        $crawler = $client->click($link);
        $this->assertEquals($expectedInt, $client->getRequest()->attributes->get('page'));
    }

    /**
     * @param int $number Clicking on pagination link witch have a value of this variable
     * Possible values are integers only
     * @param int $expectedInt Expected number of pagination link
     *
     */
    private function isLinkClickableByNumber($client, $crawler, $number, $expectedInt) {
        $link = $crawler->selectLink('' . $number . '')->link();
        $crawler = $client->click($link);
        $this->assertEquals($expectedInt, $client->getRequest()->attributes->get('page'));
    }

}