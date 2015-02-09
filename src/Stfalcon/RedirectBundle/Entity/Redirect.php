<?php

namespace Stfalcon\RedirectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fresh\Bundle\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use Stfalcon\RedirectBundle\Entity\Types\RedirectCodeType;
use Stfalcon\RedirectBundle\Entity\Types\RedirectStatusType;
use Symfony\Component\Validator\Constraints as Assert;
use Stfalcon\RedirectBundle\Validator\Constraints as StfalconRedirectAssert;

/**
 * Stfalcon\RedirectBundle\Entity\Block
 *
 * @ORM\Table(name="redirects")
 * @ORM\Entity
 */
class Redirect
{
    /**
     * entity path
     */
    const ENTITY_NAME = 'StfalconRedirectBundle:Redirect';

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255)
     * @Assert\NotBlank
     * @StfalconRedirectAssert\UniversalUrl(global=false, message="The string &quot;{{ value }}&quot; is not valid internal address")
     */
    protected $slug;

    /**
     * @var string $target
     *
     * @ORM\Column(name="target", type="string", length=255)
     * @Assert\NotBlank
     * @StfalconRedirectAssert\UniversalUrl
     */
    protected $target;

    /**
     * @ORM\Column(name="status", type="RedirectStatusType", nullable=false)
     * @DoctrineAssert\Enum(
     *    entity="Stfalcon\RedirectBundle\Entity\Types\RedirectStatusType"
     * )
     *
     * @var string $status
     */
    protected $status = RedirectStatusType::DISABLED;

    /**
     * @ORM\Column(name="code", type="RedirectCodeType", nullable=false)
     * @DoctrineAssert\Enum(
     *    entity="Stfalcon\RedirectBundle\Entity\Types\RedirectCodeType"
     * )
     *
     * @var string $code
     */
    protected $code = RedirectCodeType::CODE_302;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function __toString()
    {
        return ($this->slug) ? : '-';
    }

    /**
     * Set target
     *
     * @param string $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * Get target
     *
     * @return string 
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return Redirect
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }
}
