<?php

namespace Stfalcon\Bundle\BlogBundle\Entity;

use Application\Bundle\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Post entity.
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 * @ORM\Table(name="blog_posts")
 * @ORM\Entity(repositoryClass="Stfalcon\Bundle\BlogBundle\Repository\PostRepository")
 * @Gedmo\TranslationEntity(class="Stfalcon\Bundle\BlogBundle\Entity\PostTranslation")
 */
class Post implements Translatable
{
    /**
     * Post id.
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Post title.
     *
     * @var string
     *
     * @Gedmo\Translatable(fallback=true)
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "3"
     * )
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * Post text.
     *
     * @var string
     *
     * @Gedmo\Translatable(fallback=true)
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;

    /**
     * Tags for post.
     *
     * @var ArrayCollection
     * @Assert\NotBlank()
     * @ORM\ManyToMany(targetEntity="Stfalcon\Bundle\BlogBundle\Entity\Tag", inversedBy="posts")
     * @ORM\JoinTable(name="blog_posts_tags",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    private $tags;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $commentsCount = 0;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    protected $author;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $published = true;

    /**
     * @ORM\OneToMany(
     *   targetEntity="PostTranslation",
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
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

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

    /**
     * Initialization properties for new post entity.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->created = new \DateTime();
        $this->translations = new ArrayCollection();
    }

    /**
     * Get post id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tags to post.
     *
     * @param ArrayCollection $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get all tags.
     *
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Tag $tag
     */
    public function addTag(Tag $tag)
    {
        $this->tags->add($tag);

        return $this;
    }

    /**
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * Set post title.
     *
     * @param string $title Text of the title
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get post title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set post slug.
     *
     * @param string $slug Unique text identifier
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get post slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set post text.
     *
     * @param string $text Text for post
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get post text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set time when post created.
     *
     * @param \DateTime $created A time when post created
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get time when post created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set time when post updated.
     *
     * @param \DateTime $updated A time when post updated
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get time when post updated.
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set comments count for post.
     *
     * @param int $commentsCount A count of comments for post
     */
    public function setCommentsCount($commentsCount)
    {
        $this->commentsCount = $commentsCount;

        return $this;
    }

    /**
     * Get comments count for post.
     *
     * @return int
     */
    public function getCommentsCount()
    {
        return $this->commentsCount;
    }

    /**
     * This method allows a class to decide how it will react when it is treated like a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @param User $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param bool $published
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return bool
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return (bool) $this->published;
    }

    /**
     * @param PostTranslation $postTranslation
     */
    public function addTranslations(PostTranslation $postTranslation)
    {
        if (!$this->translations->contains($postTranslation)) {
            $this->translations->add($postTranslation);
            $postTranslation->setObject($this);
        }

        return $this;
    }

    /**
     * @param PostTranslation $postTranslation
     */
    public function addTranslation(PostTranslation $postTranslation)
    {
        if (!$this->translations->contains($postTranslation)) {
            $this->translations->add($postTranslation);
            $postTranslation->setObject($this);
        }

        return $this;
    }

    /**
     * @param PostTranslation $postTranslation
     */
    public function removeTranslation(PostTranslation $postTranslation)
    {
        $this->translations->removeElement($postTranslation);

        return $this;
    }

    /**
     * @param ArrayCollection $translations
     */
    public function setTranslations($translations)
    {
        $this->translations = $translations;

        return $this;
    }

    /**
     * @param string $locale
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * @param string $metaKeywords
     *
     * @return Post
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaDescription
     *
     * @return Post
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * @return string
     */
    public function getMetaTitle()
    {
        if (empty($this->metaTitle)) {
            return $this->getTitle();
        }

        return $this->metaTitle;
    }

    /**
     * @param string $metaTitle
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return Post
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
}
