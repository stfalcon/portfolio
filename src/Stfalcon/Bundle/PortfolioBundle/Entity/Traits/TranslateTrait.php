<?php

namespace Stfalcon\Bundle\PortfolioBundle\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Stfalcon\Bundle\PortfolioBundle\Entity\Translation\TranslatableEntity;

trait TranslateTrait
{
    /**
     * @param TranslatableEntity $translation
     */
    public function addTranslation($translation)
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setObject($this);
        }
    }
    /**
     * @param TranslatableEntity $translation
     */
    public function addTranslations($translation)
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setObject($this);
        }
    }
    /**
     * @param TranslatableEntity $translation
     */
    public function removeTranslation($translation)
    {
        $this->translations->removeElement($translation);
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