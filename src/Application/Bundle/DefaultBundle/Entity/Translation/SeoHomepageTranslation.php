<?php

namespace Application\Bundle\DefaultBundle\Entity\Translation;

use Application\Bundle\DefaultBundle\Entity\SeoHomepage;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * SeoHomepageTranslation Entity
 *
 * @ORM\Entity()
 *
 * @ORM\Table(name="seo_homepage_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="seo_homepage_lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class SeoHomepageTranslation extends AbstractPersonalTranslation
{
    /**
     * Convenient constructor
     *
     * @param string $locale  locale
     * @param string $field   field
     * @param string $content content
     */
    public function __construct($locale = null, $field = null, $content = null)
    {
        $this->setLocale($locale);
        $this->setField($field);
        $this->setContent($content);
    }

    /**
     * @var SeoHomepage $object Seo homepage
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\DefaultBundle\Entity\SeoHomepage", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getLocale();
    }
}
