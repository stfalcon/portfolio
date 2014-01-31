<?php

namespace StfalconBundle\Bundle\BlogBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test cases for check access to blog actions
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class AccessTest extends WebTestCase
{

    public function testAccessDeniedForUnathorizedUsers()
    {
        $this->_testReturnCode(401, $this->getUrl('admin_bundle_blog_post_list', array()));
        $this->_testReturnCode(401, $this->getUrl('admin_bundle_blog_post_create', array()));
        $this->_testReturnCode(401, $this->getUrl('admin_bundle_blog_post_edit', array('id' => 0)));
        $this->_testReturnCode(401, $this->getUrl('admin_bundle_blog_post_delete', array('id' => 0)));
    }

    public function testAccessAllowedForUnathorizedUsers()
    {
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));

        $this->_testReturnCode(200, $this->getUrl('blog', array()));
        $this->_testReturnCode(200, $this->getUrl('blog_post_view', array('slug' => 'my-first-post')), true);
    }

    /**
     * Check return code
     *
     * @param int    $code           Expected code
     * @param string $url            Page url for test
     * @param bool   $authentication Log in
     *
     * @return void
     */
    protected function _testReturnCode($code, $url, $authentication = false)
    {
        $client = $this->makeClient($authentication);
        $crawler = $client->request('GET', $url);

        $this->assertEquals($code, $client->getResponse()->getStatusCode());
    }
}