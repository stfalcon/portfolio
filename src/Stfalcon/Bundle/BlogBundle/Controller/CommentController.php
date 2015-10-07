<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Stfalcon\Bundle\BlogBundle\Bridge\Doctrine\Form\Type\NewCommentType;
use Stfalcon\Bundle\BlogBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Stfalcon\Bundle\BlogBundle\Entity\Post;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller for actions with DISQUS comments
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class CommentController extends Controller
{

    /**
     * Synchronization comments count with disqus
     *
     * @param Post $post Post object
     *
     * @return Response
     * @Route("/blog/post/{slug}/disqus-sync", name="blog_post_disqus_sync")
     */
    public function disqusSyncAction(Post $post)
    {
        // @todo. нужно доставать полный список ЗААПРУВЛЕННЫХ комментариев или
        // колличество комментариев к записи (если такой метод появится в API disqus)
        // после чего обновлять их колличество в БД

        if (false == $this->getRequest()->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        $post->setCommentsCount($post->getCommentsCount() + 1);
        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($post);
        $em->flush($post);

        return new Response(json_encode('ok'));
    }

    /**
     * Get comments action
     *
     * @param Request $request Request
     * @param Post    $post    Post
     * @param int     $page    Current page
     *
     * @return Response
     *
     * @Route("/blog/post/{id}/comments/{page}", name="blog_post_comments")
     * @ParamConverter("post", class="StfalconBlogBundle:Post")
     */
    public function getCommentsAction(Request $request, Post $post, $page)
    {
        $commentRepository = $this->getDoctrine()->getManager()->getRepository('StfalconBlogBundle:Comment');

        /** @var Comment[] $comments */
        $comments = $commentRepository->findCommentsByPost($post, $request->getLocale());

        $comments = $this->get('knp_paginator')->paginate($comments, $page, 20);

        return $this->render('StfalconBlogBundle:Comment:commentsWidget.html.twig', [
            'post'     => $post,
            'comments' => $comments,
        ]);
    }

    /**
     * New comment action
     *
     * @param Post $post Post
     *
     * @return Response
     *
     * @Route("/blog/post/{id}/comment", name="blog_post_comment_form")
     * @ParamConverter("post", class="StfalconBlogBundle:Post")
     */
    public function commentFormAction(Post $post)
    {
        $form = $this->createForm(new NewCommentType());

        return $this->render('@StfalconBlog/Comment/newComment.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    /**
     * Add new comment action
     *
     * @param Request $request Request
     * @param Post    $post    Post
     *
     * @Route("/blog/post/{id}/add_comment", name="blog_post_add_comment")
     * @ParamConverter("post", class="StfalconBlogBundle:Post")
     *
     * @throw NotFoundHttpException
     *
     * @return Response
     */
    public function addCommentAction(Request $request, Post $post)
    {
        if (!$post) {
            throw new NotFoundHttpException('Post not found');
        }

        if (!$request->isMethod('POST')) {
            throw new BadRequestHttpException();
        }

        $comment = (new Comment())->setPost($post);
        $form = $this->createForm(new NewCommentType(), $comment);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var Comment $comment */
            $comment = $form->getData();

            $post->addComment($comment);

            $em->persist($post);
            $em->persist($comment);

            $em->flush();

            $resultMessage = 'Comment successfully added!';
            $this->get('session')->getFlashBag()->add('notice', $resultMessage);
        } else {
            $resultMessage = 'Form is not valid!';
            $this->get('session')->getFlashBag()->add('error', $resultMessage);
        }

        return new JsonResponse([
            'status'  => 'ok',
            'message' => $resultMessage,
        ]);
    }
}
