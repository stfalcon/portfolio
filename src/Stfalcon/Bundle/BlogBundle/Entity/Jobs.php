<?php

namespace Stfalcon\Bundle\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="jobs")
 * @ORM\Entity(repositoryClass="Stfalcon\Bundle\BlogBundle\Repository\JobsRepository")
 */
class Jobs
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
     * Job title
     *
     * @var string $title
     * @Assert\NotBlank()
     * @Gedmo\Translatable(fallback=true)
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string $description
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "3"
     * )
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string $slug
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "3"
     * )
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * Tags for post
     *
     * @var ArrayCollection
     * @Assert\NotBlank()
     * @ORM\ManyToMany(targetEntity="Stfalcon\Bundle\BlogBundle\Entity\Tag", inversedBy="posts")
     * @ORM\JoinTable(name="jobs_tags",
     *      joinColumns={@ORM\JoinColumn(name="jobs_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    private $tags;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_keywords", type="text", nullable=true)
     * @Gedmo\Translatable(fallback=true)
     */
    private $metaKeywords;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     * @Gedmo\Translatable(fallback=true)
     */
    private $metaDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_title", type="text", nullable=true)
     * @Gedmo\Translatable(fallback=true)
     */
    private $metaTitle;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }
}