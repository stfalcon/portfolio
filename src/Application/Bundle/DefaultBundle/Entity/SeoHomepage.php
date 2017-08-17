<?php

namespace Application\Bundle\DefaultBundle\Entity;

use Application\Bundle\DefaultBundle\Entity\Translation\SeoHomepageTranslation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * SeoHomepage Entity
 *
 * @ORM\Table(name="seo_homepage")
 * @ORM\Entity(repositoryClass="Application\Bundle\DefaultBundle\Repository\SeoHomepageRepository")
 *
 * @Gedmo\TranslationEntity(class="Application\Bundle\DefaultBundle\Entity\Translation\SeoHomepageTranslation")
 *
 * @Vich\Uploadable
 */
class SeoHomepage implements Translatable
{
    use TimestampableEntity;

    /**
     * @var integer $id ID
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection|SeoHomepageTranslation[] $translations Translations
     *
     * @ORM\OneToMany(
     *   targetEntity="Application\Bundle\DefaultBundle\Entity\Translation\SeoHomepageTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

    /**
     * @var string $title Title
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     *
     * @Gedmo\Translatable(fallback=true)
     */
    protected $title;

    /**
     * @var string $title Title
     *
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank()
     *
     * @Gedmo\Translatable(fallback=true)
     */
    protected $description;

    /**
     * @var string $keywords Keywords
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Length(max="255")
     *
     * @Gedmo\Translatable(fallback=true)
     */
    protected $keywords;

    /**
     * @var string $title Title
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     *
     * @Gedmo\Translatable(fallback=true)
     */
    protected $ogTitle;

    /**
     * @var string $title Title
     *
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank()
     *
     * @Gedmo\Translatable(fallback=true)
     */
    protected $ogDescription;

    /**
     * @var File $ogImageFile og:image file
     *
     * @Assert\File(
     *     maxSize="4M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="seo_homepage", fileNameProperty="ogImage")
     */
    protected $ogImageFile;

    /**
     * @var string $ogImage og:image image
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $ogImage;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    /**
     * To string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getID().'';
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title Title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description Description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get keywords
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set keywords
     *
     * @param string $keywords Keywords
     *
     * @return $this
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get og:title
     *
     * @return string
     */
    public function getOgTitle()
    {
        return $this->ogTitle;
    }

    /**
     * Set og:title
     *
     * @param string $ogTitle OG:title
     *
     * @return $this
     */
    public function setOgTitle($ogTitle)
    {
        $this->ogTitle = $ogTitle;

        return $this;
    }

    /**
     * Get og:description
     *
     * @return string
     */
    public function getOgDescription()
    {
        return $this->ogDescription;
    }

    /**
     * Set og:description
     *
     * @param string $ogDescription OG:description
     *
     * @return $this
     */
    public function setOgDescription($ogDescription)
    {
        $this->ogDescription = $ogDescription;

        return $this;
    }

    /**
     * Get og:image file
     *
     * @return File
     */
    public function getOgImageFile()
    {
        return $this->ogImageFile;
    }

    /**
     * Set ogImageFile
     *
     * @param File $ogImageFile OG:image
     *
     * @return $this
     */
    public function setOgImageFile($ogImageFile)
    {
        $this->ogImageFile = $ogImageFile;

        if ($ogImageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * Get og:image
     *
     * @return string
     */
    public function getOgImage()
    {
        return $this->ogImage;
    }

    /**
     * Set og:image
     *
     * @param string $ogImage OG:image
     *
     * @return $this
     */
    public function setOgImage($ogImage)
    {
        $this->ogImage = $ogImage;

        return $this;
    }

    /**
     * Add translations
     *
     * @param SeoHomepageTranslation $seoHomepageTranslation Seo homepage translation
     */
    public function addTranslations(SeoHomepageTranslation $seoHomepageTranslation)
    {
        if (!$this->translations->contains($seoHomepageTranslation)) {
            $this->translations->add($seoHomepageTranslation);
            $seoHomepageTranslation->setObject($this);
        }
    }
    /**
     * Add translation
     *
     * @param SeoHomepageTranslation $seoHomepageTranslation Seo homepage translation
     */
    public function addTranslation(SeoHomepageTranslation $seoHomepageTranslation)
    {
        if (!$this->translations->contains($seoHomepageTranslation)) {
            $this->translations->add($seoHomepageTranslation);
            $seoHomepageTranslation->setObject($this);
        }
    }

    /**
     * Remove translations
     *
     * @param SeoHomepageTranslation $seoHomepageTranslation Seo homepage translation
     */
    public function removeTranslation(SeoHomepageTranslation $seoHomepageTranslation)
    {
        $this->translations->removeElement($seoHomepageTranslation);
    }

    /**
     * Get translations
     *
     * @return ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Set translations
     *
     * @param ArrayCollection $translations Translations
     */
    public function setTranslations($translations)
    {
        $this->translations = $translations;
    }
}
