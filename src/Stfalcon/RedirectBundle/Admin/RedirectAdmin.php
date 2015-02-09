<?php
namespace Stfalcon\RedirectBundle\Admin;

use Sonata\AdminBundle\Admin\Admin,
    Sonata\AdminBundle\Form\FormMapper,
    Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

use Stfalcon\RedirectBundle\Entity\Redirect;

use Stfalcon\RedirectBundle\Entity\Types\RedirectCodeType;
use Stfalcon\RedirectBundle\Entity\Types\RedirectStatusType;

use Stfalcon\RedirectBundle\Service\RedirectService;

/**
 * block admin
 */
class RedirectAdmin extends Admin
{
    /**
     * The label class name  (used in the title/breadcrumb ...)
     *
     * @var string
     */
    protected $classnameLabel = 'redirect';

    /**
     * The base route pattern used to generate the routing information
     *
     * @var string
     */
    protected $baseRoutePattern = '/redirects';

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('slug')
            ->add('target')
            ->add('code')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                )
            )
        );
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
            ->add('slug', null, array('required' => true))
            ->add('target', null, array('required' => true))
            ->add('code', 'choice', array(
                'label' => 'Code',
                'choices' => RedirectCodeType::getChoices(),
                'required'  => true,
            ))
            ->add('status', 'choice', array(
                'label' => 'Status',
                'choices' => RedirectStatusType::getChoices(),
                'required'  => true,
            ))
            ->end();
    }

    /**
     * @return RedirectService
     */
    protected function getRedirectService()
    {
        return $this->get('stfalcon_redirect.service.redirect');
    }

    /**
     * Gets a service.
     *
     * @param string $id The service identifier
     *
     * @return object The associated service
     */
    protected function get($id)
    {
        return $this->configurationPool->getContainer()->get($id);
    }
}