<?php

namespace Application\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * UploadController
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class UploadController extends Controller
{
    const RESPONSE_SUCCESS = 'success';

    /**
     * Upload photo
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @Route("/admin/blog/uploadImage", name="blog_post_upload_image")
     * @Method({"POST"})
     */
    public function uploadImageAction(Request $request)
    {
        /** @var $file \Symfony\Component\HttpFoundation\File\UploadedFile|null */
        $file = $request->files->get('upload_file');

        $fileConstraint = new Collection(array(
             'file' => array(
                 new NotBlank(),
                 new Image()
             ),
         ));

        // Validate
        /** @var $errors \Symfony\Component\Validator\ConstraintViolationList */
        $errors = $this->get('validator')->validateValue(array('file' => $file), $fileConstraint);
        if ($errors->count() > 0) {
            return new JsonResponse(array('msg' => 'Your file is not valid!'), 400);
        }

        $config = $this->container->getParameter('application_default.config');

        // Move uploaded file
        $newFileName = uniqid() . '.' . $file->guessExtension();
        $path = $config['web_root'] . $config['upload_dir'];
        try {
            $file->move($path, $newFileName);
        } catch (FileException $e) {
            return new JsonResponse(array('msg' => $e->getMessage()), 400);
        }

        // Get image width/height
        list($width, $height, $type, $attr) = getimagesize(
            $path . DIRECTORY_SEPARATOR . $newFileName
        );

        return new JsonResponse(
            $response = array(
                'status' => self::RESPONSE_SUCCESS,
                'src' => $config['upload_dir'] . '/' . $newFileName,
                'width' => $width,
                'height' => $height,
            )
        );
    }
}