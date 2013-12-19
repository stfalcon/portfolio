<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Bundle\UserBundle\Admin;

use Application\Bundle\UserBundle\Entity\User;
use Sonata\UserBundle\Admin\Model\UserAdmin as Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;


class UserAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->with('Profile')
                ->add('position', NULL, array('required' => false, 'label'     => 'Должность'))
                ->add('image', 'file', array('required' => false, 'label'     => 'Аватарка'))
                ->add('caricature', 'file', array('required' => false, 'label'     => 'Карикатура'))
            ->end()
            ->with('Interests',  array('label' => 'Интересы'))
                ->add('interests', 'choice', array(
                    'choices'   => User::getInterestsList(),
                    'multiple'  => true,
                    'required'  => false,
                    'label'     => 'Интересы',
                ))
                ->add('drink', 'choice', array(
                    'choices'   => User::getDrinksList(),
                    'required'  => false,
                    'label'     => 'Любимый напиток',
                ))
            ->end()
        ;
    }

    public function preUpdate($entity){
        $entity->setUpdatedAt(new \DateTime());
    }
}
