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
    /** @var Client */
    protected $client;

    public function __construct()
    {
        $this->client = $this->makeClient();
    }

    public function testAccessDeniedForUnathorizedUsers()
    {
        $this->_testReturnCode(302, $this->getUrl('admin_stfalcon_blog_post_list', array()));
        $this->_testReturnCode(302, $this->getUrl('admin_stfalcon_blog_post_create', array()));
        $this->_testReturnCode(302, $this->getUrl('admin_stfalcon_blog_post_edit', array('id' => 0)));
        $this->_testReturnCode(302, $this->getUrl('admin_stfalcon_blog_post_delete', array('id' => 0)));
    }

    public function testAccessAllowedForUnathorizedUsers()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
            'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));

        $this->_testReturnCode(200, $this->getUrl('blog', array()));
        $this->doLogin();
        $this->_testReturnCode(200, $this->getUrl('blog_post_view', array('slug' => 'my-first-post')));
    }

    /**
     * Check return code
     *
     * @param int    $code Expected code
     * @param string $url  Page url for test
     */
    protected function _testReturnCode($code, $url)
    {
        $this->client->request('GET', $url);

        $this->assertEquals($code, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Do login with username
     *
     * @param string $username
     */
    private function doLogin($username = 'admin')
    {
        $crawler = $this->client->request('GET', $this->getUrl('fos_user_security_login', array()));
        $form = $crawler->selectButton('_submit')->form(array(
            '_username' => $username,
            '_password' => 'qwerty'
        ));
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirects();
    }
}