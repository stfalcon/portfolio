<?php
namespace Stfalcon\Bundle\PortfolioBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ProjectAdmin extends Admin
{
    public function __construct($code, $class, $baseControllerName)
    {
        parent::__construct($code, $class, $baseControllerName);

        if (!$this->hasRequest()) {
            $this->datagridValues = array(
                '_page' => 1,
                '_per_page' => 1,
                '_sort_order' => 'ASC', // sort direction
                '_sort_by' => 'ordernum' // field name
            );
        }
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('slug')
            ->add('url')
            ->add('description')
            ->add('tags')
            ->add('imageFile', 'file', array('required' => false, 'data_class' => 'Symfony\Component\HttpFoundation\File\File'))
            ->add('date', 'date')
            ->add('categories')
            ->add('users')
            ->add('onFrontPage', 'checkbox', array('required' => false))
            ->add('participants', 'entity', array(
                'required' => false,
                'label' => 'Учасники',
                'class' => 'Application\Bundle\UserBundle\Entity\User',
                'multiple' => true
            ))
            ->with('Media')
                ->add('media', 'sonata_type_collection', array(
                    'cascade_validation' => true,
                ), array(
                    'edit'              => 'inline',
                    'inline'            => 'table',
                    'sortable'          => 'position',
                    'link_parameters'   => array('context' => 'default'),
                    'admin_code'        => 'sonata.media.admin.gallery_has_media'
                ))
            ->end();
    }

    // @todo с sortable проблемы начиная со второй страницы (проекты перемещаются на первую страницу)
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('slug')
            ->add('name')
            ->add('date');
    }

    public function setTemplates(array $templates)
    {
        $templates['list'] = 'StfalconPortfolioBundle:ProjectAdmin:list.html.twig';
        parent::setTemplates($templates);
    }
}