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
        $form = $this->createFormBuilder(null, array(
            'csrf_protection' => false,
            'validation_constraint' => $collectionConstraint
            ))
                ->add('inlineUploadFile', 'file')
                ->getForm();

        $form->bindRequest($this->get('request'));
        if ($form->isValid()) {
            $file = $form->get('inlineUploadFile')->getData();
            $ext = $file->guessExtension();

            if (!$ext) {
                $response = array(
                    'msg' => 'Your file is not valid!',
                );
            } else {
                $uploadDir = $config['upload_dir'];
                $newName = uniqid() . '.' . $ext;
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