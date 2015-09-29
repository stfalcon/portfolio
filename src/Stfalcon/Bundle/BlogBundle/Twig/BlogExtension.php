<?php
namespace Stfalcon\Bundle\BlogBundle\Twig;

use Avalanche\Bundle\ImagineBundle\Imagine\CachePathResolver;
use Stfalcon\Bundle\BlogBundle\Entity\Post;

/**
 * Class BlogExtension
 */
class BlogExtension extends \Twig_Extension
{
    /**
     * @var CachePathResolver
     */
    private $cacheManager;

    /**
     * @param CachePathResolver $cacheManager
     */
    public function __construct(CachePathResolver $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('get_post_first_image', [$this, 'getPostFirstImage'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Get post first image
     *
     * @param Post   $post   Current post
     * @param string $filter Image filter
     * @param string $host   Current host
     *
     * @return string
     */
    public function getPostFirstImage(Post $post, $filter, $host)
    {
        $return = '';

        if (preg_match('~<img[^>]+src="([^"]+)"[^>]*>~i', $post->getText(), $matches)) {
            $imagePath = $this->cacheManager->getBrowserPath($matches[1], $filter);

            $return = '<img src="'.$imagePath.'" alt="'.$post->getTitle().'">';
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'blog_extension';
    }
}
