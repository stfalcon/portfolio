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
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\UserBundle\Admin\Model\UserAdmin as Admin;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class UserAdmin
 */
class UserAdmin extends Admin
{
    /**
     * Pre update
     *
     * @param User $entity User
     *
     * @return User
     */
    public function preUpdate($entity)
    {
        $entity->setUpdatedAt(new \DateTime());
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper->remove('firstname');
        $formMapper->remove('lastname');

        $datetimePickerOptions =
            [
                'dp_use_seconds' => false,
                'dp_language' => 'ru',
                'format' => 'dd.MM.y',
                'required' => false,
            ];

        $formMapper
            ->with('General')
                ->add('ordering', null, array('required' => false, 'label' => 'Сортировка'))
                ->add('usernameCanonical', null, ['label' => 'Username'])
            ->end()
            ->with('Profile')
                ->add('translations', 'a2lix_translations_gedmo', array(
                        'translatable_class' => 'Application\Bundle\UserBundle\Entity\User',
                        'fields' => array(
                            'firstname' => array(
                                'label' => 'Имя',
                                'locale_options' => array(
                                    'ru' => array(
                                        'required' => true
                                    ),
                                    'en' => array(
                                        'required' => false
                                    )
                                )
                            ),
                            'lastname' => array(
                                'label' => 'Фамилия',
                                'locale_options' => array(
                                    'ru' => array(
                                        'required' => true
                                    ),
                                    'en' => array(
                                        'required' => false
                                    )
                                )
                            ),
                            'position' => array(
                                'label' => 'Должность',
                                'locale_options' => array(
                                    'ru' => array(
                                        'required' => true
                                    ),
                                    'en' => array(
                                        'required' => false
                                    )
                                )
                            ),
                            'description' => array(
                                'label' => 'Краткая информация',
                                'locale_options' => array(
                                    'ru' => array(
                                        'required' => false
                                    ),
                                    'en' => array(
                                        'required' => false
                                    )
                                ),
                            ),
                        ),
                        'label' => 'Перевод'
                    )
                )
                ->add('avatar', 'file', array('required' => false, 'label' => 'Аватарка'))
                ->add('caricature', 'file', array('required' => false, 'label' => 'Карикатура'))
                ->add('hideFromTeamSection', null, ['label' => 'не показывать в секции команда'])
            ->end()
            ->with('Interests', array('label' => 'Интересы'))
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
            ->with('Management')
                ->add('startDate', 'sonata_type_datetime_picker', array_merge(['label' => 'дата начала сотрудничества'], $datetimePickerOptions))
                ->add('endDate', 'sonata_type_datetime_picker', array_merge(['label' => 'дата окончания сотрудничества'], $datetimePickerOptions))
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper
            ->add('_action', 'actions', [
                'label' => 'Действия',
                'actions' => [
                    'edit'   => [],
                    'delete' => [],
                ],
            ]);
    }
}
