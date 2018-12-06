<?php
namespace Stfalcon\Bundle\BlogBundle\Twig;

use Avalanche\Bundle\ImagineBundle\Imagine\CachePathResolver;
use Stfalcon\Bundle\BlogBundle\Entity\Post;
use Stfalcon\Bundle\BlogBundle\Entity\PostTranslation;

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
    public function getPostFirstImage(Post $post, $filter = '', $host = '')
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
    public function getPostFirstImagePath(Post $post, $filter = '', $host = '')
    {
        $imagePath = '';
        $postText = $post->getText();
        if (null === $postText || empty($postText)) {
            /** @var PostTranslation $translation */
            foreach ($post->getTranslations() as $translation) {
                $content = $translation->getContent();
                if ($translation->getField() === 'text' && null !== $content && !empty($content)) {
                    $postText = $content;
                    break;
                }
            }
        }

        if (preg_match('~<img[^>]+src="([^"]+)"[^>]*>~i', $postText, $matches)) {
            $imagePath = $this->cachePathResolver->getBrowserPath($matches[1], $filter);
        }

        if ('' === $filter && isset($matches[1])) {
            $imagePath = $matches[1];
        }

        return $imagePath;
    }

    /**
     * Cut text to specific limit
     *
     * @param string $string Text
     * @param int    $limit  Limit
     *
     * @return string
     */
    public function cutTextToLimit($string, $limit = 275)
    {
        $string = strip_tags($string);

        if (strlen($string) > $limit) {
            $stringCut = substr($string, 0, $limit);

            $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...';
        }

        return $string;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'blog_extension';
    }
}
