<?php
namespace Stfalcon\Bundle\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class TagAdmin
 */
class TagAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('text');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('text')
            ->add('id');
    }

    public function postPersist($post)
    {
        $this->postUpdate($post);
    }
    public function postUpdate($post)
    {
        $this->configurationPool->getContainer()->get('application_defaultbundle.service.sitemap')->generateSitemap();
    }
}