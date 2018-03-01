<?php

namespace Stfalcon\Bundle\PortfolioBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Stfalcon\Bundle\PortfolioBundle\Entity\ProjectReview;

/**
 * Class ProjectReviewAdmin
 */
class ProjectReviewAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */
    public function prePersist($projectReview)
    {
        $this->preUpdate($projectReview);
    }

    /**
     * {@inheritdoc}
     *
     * @param ProjectReview $projectReview
     */
    public function preUpdate($projectReview)
    {
        if ($projectReview->isActive()) {
            /** @var ProjectReview $review */
            foreach ($projectReview->getProject()->getProjectReviews() as $review) {
                $review->setActive(false);
            }
            $projectReview->setActive(true);
        }
    }

    /**
     * @param FormMapper $form
     */
    public function configureFormFields(FormMapper $form)
    {
        $form
            ->with('Основное')
                ->add('project')
                ->add('reviewer')
                ->add('active')
            ->end()
            ->with('Переводы')
                ->add(
                    'translations',
                    'a2lix_translations_gedmo',
                    [
                        'translatable_class' => 'Stfalcon\Bundle\PortfolioBundle\Entity\ProjectReview',
                        'fields' => [
                            'text' => [
                                'label' => 'текст',
                                'locale_options' => [
                                    'ru' => ['required' => true],
                                    'en' => ['required' => false],
                                ],
                                'attr' => ['class' => 'markitup'],
                            ],
                        ],
                    ]
                )
            ->end()
        ;
    }

    /**
     * @param ListMapper $list
     */
    public function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('project')
            ->add('reviewer')
            ->add('active')
        ;
    }

    /**
     * @param DatagridMapper $filter
     */
    public function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('id')
            ->add('project')
            ->add('reviewer')
            ->add('active')
        ;
    }
}
