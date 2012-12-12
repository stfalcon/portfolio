<?php

namespace Application\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Application\Bundle\DefaultBundle\Model\UploadImage;

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
        $uploadImage = new UploadImage();
        if ($request->files->has('upload_file')) {
            $uploadImage->setFile($request->files->get('upload_file'));
        }

        /** @var $errors \Symfony\Component\Validator\ConstraintViolationList */
        $errors = $this->get('validator')->validate($uploadImage);
        if ($errors->count() > 0) {
            return new JsonResponse(array('msg' => 'Your file is not valid!'), 400);
        }

        /** @var $uploadService \Application\Bundle\DefaultBundle\Service\UploadService */
        $uploadService = $this->get('application_default.service.uploader');
        try {
            $uploadService->upload($uploadImage->getFile());
        } catch (FileException $e) {
            return new JsonResponse(array('msg' => $e->getMessage()), 400);
        }

        return new JsonResponse(
            $response = array(
                'status' => self::RESPONSE_SUCCESS,
                'src' => $uploadService->getWebPath(),
                'width' => $uploadImage->getWidth(),
                'height' => $uploadImage->getHeight(),
            )
        );
    }
}