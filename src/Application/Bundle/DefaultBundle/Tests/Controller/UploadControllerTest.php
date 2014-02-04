<?php

namespace Application\Bundle\DefaultBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Client;

/**
 * Test cases for UploadController
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class UploadControllerTest extends WebTestCase
{
    /** @var Client */
    protected $client;

    /**
     * @param $imageName
     * @param $responseCode
     * @param $responseData
     *
     * @dataProvider imageUploadProvider
     */
    public function testUploadValidImage($imageName, $responseCode, $responseData)
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
        ));
        $this->client = $this->makeClient();
        $this->doLogin();
        $path = tempnam(sys_get_temp_dir(), $imageName);
        copy(realpath(__DIR__ . '/../_files/' . $imageName), $path);

        $uploadFile = new UploadedFile($path, $imageName, null, null, null, true);

        $this->client->request(
            'POST', $this->getUrl('blog_post_upload_image'), array(),
            array('upload_file' => $uploadFile)
        );

        $this->assertEquals(
            $responseCode,
            $this->client->getResponse()->getStatusCode()
        );
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $actualResponseData = json_decode($this->client->getResponse()->getContent(), true);
        unset($actualResponseData['src']);
        $this->assertEquals(
            $responseData,
            $actualResponseData
        );
    }

    /**
     * Provider for the image upload test
     *
     * @return array
     */
    public static function imageUploadProvider()
    {
        $dataSets = array();

        // Valid image
        $dataSets[] = array(
            'image_valid.jpg',
            200,
            array(
                'status' => 'success',
                'width' => '600',
                'height' => '800',
            )
        );

        // Invalid image
        $dataSets[] = array(
            'image_invalid.jpg',
            400,
            array(
                'msg' => 'Your file is not valid!',
            )
        );

        return $dataSets;
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
        $crawler = $this->client->followRedirects();
    }
}