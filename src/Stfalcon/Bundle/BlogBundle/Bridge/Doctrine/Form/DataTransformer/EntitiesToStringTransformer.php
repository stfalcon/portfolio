<?php

namespace Stfalcon\Bundle\BlogBundle\Bridge\Doctrine\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * Transformer entities to string
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class EntitiesToStringTransformer implements DataTransformerInterface
{

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Constructor injection. Set entity manager to object
     *
     * @param Doctrine\ORM\EntityManager $em Entity manager object
     *
     * @return void
     */
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms tags entities into string (separated by comma)
     *
     * @param Collection|null $collection A collection of entities or NULL
     *
     * @return string|null An string of tags or NULL
     */
    public function transform($collection)
    {
        if (null === $collection) {
            return null;
        }

        if (!($collection instanceof Collection)) {
            throw new UnexpectedTypeException($collection, 'Doctrine\Common\Collections\Collection');
        }

        $array = array();
        foreach ($collection as $entity) {
            array_push($array, $entity->getText());
        }

        return implode(', ', $array);
    }

    /**
     * Transforms string into tags entities
     *
     * @param string|null $data Input string data
     *
     * @return Collection|null
     */
    public function reverseTransform($data)
    {
        $collection = new ArrayCollection();

        if ('' === $data || null === $data) {
            return $collection;
        }

        if (!is_string($data)) {
            throw new UnexpectedTypeException($data, 'string');
        }

        foreach ($this->_stringToArray($data) as $text) {
            $tag = $this->em->getRepository("StfalconBlogBundle:Tag")
                    ->findOneBy(array('text' => $text));
            if (!$tag) {
                $tag = new \Stfalcon\Bundle\BlogBundle\Entity\Tag($text);
                $this->em->persist($tag);
            }
            $collection->add($tag);
        }

        return $collection;
    }

    /**
     * Convert string of tags to array
     *
     * @param string $string
     */
    private function _stringToArray($string)
    {
        $tags = explode(',', $string);
        // strip whitespaces from beginning and end of a tag text
        foreach ($tags as &$text) {
            $text = trim($text);
        }

        // removes duplicates
        return array_unique($tags);
    }

}