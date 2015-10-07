<?php

namespace Stfalcon\Bundle\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 *
 * @ORM\Table(name="blog_comments")
 * @ORM\Entity(repositoryClass="Stfalcon\Bundle\BlogBundle\Repository\CommentRepository")
 */
class Comment
{
    use TimestampableEntity;

    /**
     * Comment ID
     *
     * @var int $id ID
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * User name
     *
     * @var string $name Name
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * User email
     *
     * @var string $email Email
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * User message
     *
     * @var string $message Message
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=false)
     *
     * @Assert\NotBlank()
     */
    private $message;

    /**
     * @param Collection|Comment[] $children Children
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="parent")
     */
    private $children;

    /**
     * @param Comment $parent Comment
     *
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @var Post $post Post
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\Type(type="object")
     */
    private $post;

    /**
     * @var string $language Language
     *
     * @ORM\Column(name="language", type="string", length=10, nullable=false)
     *
     * @Assert\NotBlank()
     */
    private $language;

    /**
     * To string method
     *
     * @return string
     */
    public function __toString()
    {
        $result = 'New comment';

        if ($this->getName()) {
            $result = $this->getName();
        }

        return $result;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * Get ID
     *
     * @return int ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get email
     *
     * @return string Email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get message
     *
     * @return string Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set message
     *
     * @param string $message message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get children
     *
     * @return mixed Children
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set children
     *
     * @param mixed $children children
     *
     * @return $this
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Add child
     *
     * @param Comment $child Child
     *
     * @return $this
     */
    public function addChild(Comment $child)
    {
        $child->setParent($this);

        $this->children->add($child);

        return $this;
    }

    /**
     * Get parent
     *
     * @return Comment Parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent
     *
     * @param mixed $parent parent
     *
     * @return $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get post
     *
     * @return Post Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set post
     *
     * @param Post $post post
     *
     * @return $this
     */
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get language
     *
     * @return string Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set language
     *
     * @param string $language language
     *
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }
}
