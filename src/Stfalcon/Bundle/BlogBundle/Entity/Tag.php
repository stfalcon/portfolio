<?php

namespace Stfalcon\Bundle\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Stfalcon\Bundle\BlogBundle\Entity\TagTranslation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Stfalcon\Bundle\BlogBundle\Entity\Tag
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 * @ORM\Table(name="blog_tags")
 * @ORM\Entity
 *
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
     *
     * @ORM\Column(name="text", type="string", length=255)
     *
     * @Gedmo\Translatable(fallback=true)
     *
     * @Assert\NotBlank()
     */
    private $text = '';

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Stfalcon\Bundle\BlogBundle\Entity\Post", mappedBy="tags")
     */
    private $posts;

    /**
     * @var Collection|TagTranslation[] $translations
     *
     * @ORM\OneToMany(targetEntity="TagTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    private $translations;

    /**
     * Entity constructor
     *
     * @param string $text A tag text
     */
    public function  __construct($text = null)
    {
        $this->text         = $text;
        $this->posts        = new ArrayCollection();
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
     * @return Tag
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
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
     * @param TagTranslation $tagTranslation
     *
     * @return Tag
     */
    public function addTranslation(TagTranslation $tagTranslation)
    {
        if (!$this->translations->contains($tagTranslation)) {
            $this->translations->add($tagTranslation);
            $tagTranslation->setObject($this);
        }

        return $this;
    }

    /**
     * Remove translation
     *
     * @param TagTranslation $tagTranslation
     *
     * @return Tag
     */
    public function removeTranslation(TagTranslation $tagTranslation)
    {
        $this->translations->removeElement($tagTranslation);

        return $this;
    }

    /**
     * Set translations
     *
     * @param ArrayCollection $translations
     */
    public function setTranslations($translations)
    {
        $this->translations = $translations;
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
}
