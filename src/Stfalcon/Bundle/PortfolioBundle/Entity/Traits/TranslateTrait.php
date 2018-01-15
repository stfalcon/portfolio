<?php

namespace Stfalcon\Bundle\PortfolioBundle\Entity\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

/**
 * Trait TranslateTrait
 */
trait TranslateTrait
{
    /**
     * @param AbstractPersonalTranslation $translation
     */
    public function addTranslation($translation)
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setObject($this);
        }
    }
    /**
     * @param AbstractPersonalTranslation $translation
     */
    public function addTranslations($translation)
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setObject($this);
        }
    }
    /**
     * @param AbstractPersonalTranslation $translation
     */
    public function removeTranslation($translation)
    {
        if ($this->translations->contains($translation)) {
            $this->translations->removeElement($translation);
        }
    }

    /**
     * @param ArrayCollection $translations
     */
    public function setTranslations($translations)
    {
        $this->translations = $translations;
    }

    /**
     * @return ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }
}
