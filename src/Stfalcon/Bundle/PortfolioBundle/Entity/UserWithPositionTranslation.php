<?php

namespace Stfalcon\Bundle\PortfolioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * UserWithPositionTranslation class
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 *
 * @ORM\Entity()
 * @ORM\Table(name="portfolio_users_with_positions_translations")
 */
class UserWithPositionTranslation extends AbstractPersonalTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="UserWithPosition", inversedBy="translations")
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
