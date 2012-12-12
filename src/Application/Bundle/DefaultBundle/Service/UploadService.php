<?php

namespace Application\Bundle\DefaultBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService
{
    /**
     * @var string
     */
    protected $uploadRoot;

    /**
     * @var string
     */
    protected $uploadDir;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @param $config
     */
    public function __construct($config)
    {
        $this->uploadRoot = $config['upload_root'];
        $this->uploadDir  = $config['upload_dir'];
    }

    /**
     * Upload file to storage
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function upload(UploadedFile $file)
    {
        $this->fileName = uniqid() . '.' . $file->guessExtension();
        $file->move($this->getUploadRootDir(), $this->fileName);
    }


    /**
     * The absolute web path where from uploaded
     * documents should be download
     *
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->fileName
            ? null
            : $this->uploadDir.'/'. $this->fileName;
    }

    /**
     * The absolute directory path where uploaded
     * documents should be saved
     *
     * @return string
     */
    public function getUploadRootDir()
    {
        return $this->uploadRoot . $this->uploadDir;
    }

}