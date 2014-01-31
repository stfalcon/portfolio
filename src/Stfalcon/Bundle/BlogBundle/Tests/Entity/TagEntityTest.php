<?php

namespace Stfalcon\Bundle\BlogBundle\Tests\Entity;

use Stfalcon\Bundle\BlogBundle\Entity\Tag;

/**
 * Test cases for tag entity
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class TagEntityTest extends \PHPUnit_Framework_TestCase
{

    public function testEmptyTagIdIsNull()
    {
        $tag = new Tag();
        $this->assertNull($tag->getId());
    }

    public function testSetAndGetTagText()
    {
        $text = "symfony2";

        $tag = new Tag();
        $tag->setText($text);

        $this->assertEquals($tag->getText(), $text);
    }

    public function testTagConstructor()
    {
        $text = "symfony2";
        $tag = new Tag('symfony2');

        $this->assertEquals($tag->getText(), $text);
    }

}