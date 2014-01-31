<?php

namespace Stfalcon\Bundle\BlogBundle\Tests\Entity;

use Stfalcon\Bundle\BlogBundle\Entity\Post;
use Stfalcon\Bundle\BlogBundle\Entity\Tag;

/**
 * Test cases for post entity
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class PostEntityTest extends \PHPUnit_Framework_TestCase
{

    public function testEmptyPostId()
    {
        $post = new Post();
        $this->assertNull($post->getId());
        $this->assertEmpty($post->getTitle());
        $this->assertNull($post->getText());
        $this->assertNull($post->getSlug());
        $this->assertTrue(is_a($post->getTags(), 'Doctrine\Common\Collections\ArrayCollection'));
        $this->assertEquals(count($post->getTags()), 0);
    }

    public function testSetAndGetPostTitle()
    {
        $title = "A week of symfony #228 (9->15 May 2011)";

        $post = new Post();
        $post->setTitle($title);

        $this->assertEquals($post->getTitle(), $title);
    }

    public function testSetAndGetPostTag()
    {
        $slug = "valid-slug";

        $post = new Post();
        $post->setSlug($slug);

        $this->assertEquals($post->getSlug(), $slug);
    }

    public function testSetAndGetPostText()
    {
        $text = "This week, Symfony2 reintroduced parameters in the DIC of several bundles, error page template customization was greatly simplified and Assetic introduced configuration for automatically apply filters to assets based on path.";

        $post = new Post();
        $post->setText($text);

        $this->assertEquals($post->getText(), $text);
    }

    public function testAddTagToPostAndGetTags()
    {
        $post = new Post();
        $tag1 = new Tag('symfony2');
        $tag2 = new Tag('doctrine2');
        $post->setTags(array($tag1, $tag2));

        $this->assertEquals($post->getTags(), array($tag1, $tag2));
        $this->assertEquals(count($post->getTags()), 2);
    }

}