<?php

namespace Stfalcon\Bundle\PortfolioBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Translatable\Translatable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Stfalcon\Bundle\PortfolioBundle\Entity\Traits\TimestampAbleTrait;
use Stfalcon\Bundle\PortfolioBundle\Traits\TranslateTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class ProjectReviewer.
 *
 * @ORM\Table(name="portfolio_projects_reviewer")
 * @ORM\Entity(repositoryClass="Stfalcon\Bundle\PortfolioBundle\Repository\ProjectReviewerRepository")
 *
 * @Gedmo\TranslationEntity(class="Stfalcon\Bundle\PortfolioBundle\Entity\Translation\ProjectReviewerTranslation")
 *
 * @Vich\Uploadable
 */
class ProjectReviewer implements Translatable
{
    use TimestampAbleTrait;
    use TranslateTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Stfalcon\Bundle\PortfolioBundle\Entity\ProjectReview", mappedBy="reviewer"
     * , cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $projectReviews;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="3")
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     *
     * @Gedmo\Translatable(fallback=true)
     */
    private $name;

    /**
     * @var File
     *
     * @Assert\File(
     *     maxSize="4M",
     *     mimeTypes={"image/png", "image/jpeg", "image/jpg"}
     * )
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="foto")
     */
    protected $fotoFile;

    /**
     * @var string
     *
     * @ORM\Column(name="foto", type="string", length=255, nullable=true)
     */
    private $foto;

    public function __construct()
    {
        $this->projectReviews = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getProjectReviews()
    {
        return $this->projectReviews;
    }

    /**
     * @param ArrayCollection $projectReviews
     *
     * @return $this
     */
    public function setProjectReviews($projectReviews)
    {
        $this->projectReviews = $projectReviews;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return File
     */
    public function getFotoFile()
    {
        return $this->fotoFile;
    }

    /**
     * @param File $fotoFile
     *
     * @return $this
     */
    public function setFotoFile($fotoFile)
    {
        $this->setUpdatedAt(new \DateTime());
        $this->fotoFile = $fotoFile;

        return $this;
    }

    /**
     * @return string
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * @param string $foto
     *
     * @return $this
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }
}
