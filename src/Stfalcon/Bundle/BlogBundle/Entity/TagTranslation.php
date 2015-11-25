<?php

namespace Stfalcon\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * TagTranslation
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="blog_tags_translations")
 */
class TagTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="Tag", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;

    /**
     * To string method
     *
     * @return string
     */
    public function __toString()
    {
        $result = 'New tag translation';

        if ($this->getLocale()) {
            $result = $this->getLocale();
        }

        return $result;
    }
}
