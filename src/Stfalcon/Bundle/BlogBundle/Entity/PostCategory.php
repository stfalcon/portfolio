<?php

namespace  Stfalcon\Bundle\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PostCategory.
 *
 * @ORM\Table(name="post_category")
 * @ORM\Entity()
 *
 * @Gedmo\TranslationEntity(class="Stfalcon\Bundle\BlogBundle\Entity\PostCategoryTranslation")
 */
class PostCategory implements Translatable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @Gedmo\Translatable(fallback=true)
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Stfalcon\Bundle\BlogBundle\Entity\Post", mappedBy="category")
     */
    private $posts;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Stfalcon\Bundle\BlogBundle\Entity\PostCategoryTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

    /**
     * PostCategory constructor.
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     * @return ArrayCollection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param ArrayCollection $posts
     *
     * @return $this
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param mixed $translations
     *
     * @return $this
     */
    public function setTranslations($translations)
    {
        $this->translations = $translations;

        return $this;
    }

    /**
     * @param $translation
     *
     * @return $this
     */
    public function addTranslation($translation)
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setObject($this);
        }

        return $this;
    }

    /**
     * @param $translation
     *
     * @return $this
     */
    public function removeTranslation($translation)
    {
        $this->translations->removeElement($translation);

        return $this;
    }
}
