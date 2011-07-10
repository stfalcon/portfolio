<?php

namespace Application\PortfolioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * ProjectForm
 */
class ProjectForm extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('name');
        $builder->add('slug');
        $builder->add('url', 'text', array('required' => false));
        $builder->add('date', 'date');
        $builder->add('image', 'file', array('required' => false));
        $builder->add('description', 'textarea');
        $builder->add('categories', 'entity', array(
                    'class' => 'Application\PortfolioBundle\Entity\Category',
                    'multiple' => true, 'expanded' => true,
                ));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Application\PortfolioBundle\Entity\Project',
        );
    }
    
    public function getName()
    {
        return 'project';
    }    
}