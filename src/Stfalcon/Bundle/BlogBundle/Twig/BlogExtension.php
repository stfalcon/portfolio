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
    private $cachePathResolver;

    /**
     * @param CachePathResolver $cachePathResolver
     */
    public function __construct(CachePathResolver $cachePathResolver)
    {
        $this->cachePathResolver = $cachePathResolver;
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
    public function getPostFirstImage(Post $post, $filter, $host = '')
    {
        $return = '';
        $path   = $this->getPostFirstImagePath($post, $filter, $host);

        if (!empty($path)) {
            $return = '<img src="'.$path.'" alt="'.$post->getTitle().'">';
        }

        return $return;
    }

    /**
     * Get post first image path
     *
     * @param Post   $post   Current post
     * @param string $filter Image filter
     * @param string $host   Current host
     *
     * @return string
     */
    public function getPostFirstImagePath(Post $post, $filter, $host = '')
    {
        $imagePath = '';

        if (preg_match('~<img[^>]+src="([^"]+)"[^>]*>~i', $post->getText(), $matches)) {
            $imagePath = $this->cachePathResolver->getBrowserPath($matches[1], $filter);
        }

        return $imagePath;
    }

    /**
     * Delete post first image
     *
     * @param string $postText Post text
     *
     * @return string Post text
     */
    public function deletePostFirstImage($postText)
    {
        return preg_replace('~<img[^>]+src="([^"]+)"[^>]*>~i', '', $postText);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'blog_extension';
    }
}
