<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Stfalcon\Bundle\BlogBundle\Bridge\Doctrine\Form\Type\SearchType;
use Stfalcon\Bundle\BlogBundle\Controller\AbstractController;
use Stfalcon\Bundle\BlogBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * SearchController
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class SearchController extends AbstractController
{
    /**
     * Render search form
     *
     * @return JsonResponse
     *
     * @Route(name="search")
     */
    public function searchPostsAction()
    {
        $form = $this->createForm(new SearchType());

        return $this->render('@StfalconBlog/Search/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request Request
     *
     * @return JsonResponse
     *
     * @throw NotFoundHttpException
     *
     * @Route("/ajax_search", name="ajax_search")
     */
    public function searchAjaxAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(new SearchType());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $locale = $request->getLocale();
            $text   = $form->get('searchPhrase')->getData();

            $searchedPosts = [];

            if (null !== $text) {
                $postsId        = $this->search($locale, $text, 'postSearchIndex');
                $postRepository = $this->getDoctrine()->getManager()->getRepository('StfalconBlogBundle:Post');
                $searchedPosts  = $postRepository->findAllInArray($postsId);
            }

            return new JsonResponse([
                'status' => 'success',
                'posts'  => $this->performPosts($searchedPosts),
            ]);
        }

        return new JsonResponse([
            'status'  => 'error',
            'message' => 'Form is not valid!',
        ]);
    }

    /**
     * Search method
     *
     * @param string $locale Current locale
     * @param string $text   Text to search
     * @param string $type   Sphinx search entity
     *
     * @return array Searched items id
     *
     * @throws \Exception
     * @throws \RuntimeException
     */
    private function search($locale, $text, $type)
    {
        $sphinxSearch = $this->get('iakumai.sphinxsearch.search');
        $sphinxSearch->SetMatchMode(SPH_MATCH_ANY);
        $sphinxSearch->SetFilter('locale', [
            crc32($locale),
        ]);

        try {
            $searchResults = $sphinxSearch->search('*'.$text.'*', [
                $type,
            ]);
        } catch (\Exception $e) {
            throw $e;
        }

        if (false === $searchResults) {
            throw new \RuntimeException('Sphinx not found!');
        }

        $items = [];
        if (isset($searchResults['matches'])) {
            foreach ($searchResults['matches'] as $itemAttr) {
                if (isset($itemAttr['attrs']['id'])) {
                    $items[] = $itemAttr['attrs']['id'];
                }
            }
        }

        return $items;
    }

    /**
     * Perform posts
     *
     * @param array $posts Posts
     *
     * @return array Performed posts
     */
    private function performPosts($posts)
    {
        $performedPosts = [];
        /** @var Post $post */
        foreach ($posts as $post) {
            $blogExtension = $this->get('twig.extension.blog');

            $postText = $blogExtension->cutTextToLimit($post->getText(), 275);

            $performedPosts[] = [
                'title'         => $post->getTitle(),
                'text'          => $postText,
                'preview_image' => $blogExtension->getPostFirstImagePath($post),
                'url'           => $this->generateUrl('blog_post_view', [
                    'slug' => $post->getSlug(),
                ]),
            ];
        }

        return $performedPosts;
    }
}
