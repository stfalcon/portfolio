<?php

namespace Stfalcon\Bundle\PortfolioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirect controller
 */
class RedirectController extends Controller
{
    /**
     * @param string $categorySlug
     * @param string $projectSlug
     *
     * @return array
     * @Route("/portfolio/{categorySlug}/{projectSlug}",
     *  requirements={"projectSlug" = "uaroads|meinfernbus-de|wallpaperinua|swell-foop-android",
     *                "categorySlug" = "web-development|web-design|mobile-development"})
     */
    public function redirectOldPortfolioLinksAction($categorySlug, $projectSlug)
    {
        switch (true) {
            case ($categorySlug == 'web-development' && $projectSlug == 'uaroads'):
                $redirectUrl = $this->generateUrl(
                    'portfolio_project_view',
                    [
                        'categorySlug' => $categorySlug,
                        'projectSlug'  => 'uaroads-com'
                    ]
                );
                break;
            case ($categorySlug == 'web-design' && $projectSlug == 'meinfernbus-de'):
                $redirectUrl = $this->generateUrl(
                    'portfolio_project_view',
                    [
                        'categorySlug' => 'web-development',
                        'projectSlug'  => $projectSlug
                    ]
                );
                break;
            case ($categorySlug == 'web-design' && $projectSlug == 'wallpaperinua'):
                $redirectUrl = $this->generateUrl(
                    'portfolio_project_view',
                    [
                        'categorySlug' => 'web-development',
                        'projectSlug'  => 'wallpaper-in-ua-v2'
                    ]
                );
                break;
            case ($categorySlug == 'mobile-development' && $projectSlug == 'swell-foop-android'):
                $redirectUrl = $this->generateUrl(
                    'portfolio_project_view',
                    [
                        'categorySlug' => 'game-development',
                        'projectSlug'  => $projectSlug
                    ]
                );
                break;
            default:
                return $this->forward('StfalconPortfolioBundle:Project:view', [
                    'categorySlug' => $categorySlug,
                    'projectSlug'  => $projectSlug
                ]);

        }

        return $this->redirect($redirectUrl, 301);
    }

    /**
     * @param string $categorySlug
     * @param int    $page
     *
     * @return Response
     *
     * @Route("/portfolio/{categorySlug}/{page}", requirements={"page" = "\d+"})
     */
    public function redirectOldLinksAction($categorySlug, $page)
    {
        return $this->redirect($this->generateUrl(
            'portfolio_category_view',
            [
                'slug' => $categorySlug,
                'page'  => $page
            ]
        ), 301);
    }
}
