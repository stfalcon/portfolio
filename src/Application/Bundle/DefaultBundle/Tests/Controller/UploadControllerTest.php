<?php

namespace Application\Bundle\DefaultBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Test cases for UploadContoller
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class UploadControllerTest extends WebTestCase
{

    public function testUploadValidImage()
    {
        $validImageFile = tempnam(sys_get_temp_dir(), 'image_valid.jpg');
        copy(realpath(__DIR__ . '/../_files/image_valid.jpg'), $validImageFile);

        $photo = new UploadedFile($validImageFile, 'image_valid.jpg', null, null, null, true);

        $client = $this->makeClient(true);
        $crawler = $client->request(
            'POST', $this->getUrl('blog_post_upload_image'), array(),
            array('form' => array('inlineUploadFile' => $photo))
        );

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('html:contains("success")')->count());
    }

    public function testUploadInvalidImage()
    {
        $invalidImageFile = tempnam(sys_get_temp_dir(), 'image_invalid.jpg');
        copy(realpath(__DIR__ . '/../_files/image_invalid.jpg'), $invalidImageFile);

        $photo = new UploadedFile($invalidImageFile, 'image_invalid.jpg', null, null, null, true);

        $client = $this->makeClient(true);
        $crawler = $client->request(
            'POST', $this->getUrl('blog_post_upload_image'), array(),
            array('form' => array('inlineUploadFile' => $photo)));

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('html:contains("Your file is not valid")')->count());
    }
}