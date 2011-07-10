<?php

namespace Application\PortfolioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * CategoryForm
 */
class CategoryForm extends AbstractType
{

    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('name');
        $builder->add('slug');
        $builder->add('description', 'textarea');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Application\PortfolioBundle\Entity\Category',
        );
    }
    
    public function getName()
    {
        return 'category';
    }    

}