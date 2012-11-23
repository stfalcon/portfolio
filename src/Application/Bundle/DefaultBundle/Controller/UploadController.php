<?php

namespace Application\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * UploadController
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class UploadController extends Controller
{

    /**
     * Upload photo
     *
     * @return Response
     * @Route("/admin/blog/uploadImage", name="blog_post_upload_image")
     * @Method({"POST"})
     */
    public function uploadImageAction()
    {
        $config = $this->container->getParameter('application_default.config');

        $collectionConstraint = new Collection(array(
            'inlineUploadFile' => new Image(array('mimeTypes' => array("image/png", "image/jpeg", "image/gif"))),
        ));

        if (isset($_FILES)) {
        $fileContainer = $this->getRequest()->files->get('form');

        $file = $fileContainer['inlineUploadFile'];

        $errors = $this->container->get('validator')->validateValue(array('inlineUploadFile' => $file), $collectionConstraint);
            if ($errors->count()) {
                $response = array(
                    'msg' => 'Your file is not valid!',
                );
            } else {
                $uploadDir = $config['upload_dir'];
                $newName = uniqid() . '.' . $file->guessExtension();
                $file->move($uploadDir, $newName);
                list($width, $height, $type, $attr) = getImageSize($uploadDir . '/' . $newName);

                $response = array(
                    'status' => 'success',
                    'src' => '/uploads/images/' . $newName,
                    'width' => $width,
                    'height' => $height,
                );
            }

        } else {
            $response = array(
                    'msg' => 'Your file is not valid!',
                );
        }

        return new Response(json_encode($response));
    }
}