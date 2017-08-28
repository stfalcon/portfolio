<?php
namespace Stfalcon\Bundle\PortfolioBundle\Admin;

use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Stfalcon\Bundle\PortfolioBundle\Entity\UserWithPosition;
use Stfalcon\Bundle\PortfolioBundle\Entity\UserWithPositionTranslation;

/**
 * Class ProjectAdmin
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
                '_sort_by' => 'ordernum' // field name
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
            ->with('Projects')
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
                    ),
                    'label' => 'Перевод',
                ))
                ->add('slug')
                ->add('url')
                ->add('imageFile', 'file', array('required' => false))
                ->add('date', 'date')
                ->add('categories', null, array('required' => true))
                ->add('published', 'checkbox', array('required' => false))
                ->add('shadow', 'checkbox', array('required' => false))
                ->add('onFrontPage', 'checkbox', array('required' => false))
                ->add('showCase', 'checkbox', array('required' => false))
                ->add('relativeProjects', 'entity', array(
                    'required' => false,
                    'label' => 'Похожие проекты',
                    'class' => 'Stfalcon\Bundle\PortfolioBundle\Entity\Project',
                    'multiple' => true,
                    'query_builder' => function(EntityRepository $repository) use ($currentProject) {
                        $qb = $repository->createQueryBuilder('p');

                        if ($currentProject->getId()) {
                            $qb->andWhere($qb->expr()->neq('p.id', $currentProject->getId()));
                        }

                        return $qb;
                    },
                ))
            ->end()
            ->with('Participants')
                ->add('usersWithPositions', 'sonata_type_collection',
                    [
                        'by_reference' => true,
                    ],
                    [
                        'edit'            => 'inline',
                        'inline'          => 'table',
                        'sortable'        => 'position',
                        'link_parameters' => [
                            'context' => 'default',
                        ],
                    ]
                )
            ->end()
            ->with('Media')
                ->add('media', 'sonata_type_collection', array(
                    'cascade_validation' => true,
                ), array(
                    'edit'              => 'inline',
                    'inline'            => 'table',
                    'sortable'          => 'position',
                    'link_parameters'   => array('context' => 'default'),
                    'admin_code'        => 'sonata.media.admin.gallery_has_media',
                ))
            ->end();
    }

    // @todo с sortable проблемы начиная со второй страницы (проекты перемещаются на первую страницу)
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('slug')
            ->add('name')
            ->add('date')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit'   => [],
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
