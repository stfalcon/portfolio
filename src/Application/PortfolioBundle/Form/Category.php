<?php

namespace Application\PortfolioBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\TextField;
use Symfony\Component\Form\TextareaField;

class Category extends Form
{
    public function configure()
    {
        parent::configure();
        $this->add(new TextField('name'));
        $this->add(new TextareaField('description'));
    }
}