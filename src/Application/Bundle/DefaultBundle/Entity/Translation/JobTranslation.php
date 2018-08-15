<?php

namespace Application\Bundle\DefaultBundle\Entity\Translation;

use Application\Bundle\DefaultBundle\Entity\SeoHomepage;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * JobTranslation Entity
 *
 * @ORM\Entity()
 *
 * @ORM\Table(name="job_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="job_lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class JobTranslation extends AbstractPersonalTranslation
{
    /**
     * @var SeoHomepage $object Seo homepage
     *
     * @ORM\ManyToOne(targetEntity="Application\Bundle\DefaultBundle\Entity\Job", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;

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
     * @return string
     */
    public function __toString()
    {
        return $this->getLocale();
    }
}
