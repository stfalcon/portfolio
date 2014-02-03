<?php
namespace Application\Bundle\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseMedia;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="portfolio_media")
 */
class Media extends BaseMedia
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
     * @ORM\OneToMany(targetEntity="GalleryHasMedia", mappedBy="media")
     */
    protected $galleryHasMedias;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
