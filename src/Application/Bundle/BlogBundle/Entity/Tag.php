<?php

namespace Application\Bundle\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Stfalcon\Bundle\BlogBundle\Entity\Tag as BaseTag;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="blog_tags")
 * @ORM\Entity(repositoryClass="Application\Bundle\BlogBundle\Repository\TagRepository")
 */
class Tag extends BaseTag
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
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="tags")
     */
    protected $posts;

    /**
     * Entity constructor
     *
     * @param string $text A tag text
     */
    public function  __construct($text = null)
    {
        $this->text = $text;
        $this->posts = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     * @param ArrayCollection $posts
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
    }
}
