<?php

namespace Application\Bundle\DefaultBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadImage
{
    /**
     * @Assert\Image(maxSize="6000000")
     * @Assert\NotBlank()
     * @var UploadedFile
     */
    protected $file;

    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @param $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
        list($width, $height, $type, $attr) = getimagesize($file->getRealPath());
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

}