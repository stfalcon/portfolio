<?php

namespace Application\Bundle\DefaultBundle\DataFixtures\ORM;

use Application\Bundle\DefaultBundle\Entity\Jobs;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


/**
 * jobs fixtures
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class LoadJobsData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Create and load jobs fixtures to database
     *
     * @param ObjectManager $manager Entity manager object
     */
    public function load(ObjectManager $manager)
    {
        $symfonyTag = $this->getReference('tag-symfony2');
        $phpTag = $this->getReference('tag-php');
        $doctrine2Tag = $this->getReference('tag-doctrine2');
        $kmTag = $this->getReference('tag-km');
        $kievTag = $this->getReference('tag-kiev');
        $remoteTag = $this->getReference('tag-remote');

        // jobs
        $jobFirst = new Jobs();
        $jobFirst->setTitle('Middle/ Senior Angular Developer');
        $jobFirst->setSlug('angular-developer');
        $jobFirst->setDescription('В связи с ростом компании ищем опытного Middle/Senior JavaScript (Angular) Developer в свою команду профессионалов, который будет заниматься  разработкой веб приложений с использованием последних технологий и лучших практик. 
 
Что вам понадобиться для работы:
HTML5/CSS3, не менее 2 лет
JavaScript/ Angular не менее 2 лет
кроссбраузерная вёрстка
Responsive/Adaptive Design
знание английского на уровне не ниже чтения профессиональной литературы
знание Adobe Photoshop для нарезки макетов
инициативность и умение работать на результат
ответственность
стремление к развитию
Мы работаем над проектами, которые упрощают и улучшают условия работы бизнеса. Убедится в этом можно перейдя по ссылке: https://stfalcon.com/en/portfolio
 
Что мы используем в фронт-энде: 
Angular, Webpack, Typescript, Sass.
 
Мы высоко оценим если у вас есть:
опыт работы с git
LESS,SCSS,PostCss
сборщики grunt, gulp, webpack
языки программирования: typescript
знания паттернов программирования: SOLID, Component, REDUX, FLUX, functional programming
фреймворки и библиотеки: React, Angular2, vuejs
 
То, что мы предлагаем взамен:
В нашей команде вы сможете принять участие в разработке и поддержке больших проектов с адаптивным дизайном с использованием современных технологий фронт-энда (например — http://keepsnap.com/ Улучшить свои навыки в работе с <!--more-->And text after cut
опытными дизайнерами и разработчиками. Получить опыт в разработке фронт-энда больших мобильных проектов (например — http://m.liveexpert.ru/ )
участие в профильных конференциях
курсы английского языка
50% оплаты за абонемент в бассейн/спортзал
веселые и активные корпоративы
возможность обучения и карьерного роста
отличный коллектив

');

        $jobFirst->addTag($kmTag);
        $jobFirst->addTag($kievTag);
        $jobFirst->addTag($remoteTag);
        $jobFirst->setActive(true)
        ->setCreated(new \DateTime('17-05-2016'));
        $manager->persist($jobFirst);
        $manager->merge($kmTag);
        $manager->merge($kievTag);
        $manager->merge($remoteTag);


        $jobAboutPhp = new Jobs();
        $jobAboutPhp->setTitle('PHP Middle/Senior (Symfony) Developer');
        $jobAboutPhp->setSlug('php-developer');
        $jobAboutPhp->setDescription('
        PHP Middle/Senior (Symfony) Developer
 
 Киев, Хмельницкий
Необходимые навыки
— уверенное знание PHP;
— опыт использования фреймворка Symfony;
— понимание архитектурного шаблона MVC;
— понимание шаблона Dependency Injection и его применение в Symfony;
— опыт работы с СУБД MySQL или PostgreSQL;
— знание для чего применяются key-value storage типа Redis, Memcached;
— знание для чего применяются документо-ориентированные базы данных, типа MongoDB;
— опыт работы с RabbitMQ либо другими брокерами сообщений;
— базовые знания JavaScript/jQuery;
— понимание основ HTML 5 и CSS 3;
— владение английским языком на уровне, достаточном для чтения тех. документации;
— использование системы контроля версий Git;
— базовые знания Linux.
Будет плюсом
— опыт работы с highload проектами;
— участие в open source проектах;
— опыт работы с микросервисной архитектурой;
— опыт использования сторонних сервисов с использованием RESTful API либо написание своих;
— опыт использования инструментов для полнотекстового поиска (Sphinx, Elasticsearch);
— опыт использования фреймворков для тестирования PHPUnit, Behat/Mink, Codeception;
— опыт работы с любыми JS-фреймворками;
— умение писать чистый, понятный, поддерживаемый код;
— умение проектировать по модели (DDD).
Предлагаем
— участие в профильных конференциях;
— курсы английского языка;
— 50% оплаты за абонемент в бассейн/спортзал;
— веселые и активные корпоративы;
— возможность обучения и карьерного роста;
— хороший коллектив;
— помощь по релокейту (компания оплачивает услуги риелтора и первый месяц проживания);
— и это ты еще не видел наш офис:) inna-dove.com.ua/...olio/stfalcon/pano/1.html.

        ');
        $jobAboutPhp->addTag($symfonyTag);
        $jobAboutPhp->addTag($doctrine2Tag);
        $jobAboutPhp->addTag($phpTag);
        $jobAboutPhp->addTag($kmTag);
        $jobAboutPhp->addTag($kievTag);
        $jobAboutPhp->addTag($remoteTag);
        $jobAboutPhp->setActive(true);

        $manager->persist($jobAboutPhp);
        $manager->merge($kmTag);
        $manager->merge($kievTag);
        $manager->merge($remoteTag);
        $manager->merge($symfonyTag);
        $manager->merge($doctrine2Tag);
        $manager->merge($phpTag);

        $manager->flush();

        $this->addReference('job-first', $jobFirst);
        $this->addReference('job-about-php', $jobAboutPhp);
    }

    /**
     * Get the number for sorting fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 5; // the order in which fixtures will be loaded
    }

}