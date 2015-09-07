<?php
namespace Application\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Application\Bundle\DefaultBundle\Form\Type\PromotionOrderFormType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Promotion controller. For promotions actions
 *
 * @Route("/services/web-design")
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
        $translator = $this->get('translator');
        $canonicalUrl = $this->generateUrl('page_landing', ['type' => $type], true);
        $title = $translator->trans('landing_meta.title-' . $type);
        $description = $translator->trans('landing_meta.description-' . $type);
        $seoPage->addMeta('name', 'description', $description)
            ->addMeta('property', 'og:title', $title)
            ->addMeta('property', 'og:url', $canonicalUrl)
            ->addMeta('property', 'og:type', 'website')
            ->addMeta('property', 'og:description', $description)
            ->setLinkCanonical($canonicalUrl);

        $landingPage = $this->get('doctrine.orm.entity_manager')
            ->getRepository('StfalconPortfolioBundle:Landing')
            ->findOneBy(['slug' => $type]);

        $form = $this->createForm(new PromotionOrderFormType());

        return $this->render(
            'ApplicationDefaultBundle:Default:landing.html.twig',
            [
                'form' => $form->createView(),
                'landing_page' => $landingPage,
                'title' => $title,
            ]
        );
    }
}