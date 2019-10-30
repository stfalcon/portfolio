<?php

namespace Application\Bundle\DefaultBundle\Service\SonataBlock;

use Application\Bundle\DefaultBundle\Service\GeoIpService;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * FooterHomeLinkBlockService.
 */
class FooterHomeLinkBlockService extends BaseBlockService
{
    private $geoIpService;

    /**
     * @param string           $name
     * @param EngineInterface  $templating
     * @param GeoIpService     $geoIpService
     */
    public function __construct($name, EngineInterface $templating, GeoIpService $geoIpService)
    {
        parent::__construct($name, $templating);

        $this->geoIpService = $geoIpService;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $clientIp = $blockContext->getSetting('client_ip');

        $country = $this->geoIpService->getCountryByIp($clientIp);
        $isUkraineUser = $country === 'Украина';

        return $this->renderResponse($blockContext->getTemplate(), [
            'block' => $blockContext->getBlock(),
            'show_ukr_contacts' => $isUkraineUser,
        ], $response);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'template' => '@ApplicationDefault/Default/_footer_home_link.html.twig',
            'event' => null,
            'event_block' => null,
            'client_ip' => '127.0.0.1',
        ]);
    }

}
