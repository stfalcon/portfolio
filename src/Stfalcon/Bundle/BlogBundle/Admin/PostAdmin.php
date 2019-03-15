<?php

namespace Stfalcon\Bundle\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class PostAdmin.
 */
class PostAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */
    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
    ];

    /**
     * {@inheritdoc}
     */
    public function postPersist($post)
    {
        $this->postUpdate($post);
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate($post)
    {
        $this->configurationPool->getContainer()->get('application_defaultbundle.service.sitemap')->generateSitemap();
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($post)
    {
        $this->preUpdate($post);
    }

    /**
     * {@inheritdoc}
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $validator = $container->get('validator');

        foreach ($object->getTranslations() as $translation) {
            if ('additionalInfoFile' === $translation->getField()) {
                /** @var UploadedFile $uploadFile */
                $uploadFile = $translation->getContent();
                $errors = $validator->validatePropertyValue($object, 'additionalInfoFile', $uploadFile);
                if ($errors->count()) {
                    $errorElement->addViolation($errors->get(0)->getMessage())->end();
                    break;
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($post)
    {
        $file = null;
        foreach ($post->getTranslations() as $translation) {
            if ('additionalInfoFile' === $translation->getField()) {
                $container = $this->getConfigurationPool()->getContainer();
                $vichUploader = $container->get('vich_uploader.property_mapping_factory');
                $path = $vichUploader->fromField($post, 'additionalInfoFile');
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
            foreach ($post->getTranslations() as $translation) {
                if ('additionalInfo' === $translation->getField()) {
                    $translation->setContent($file->getFilename());
                    break;
                }
            }
        }
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('translations', 'a2lix_translations_gedmo', array(
                    'translatable_class' => 'Stfalcon\Bundle\BlogBundle\Entity\Post',
                    'cascade_validation' => true,
                    'fields' => array(
                        'title' => array(
                            'label' => 'title',
                            'locale_options' => array(
                                'ru' => array(
                                    'required' => false,
                                ),
                                'en' => array(
                                    'required' => false,
                                ),
                            ),
                        ),
                        'text' => array(
                            'label' => 'text',
                            'locale_options' => array(
                                'ru' => array(
                                    'required' => false,
                                ),
                                'en' => array(
                                    'required' => false,
                                ),
                            ),
                            'attr' => array(
                                'class' => 'markitup',
                            ),
                        ),
                        'additionalInfoTitle' => [
                            'label' => 'Additional Info Title',
                            'locale_options' => [
                                'ru' => [
                                    'required' => false,
                                ],
                                'en' => [
                                    'required' => false,
                                ],
                            ],
                        ],
                        'additionalInfoFile' => [
                                'label' => 'Additional Info File',
                                'data_class' => null,
                                'locale_options' => [
                                    'ru' => [
                                        'required' => false,
                                    ],
                                    'en' => [
                                        'required' => false,
                                    ],
                                ],
                        ],
                        'additionalInfo' => [
                            'label' => 'Additional Info',
                            'attr' => ['readonly' => true],
                            'locale_options' => [
                                'ru' => [
                                    'required' => false,
                                ],
                                'en' => [
                                    'required' => false,
                                ],
                            ],
                        ],
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
                        'metaTitle' => array(
                            'label' => 'Meta title',
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
                )
            )
            ->add('slug')
            ->add('category', 'sonata_type_model', ['multiple' => false, 'required' => false])
            ->add('tags', null)
            ->add('image', null, ['required' => false])
            ->add('author', null, array(
                'required' => true,
                'placeholder' => 'Choose an user',
            ))
            ->add('created', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ))
            ->add('published', null, array('required' => false));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('slug')
            ->add('title')
            ->add('created')
            ->add('category')
            ->add('_action', 'actions', [
                'label' => 'Действия',
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
            ->add('title');
    }
}
