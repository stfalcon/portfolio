<?php

namespace Stfalcon\Bundle\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * Stfalcon\Bundle\BlogBundle\Entity\Tag
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 * @ORM\Table(name="blog_tags")
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="Stfalcon\Bundle\BlogBundle\Entity\TagTranslation")
 */
class Tag implements Translatable
{
    /**
     * Tag id
     *
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Tag text
     *
     * @var string $text
     * @Gedmo\Translatable(fallback=true)
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text = '';

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Stfalcon\Bundle\BlogBundle\Entity\Post", mappedBy="tags")
     */
    private $posts;

    /**
     * @ORM\OneToMany(
     *   targetEntity="TagTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

    /**
     * @Gedmo\Locale
     */
    private $locale;

    /**
     * Entity constructor
     *
     * @param string $text A tag text
     */
    public function  __construct($text = null)
    {
        $this->text = $text;
        $this->posts = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    /**
     * Get Tag id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Tag text
     *
     * @param string $text A tag text
     *
     * @return void
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Get Tag text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get posts for this tag
     *
     * @return ArrayCollection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * This method allows a class to decide how it will react when it is treated like a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getText();
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param $locale
     */
    public function setLocale($locale)
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

    /**
     * @param TagTranslation $t
     */
    public function addTranslation(TagTranslation $t)
    {
        $this->translations->add($t);
        $t->setObject($this);
    }

    /**
     * @param TagTranslation $t
     */
    public function removeTranslation(TagTranslation $t)
    {
        $this->translations->removeElement($t);
    }

    /**
     * @param $translations
     */
    public function setTranslations($translations)
    {
        $this->translations = $translations;
    }
}