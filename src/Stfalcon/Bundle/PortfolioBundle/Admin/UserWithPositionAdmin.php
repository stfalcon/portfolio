<?php

namespace Stfalcon\Bundle\PortfolioBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
/**
 * UserWithPositionAdmin class
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class UserWithPositionAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('user')
            ->add('positions');
    }
}
