<?php

namespace Application\Bundle\DefaultBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Test cases for UploadController
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class UploadControllerTest extends WebTestCase
{

    /**
     * @param $imageName
     * @param $responseCode
     * @param $responseData
     *
     * @dataProvider imageUploadProvider
     */
    public function testUploadValidImage($imageName, $responseCode, $responseData)
    {
        $path = tempnam(sys_get_temp_dir(), $imageName);
        copy(realpath(__DIR__ . '/../_files/' . $imageName), $path);

        $uploadFile = new UploadedFile($path, $imageName, null, null, null, true);

        $client = $this->makeClient(true);
        $client->request(
            'POST', $this->getUrl('blog_post_upload_image'), array(),
            array('upload_file' => $uploadFile)
        );

        $this->assertEquals(
            $responseCode,
            $client->getResponse()->getStatusCode()
        );
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $actualResponseData = json_decode($client->getResponse()->getContent(), true);
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
}