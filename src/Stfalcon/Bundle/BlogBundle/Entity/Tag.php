<?php

namespace Stfalcon\Bundle\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Stfalcon\Bundle\BlogBundle\Entity\Tag
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 * @ORM\Table(name="blog_tags")
 * @ORM\Entity
 */
class Tag
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
     * @var text $text
     * @Assert\NotBlank()
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text = '';

    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Stfalcon\Bundle\BlogBundle\Entity\Post", mappedBy="tags")
     */
    private $posts;

    /**
     * Entity constructor
     *
     * @param string $text A tag text
     *
     * @return void
     */
    public function  __construct($text = null)
    {
        $this->text = $text;
        $this->posts = new ArrayCollection();
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
}