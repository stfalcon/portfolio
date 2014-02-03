<?php
namespace Application\Bundle\MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Sonata\MediaBundle\Entity\BaseGallery;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="portfolio_media_gallery")
 */
class Gallery extends BaseGallery
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="GalleryHasMedia", mappedBy="gallery")
     */
    protected $galleryHasMedias;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
