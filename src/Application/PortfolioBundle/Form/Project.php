<?php

namespace Application\PortfolioBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\TextField;
use Symfony\Component\Form\TextareaField;
use Symfony\Component\Form\ChoiceField;
use Symfony\Bundle\DoctrineBundle\Form\ValueTransformer\CollectionToChoiceTransformer;
use Symfony\Component\Form\Exception\InvalidOptionsException;

class Project extends Form
{

    public function configure()
    {
        parent::configure();

        // добавляем опцию em в список известных опций
        $this->addOption('em');
        if (!($this->getOption('em') instanceof \Doctrine\ORM\EntityManager)) {
            throw new InvalidOptionsException('The em option must be instance of Doctrine\ORM\EntityManager', array('em'));
        }
 
        $em = $this->getOption('em');

        $this->add(new TextField('name'));
        $this->add(new TextareaField('description'));

        // выборка всех категорий
        $categoriesCollection = $em->createQuery('SELECT c FROM PortfolioBundle:Category c')->getResult();
        // формируем массив с id категорий в качестве ключей
        $categories = array();
        foreach ($categoriesCollection AS $category) {
            $categories[$category->getId()] = $category;
        }

        // этот транформер данных отвечает за трансформацию коллекции категорий
        // в отмеченные пункты и наоборот
        $productTransformer = new CollectionToChoiceTransformer(array(
                    'em' => $em,
                    'className' => 'Application\PortfolioBundle\Entity\Category',
                ));

        $field = new ChoiceField('categories', array(
                    'choices' => $categories,
                    'multiple' => true,
                    'expanded' => true,
                    'value_transformer' => $productTransformer,
                ));
        $this->add($field);
    }

}