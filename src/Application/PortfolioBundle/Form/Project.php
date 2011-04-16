<?php

namespace Application\PortfolioBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FileField;
use Symfony\Component\Form\DateField;
use Symfony\Component\Form\UrlField;
use Symfony\Component\Form\TextField;
use Symfony\Component\Form\TextareaField;
use Symfony\Component\Form\EntityChoiceField;
use Symfony\Component\Form\Exception\InvalidOptionsException;

class Project extends Form
{

    public function configure()
    {
        $this->addOption('em');
        if (!($this->getOption('em') instanceof \Doctrine\ORM\EntityManager)) {
            throw new InvalidOptionsException('The em option must be instance of Doctrine\ORM\EntityManager', array('em'));
        }
        $em = $this->getOption('em');

        $this->add(new TextField('name'));
        $this->add(new TextField('slug'));
        $this->add(new UrlField('url'), array('required' => false));
        $this->add(new DateField('date', array(
                    'widget' => DateField::CHOICE, 'type' => DateField::DATETIME
//                    'widget' => DateField::CHOICE, 'type' => DateField::STRING
                )));
        $this->add(new TextareaField('description'), array('required' => true));
        $this->add(new FileField('image', array(
                    'secret' => md5(time()), 'required' => false
                )));
        $this->add(new EntityChoiceField('categories', array(
                    'em' => $em,
                    'class' => 'Application\PortfolioBundle\Entity\Category',
                    'multiple' => true,
                    'expanded' => true,
                )));
        
        parent::configure();
    }

}