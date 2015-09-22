<?php


namespace Stfalcon\Bundle\PortfolioBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Project entity
 *
 * @ORM\Table(name="portfolio_landing")
 * @ORM\Entity()
 * @Gedmo\TranslationEntity(class="Stfalcon\Bundle\PortfolioBundle\Entity\LandingTranslation")
 */
class Landing implements Translatable
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "3"
     * )
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;


    /**
     * @var string $name
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "3"
     * )
     * @Gedmo\Translatable(fallback=true)
     * @ORM\Column(name="title", type="text")
     */
    private $title;


    /**
     * @var string $name
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "3"
     * )
     * @Gedmo\Translatable(fallback=true)
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var  ArrayCollection
     *
     * @ORM\OneToMany(
     *   targetEntity="LandingTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @param LandingTranslation $landingTranslation
     */
    public function addTranslations(LandingTranslation $landingTranslation)
    {
        if (!$this->translations->contains($landingTranslation)) {
            $this->translations->add($landingTranslation);
            $landingTranslation->setObject($this);
        }
    }

    /**
     * @param LandingTranslation $landingTranslation
     */
    public function addTranslation(LandingTranslation $landingTranslation)
    {
        if (!$this->translations->contains($landingTranslation)) {
            $this->translations->add($landingTranslation);
            $landingTranslation->setObject($this);
        }
    }

    /**
     * @param LandingTranslation $landingTranslation
     */
    public function removeTranslation(LandingTranslation $landingTranslation)
    {
        $this->translations->removeElement($landingTranslation);
    }

    /**
     * @param ArrayCollection $translations
     */
    public function setTranslations($translations)
    {
        $this->translations = $translations;
    }

    /**
     * @param string $locale
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }
}