<?php

namespace Application\Bundle\DefaultBundle\Helpers;

use TFox\MpdfPortBundle\Service\MpdfService;
use Twig_Environment;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class PdfGeneratorHelper.
 */
class NewPdfGeneratorHelper
{
    /**
     * @var Twig_Environment
     */
    protected $templating;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * @var MpdfService
     */
    protected $mPdfPort;

    /**
     * Constructor.
     *
     * @param Twig_Environment $templating Twig
     * @param Kernel           $kernel     Kernel
     * @param MpdfService      $mPdfPort
     */
    public function __construct($templating, $kernel, $mPdfPort)
    {
        $this->templating = $templating;
        $this->kernel = $kernel;
        $this->mPdfPort = $mPdfPort;
    }

    /**
     * Generate PDF-file of ticket.
     *
     * @param string $email
     * @param array  $data
     * @param string $type
     *
     * @return mixed
     */
    public function generatePdfFile($email, array $data, $type)
    {
        $html = $this->templating->render('@ApplicationDefault/emails/order/order_pdf.html.twig', ['data' => $data, 'type' => $type]);

        // Override default fonts directory for mPDF
        define('_MPDF_SYSTEM_TTFONTS', realpath($this->kernel->getRootDir().'/../web/fonts/').'/');

        $this->mPdfPort->setAddDefaultConstructorArgs(false);

        $constructorArgs = [
            'mode' => 'BLANK',
            'format' => [210, 297],
            'margin_left' => 2,
            'margin_right' => 2,
            'margin_top' => 2,
            'margin_bottom' => 2,
            'margin_header' => 2,
            'margin_footer' => 2,
        ];

        $mPDF = $this->mPdfPort->getMpdf($constructorArgs);

        $mPDF->fontdata['ptroot'] = [
            'R' => 'PT-Root-UI_Bold.ttf',
            'B' => 'PT-Root-UI_Bold.ttf',
        ];
        // phpcs:disable Zend.NamingConventions.ValidVariableName.NotCamelCaps
        $mPDF->sans_fonts[] = 'ptroot';
        $mPDF->available_unifonts[] = 'ptroot';
        $mPDF->available_unifonts[] = 'ptrootB';
        $mPDF->default_available_fonts[] = 'ptroot';
        $mPDF->default_available_fonts[] = 'ptrootB';
        // phpcs:enable

        $mPDF->SetDisplayMode('fullpage');
        $mPDF->WriteHTML($html);
        $email = \strtolower(\preg_replace('~([\s\-/@])~', '_', $email));

        return $mPDF->Output('order-'.$email.'.pdf', 'S');
    }
}
