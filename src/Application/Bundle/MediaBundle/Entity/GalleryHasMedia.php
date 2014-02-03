<?php
namespace Application\Bundle\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseGalleryHasMedia;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="portfolio_media_gallery_has_media")
 */
class GalleryHasMedia extends BaseGalleryHasMedia
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Media
     * @ORM\ManyToOne(targetEntity="Media", inversedBy="galleryHasMedias")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id")
     */
    protected $media;

    /**
     * @var Gallery
     * @ORM\ManyToOne(targetEntity="Gallery", inversedBy="galleryHasMedias")
     * @ORM\JoinColumn(name="galery_id", referencedColumnName="id")
     */
    protected $gallery;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
