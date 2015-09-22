<?php
namespace Application\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Application\Bundle\DefaultBundle\Form\Type\PromotionOrderFormType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Promotion controller. For promotions actions
 *
 * @Route("/services/web-development")
 */
class LandingController extends Controller
{
    /**
     * Promotion index page by type
     *
     * @Route("/{type}", requirements={"type" = "mobile-app-design|responsive-design|ui-design|ember-js|silex|sylius"}, name="page_landing")
     * @param string $type
     *
     * @return Response
     */
    public function indexAction($type)
    {
        $seoPage = $this->get('sonata.seo.page');
        $canonicalUrl = $this->generateUrl('page_landing', ['type' => $type], true);

        $landingPage = $this->get('doctrine.orm.entity_manager')
            ->getRepository('StfalconPortfolioBundle:Landing')
            ->findOneBy(['slug' => $type]);
        $seoPage->addMeta('name', 'description', $landingPage->getMetaDescription())
            ->addMeta('name', 'title', $landingPage->getMetaTitle())
            ->addMeta('name', 'keywords', $landingPage->getMetaKeywords())
            ->addMeta('property', 'og:title', $landingPage->getMetaTitle())
            ->addMeta('property', 'og:url', $canonicalUrl)
            ->addMeta('property', 'og:type', 'website')
            ->addMeta('property', 'og:description', $landingPage->getMetaDescription())
            ->setLinkCanonical($canonicalUrl);


        $form = $this->createForm(new PromotionOrderFormType());

        return $this->render(
            'ApplicationDefaultBundle:Default:landing.html.twig',
            [
                'form' => $form->createView(),
                'landing_page' => $landingPage,
                'title' => $landingPage->getMetaTitle(),
            ]
        );
    }
}