<?php

namespace Stfalcon\Bundle\BlogBundle\Entity;

use Application\Bundle\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Post entity
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 * @ORM\Table(name="blog_posts")
 * @ORM\Entity(repositoryClass="Stfalcon\Bundle\BlogBundle\Repository\PostRepository")
 */
class Post
{
    /**
     * Post id
     *
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Post title
     *
     * @var string $title
     * @Assert\NotBlank()
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title = '';

    /**
     * @var string $slug
     *
     * @Assert\NotBlank()
     * @Assert\MinLength(3)
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * Post text
     *
     * @var string $text
     * @Assert\NotBlank()
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * Post text as HTML code
     *
     * @var string $textAsHtml
     * @ORM\Column(name="text_as_html", type="text")
     */
    private $textAsHtml;

    /**
     * Tags for post
     *
     * @var ArrayCollection
     * @Assert\NotBlank()
     * @ORM\ManyToMany(targetEntity="Stfalcon\Bundle\BlogBundle\Entity\Tag")
     * @ORM\JoinTable(name="blog_posts_tags",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    private $tags;

    /**
     * @var \DateTime $created
     *
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;

    /**
     * @var int $commentsCount
     *
     * @ORM\Column(type="integer")
     */
    private $commentsCount = 0;

    /**
     * @var User $author
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    protected $author;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $published = true;

    /**
     * Initialization properties for new post entity
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->created = new \DateTime();
    }

    /**
     * Get post id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tags to post
     *
     * @param ArrayCollection $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * Get all tags
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
    }

    /**
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Set post title
     *
     * @param string $title Text of the title
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get post title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set post slug
     *
     * @param string $slug Unique text identifier
     *
     * @return void
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get post slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set post text
     *
     * @param string $text Text for post
     *
     * @return void
     */
    public function setText($text)
    {
        $this->text = $text;
        $this->textAsHtml = $this->_transformTextAsHtml($text);
    }

    /**
     * Get post text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get post text as HTML code
     *
     * @return string
     */
    public function getTextAsHtml()
    {
        return $this->textAsHtml;
    }


    /**
     * Transform post text to html
     *
     * @param string $text Source post text
     *
     * @return string Post text as html
     */
    private function _transformTextAsHtml($text)
    {
        // update text html code
        require_once __DIR__ . '/../Resources/vendor/geshi/geshi.php';

        $text = preg_replace_callback(
            '/<pre lang="(.*?)">\r?\n?(.*?)\r?\n?\<\/pre>/is',
            function($data) {
                $geshi = new \GeSHi($data[2], $data[1]);

                return $geshi->parse_code();
            }, $text
        );

        return $text;
    }

    /**
     * Set time when post created
     *
     * @param \DateTime $created A time when post created
     *
     * @return void
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }

    /**
     * Get time when post created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set time when post updated
     *
     * @param \DateTime $updated A time when post updated
     *
     * @return void
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;
    }

    /**
     * Get time when post updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set comments count for post
     *
     * @param int $commentsCount A count of comments for post
     *
     * @return void
     */
    public function setCommentsCount($commentsCount)
    {
        $this->commentsCount = $commentsCount;
    }

    /**
     * Get comments count for post
     *
     * @return int
     */
    public function getCommentsCount()
    {
        return $this->commentsCount;
    }

    /**
     * This method allows a class to decide how it will react when it is treated like a string
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
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param boolean $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
    }

    /**
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    public function isPublished()
    {
        return (bool) $this->published;
    }
}