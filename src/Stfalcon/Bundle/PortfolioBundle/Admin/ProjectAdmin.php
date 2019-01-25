<?php

namespace Stfalcon\Bundle\PortfolioBundle\Admin;

use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Stfalcon\Bundle\PortfolioBundle\Entity\UserWithPosition;
use Stfalcon\Bundle\PortfolioBundle\Entity\UserWithPositionTranslation;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ProjectAdmin.
 */
class ProjectAdmin extends Admin
{
    /**
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     */
    public function __construct($code, $class, $baseControllerName)
    {
        parent::__construct($code, $class, $baseControllerName);

        if (!$this->hasRequest()) {
            $this->datagridValues = array(
                '_page' => 1,
                '_per_page' => 1,
                '_sort_order' => 'ASC', // sort direction
                '_sort_by' => 'orderNumber', // field name
            );
        }
    }

    /**
     * @param array $templates
     */
    public function setTemplates(array $templates)
    {
        $templates['list'] = 'StfalconPortfolioBundle:ProjectAdmin:list.html.twig';
        parent::setTemplates($templates);
    }

    /**
     * {@inheritdoc}
     */
    public function postPersist($project)
    {
        $this->postUpdate($project);
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate($project)
    {
        $this->configurationPool->getContainer()->get('application_defaultbundle.service.sitemap')->generateSitemap();
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($project)
    {
        $this->preUpdate($project);
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($project)
    {
        $file = null;
        foreach ($project->getTranslations() as $translation) {
            if ('imageFile' === $translation->getField()) {
                $container = $this->getConfigurationPool()->getContainer();
                $logger = $container->get('logger');
                $vichUploader = $container->get('vich_uploader.property_mapping_factory');
                $path = $vichUploader->fromField($project, 'imageFile');
                /** @var UploadedFile $uploadFile */
                $uploadFile = $translation->getContent();
                if ($uploadFile instanceof UploadedFile) {
                    try {
                        $filename = sprintf('%s.%s', uniqid(), $uploadFile->guessExtension());
                        $file = $uploadFile->move($path->getUploadDir(), $filename);
                    } catch (\Exception $e) {
                        $container->get('logger')->addCritical($e->getMessage());
                    }
                }
                break;
            }
        }

        if ($file instanceof File) {
            foreach ($project->getTranslations() as $translation) {
                $logger->addInfo($translation->getField());
                if ('image' === $translation->getField()) {
                    $logger->addInfo('set content');
                    $logger->addInfo($file->getFilename());
                    $translation->setContent($file->getFilename());
                    break;
                }
            }
        }

        /** @var UserWithPosition $userWithPosition */
        foreach ($project->getUsersWithPositions() as $userWithPosition) {
            $userWithPosition->setProject($project);

            /** @var UserWithPositionTranslation $translation */
            foreach ($userWithPosition->getTranslations() as $translation) {
                $translation->setObject($userWithPosition);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $currentProject = $this->getSubject();

        $formMapper
            ->tab('Обшие')
                ->with('Переводы')
                    ->add('translations', 'a2lix_translations_gedmo', array(
                        'translatable_class' => 'Stfalcon\Bundle\PortfolioBundle\Entity\Project',
                        'fields' => array(
                            'name' => array(
                                'label' => 'name',
                                'locale_options' => array(
                                    'ru' => array(
                                        'required' => true,
                                    ),
                                    'en' => array(
                                        'required' => false,
                                    ),
                                ),
                            ),
                            'description' => array(
                                'label' => 'description',
                                'locale_options' => array(
                                    'ru' => array(
                                        'required' => true,
                                    ),
                                    'en' => array(
                                        'required' => false,
                                    ),
                                ),
                                'attr' => array(
                                    'class' => 'markitup',
                                ),
                            ),
                            'shortDescription' => array(
                                'label' => 'short description',
                                'locale_options' => array(
                                    'ru' => array(
                                        'required' => true,
                                    ),
                                    'en' => array(
                                        'required' => false,
                                    ),
                                ),
                                'attr' => array(
                                    'class' => 'markitup',
                                ),
                            ),
                            'caseContent' => [
                                'label' => 'Case content',
                                'locale_options' => [
                                    'ru' => [
                                        'required' => false,
                                    ],
                                    'en' => [
                                        'required' => false,
                                    ],
                                ],
                                'attr' => array(
                                    'class' => 'markitup',
                                ),
                            ],
                            'additionalInfo' => [
                                'label' => 'Additional info',
                                'locale_options' => [
                                    'ru' => [
                                        'required' => false,
                                    ],
                                    'en' => [
                                        'required' => false,
                                    ],
                                ],
                                'attr' => array(
                                    'class' => 'markitup',
                                ),
                            ],
                            'tags' => array(
                                'label' => 'Tags',
                                'locale_options' => array(
                                    'ru' => array(
                                        'required' => true,
                                    ),
                                    'en' => array(
                                        'required' => false,
                                    ),
                                ),
                            ),
                            'metaKeywords' => array(
                                'label' => 'Meta keywords',
                                'locale_options' => array(
                                    'ru' => array(
                                        'required' => false,
                                    ),
                                    'en' => array(
                                        'required' => false,
                                    ),
                                ),
                            ),
                            'metaDescription' => array(
                                'label' => 'Meta description',
                                'locale_options' => array(
                                    'ru' => array(
                                        'required' => false,
                                    ),
                                    'en' => array(
                                        'required' => false,
                                    ),
                                ),
                            ),
                            'imageFile' => [
                                'label' => 'Image file',
                                'data_class' => null,
                                'locale_options' => [
                                    'ru' => [
                                        'required' => null === $currentProject->getId(),
                                    ],
                                    'en' => [
                                        'required' => false,
                                    ],
                                ],
                            ],
                            'image' => [
                                'label' => 'Image',
                                'disabled' => true,
                                'locale_options' => [
                                    'ru' => [
                                        'required' => false,
                                    ],
                                    'en' => [
                                        'required' => false,
                                    ],
                                ],
                            ],
                        ),
                        'label' => 'Перевод',
                    ))
                ->end()
                ->with('Обшие')
                    ->add('slug')
                    ->add('url')
                    ->add('date', 'date')
                    ->add('categories', null, array('required' => true))
                    ->add('published', 'checkbox', array('required' => false))
                    ->add('shadow', 'checkbox', array('required' => false))
                    ->add('onFrontPage', 'checkbox', array('required' => false))
                    ->add('orderNumber', null, array('required' => false, 'label' => 'Сортировка'))
                    ->add('showCase', 'checkbox', array('required' => false))
                    ->add('relativeProjects', 'entity', array(
                        'required' => false,
                        'label' => 'Похожие проекты',
                        'class' => 'Stfalcon\Bundle\PortfolioBundle\Entity\Project',
                        'multiple' => true,
                        'query_builder' => function (EntityRepository $repository) use ($currentProject) {
                            $qb = $repository->createQueryBuilder('p');

                            if ($currentProject->getId()) {
                                $qb->andWhere($qb->expr()->neq('p.id', $currentProject->getId()));
                            }

                            return $qb;
                        },
                    ))
                ->end()
                ->with('Цвета и Логотип')
                    ->add('mainPageImageFile', 'file', ['required' => false])
                    ->add('backgroundColor')
                    ->add('useDarkTextColor', null, ['label' => 'Использовать темный цвет текста'])
                ->end()
            ->end()
            ->tab('Participants')
                ->with('Participants')
                    ->add('usersWithPositions', 'sonata_type_collection',
                        [
                            'by_reference' => true,
                        ],
                        [
                            'edit' => 'inline',
                            'inline' => 'table',
                            'sortable' => 'position',
                            'link_parameters' => [
                                'context' => 'default',
                            ],
                        ]
                    )
                ->end()
            ->end()
            ->tab('Media')
                ->with('Media')
                    ->add('media', 'sonata_type_collection', array(
                        'cascade_validation' => true,
                    ), array(
                        'edit' => 'inline',
                        'inline' => 'table',
                        'sortable' => 'position',
                        'link_parameters' => array('context' => 'default'),
                        'admin_code' => 'sonata.media.admin.gallery_has_media',
                    ))
                ->end()
            ->end()
            ->tab('Обзоры')
                ->with('Reviews')
                    ->add(
                        'projectReviews',
                        'sonata_type_collection',
                        [
                            'by_reference' => false,
                            'btn_add' => is_null($currentProject->getId()) ? false : 'Добавить отзыв',
                            'help' => is_null($currentProject->getId()) ? 'добавление отзыва возможно только после создания события'
                                : 'добавьте отзыв',
                        ],
                        [
                            'edit' => 'inline',
                            'inline' => 'table',
                            'sortable' => 'position',
                        ]
                    )
                ->end()
            ->end()
        ;
    }

    // @todo с sortable проблемы начиная со второй страницы (проекты перемещаются на первую страницу)
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('slug')
            ->add('name')
            ->add('date')
            ->add('onFrontPage')
            ->add('orderNumber', null, ['label' => 'Сортировка'])
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
            ->add('slug')
            ->add('name');
    }
}
