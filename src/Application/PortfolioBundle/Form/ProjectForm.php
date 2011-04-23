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
//        $builder->add('image', 'file', array('required' => false));
        $builder->add('description', 'textarea');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Application\PortfolioBundle\Entity\Project',
        );
    }


//    {
//        $this->addOption('em');
//        if (!($this->getOption('em') instanceof \Doctrine\ORM\EntityManager)) {
//            throw new InvalidOptionsException('The em option must be instance of Doctrine\ORM\EntityManager', array('em'));
//        }
//        $em = $this->getOption('em');
//
//        $this->add(new FileField('image', array(
//                    'secret' => md5(time()), 'required' => false
//                )));
//        // relation with categories
//        $this->add(new EntityChoiceField('categories', array(
//                    'em' => $em,
//                    'class' => 'Application\PortfolioBundle\Entity\Category',
//                    'multiple' => true,
//                    'expanded' => true,
//                )));
//
//        parent::configure();
//    }

}