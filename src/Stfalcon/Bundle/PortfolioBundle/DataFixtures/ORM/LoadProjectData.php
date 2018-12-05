<?php

namespace Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Stfalcon\Bundle\PortfolioBundle\Entity\Project;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Projects fixtures.
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class LoadProjectData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Create and load projects fixtures to database.
     *
     * @param ObjectManager $manager Entity manager object
     */
    public function load(ObjectManager $manager)
    {
        $files = ['55f18a561ca39.jpeg', '55f18d6a43d1a.jpeg'];

        $devCategory = $this->getReference('category-development');
        $mobileCategory = $this->getReference('category-mobile');
        $webDesign = $this->getReference('category-web');

        $adminUser = $this->getReference('user-admin');
        $firstUser = $this->getReference('user-first');
        $secondUser = $this->getReference('user-second');

        // projects
        $novaPochta = (new Project())
            ->setName('novaposhta.ua')
            ->setSlug('novaposhta-ua')
            ->setUrl('https://new.novaposhta.ua/')
            ->setDate(new \DateTime('now'))
            ->setDescription('
                Nova Poshta, the Ukrainian company, during the past 16 years has become a market leader in the delivery and a driver of 
                its development. The company actively works on the elaboration and improvement of user-friendly online services. 
                Nova Posta and stfalcon.com cooperation was intended to substantially improve the current Personal Dashboard to bring 
                the user experience to a next level.
            ')
            ->setTags('design, HTML markup, development')
            ->setShortDescription('design, HTML markup, development')
            ->setOnFrontPage(1)
            ->setBackgroundColor('#EC584E')
            ->setUseDarkTextColor(false)
            ->setOrderNumber(0)
            ->setShowCase(true)
            ->setCaseContent('TestCase Content')
            ->addCategory($devCategory)
            ->addCategory($webDesign)
            ->addParticipant($adminUser)
            ->addParticipant($secondUser)
            ->setPublished(true)
            ->setImageFile($this->generateUploadedFile('p3.png'));

        $manager->persist($novaPochta);
        $manager->merge($devCategory);
        $manager->merge($webDesign);

        $nicUa = (new Project())
            ->setName('NIC.UA')
            ->setSlug('NIC-UA')
            ->setUrl('https://nic.ua')
            ->setDate(new \DateTime('now'))
            ->setDescription('
                NIC.UA is one of the largest domain name registrar and hosting provider in Ukraine. 
                Previously it was using a monolith server but together with NIC.UA team we have divided this system into back 
                end and front end parts. Now the service is flexible, scalable and has a smart domain search satisfying clients’ 
                needs.
            ')
            ->setTags('design, development')
            ->setShortDescription('design development')
            ->setOnFrontPage(1)
            ->setBackgroundColor('#F4F4F4')
            ->setUseDarkTextColor(true)
            ->setPublished(true)
            ->setOrderNumber(1)
            ->setShowCase(true)
            ->setCaseContent(
                <<<HTML
<div class="row">
            <div class="col col-6 description-row">
                <p>
NIC.UA — крупнейший регистратор доменных имен в Украине и является лидирующим по инновациям. НИК.Юэй регистрирует домены и поддерживает их на собственных серверах имен, предоставляет <a 
rel="nofollow" target="_blank" href="https://nic.ua/ru/hosting/website">хостинг</a>,оформляет торговые марки и SSL-сертификаты. Компания была создана в 2007 году.
</p>
            <p>В основе системы старого сайта был монолитный сервер. Вместе с командой NIC.UA мы разделили систему на фронтенд и бекенд. Бекендом занимались разработчики NIC.UA, фронтендом — компания Stfalcon.com, а дизайнеры Александр Зайцев и Александр Колодько сделали сайт удобным и функциональным. Благодаря этому сервис стал более гибким и легко масштабируемым. Фронтенд был построен на Symfony2, это умная система, задача которой — взаимодействие между пользователем и мощным API бекенда. Она не только отображает страницы сайта, но и умеет интеллектуально подобрать нужный <a  rel="nofollow" target="_blank" href="https://nic.ua/ru/domains">домен</a> для клиента.</p>
                
  </div>
    <div class="col col-1"></div>
</div>



<div class="row">
    <div class="col col-12">
	 <img src="/uploads/images/5b4f3264822a2.jpeg" alt="">
    </div>
</div>



<div class="row">
    <div class="col col-5"></div>
    <div class="col col-6 text">
        <p style="margin-bottom: 10px;">Что мы сделали? 
</p>
              <ol style="list-style:decimal inside;">
                    <li style="margin-bottom: 18px;">Перевели фронтенд сайта на PHP-фреймворк Symfony.
</li>
                    <li style="margin-bottom: 18px;">Написали настраиваемую админ-панель для управления контентом на сайте.
</li>
                    <li style="margin-bottom: 18px;">Изменили принцип поиска доменов.
</li>
                    <li style="margin-bottom: 18px;">Внедрили горизонтальное масштабирование и непрерывную интеграцию.
</li>
      </ol>
    <p></p>
    </div>
    <div class="col col-1"></div>
</div>





<div class="row">
  <div class="col col-5" >

  </div>
    <div class="col col-6 text">
        <h2>Преимущества PHP-фреймворка Symfony</h2>
          
<p style="margin-bottom: 30px;">Для лучшей масштабируемости и удобства взаимодействия с панелью управления, мы перевели сайт NIC.UA на Symfony.
На этом PHP-фреймворке работают BlaBlaCar, Spotify, <a  rel="nofollow" target="_blank" href="https://stfalcon.com/ru/portfolio/meinfernbus-de">MeinFernbus</a>. Они обрабатывают огромные массивы данных ежедневно.</p>

</div>
<div class="col col-1"></div>
</div>



<div class="row">
    <div class="col col-5"></div>
    <div class="col col-6 text">


        <div class="row">
                <div class="col col-5 text">
                        <h3> Актуальность </h3>
                        <p style="margin-top: 0px;">Является самым популярным PHP фреймворком в мире из-за высокой скорости работы и гибкости настройки для веб-решений. 
 </p>        
                    </div>               

                    <div class="col col-1"></div>                            
                <div class="col col-5 text">
            
                        <h3> Расширяемость </h3>
                        <p style="margin-top: 0px;">  Функционал фреймворка организован в виде бандлов, с которыми легко работать, поскольку в любое время можно расширить приложение за счет подключения новых бандлов. </p>
                    </div>   
        </div>
        <div class="row">
                <div class="col col-5 text">
                        <h3> Автоматизация </h3>
                            <p style="margin-top: 0px;">Можно выполнять некоторые команды из консоли. </p>   
                    </div>               
                       
    <div class="col col-1"></div>     
                <div class="col col-5 text">
                        <h3> Удобство </h3>
                            <p style="margin-top: 0px;" > Наличие панели веб-отладки с полезными данными для профилирования работы сайта и запросов к базе данных. </p>
                    </div>   
        </div>        

</div>



    <div class="col col-1"></div>
</div>


<div class="row">
    <div class="col col-5"></div>
    <div class="col col-6 text">
        <h2>Гибкая панель для управления контентом</h2>
        <p>Возможности стандартных админ-панелей сайтов крайне ограничены, поэтому для NIC.UA мы разработали специальную настраиваемую админ-панель с шаблонами. Она позволяет:</p>
              <ul>
                    <li>создавать блоки, которые в неизменном виде будут отображаться в разных частях сайта;</li>
                    <li>создавать лендинги (например, для акционных предложений);</li>
                    <li>индивидуально настраивать каждую страницу сайта.</li>
    </ul>
    <p></p>
    </div>
    <div class="col col-1"></div>

</div>

       <div class="row">
           <div class="col col12 fixed-cover" style="background-image: url(/uploads/images/5b506dd02035e.jpeg);">
               <img src="/uploads/images/5b506d683774c.png" alt="">
           </div>
       </div>

    <div class="row">
            <div class="col col-5"></div>
            <div class="col col-6 text">
                <h2>Новый интеллектуальный поиск доменов</h2>
                <p>Поиск доменов является одной из главных составляющих сервиса компании, поэтому мы сделали его еще лучше. Преимущества нового поиска:</p>
                
                 <h3>Функция транслитерации, подбора синонимов и переводов.</h3>
                 <p style="margin-top: 0px;" >
                     Пользователям не нужно повторять поиск, чтобы получить все наилучшие варианты. 
                        Теперь, подходящий для бизнеса домен, вы найдете еще быстрее.</p> 

                        <h3>Фильтры доменов.</h3> <p style="margin-top: 0px;"> Можно выбрать популярные, международные, украинские или региональные домены. 
                           </p> 
                           <h3>«Умное кеширование».</h3> <p style="margin-top: 0px;"> Сервис не совершает лишних запросов к реестрам и не замедляет работу системы. 
                           </p> 
            
                    </div>
                    <div class="col col-1"></div>
                </div> 



                    <div class="row">
                            <div class="col col-1"></div>
                            
                                    <div class="col col-4">
                                      <video style="max-width:100%; box-shadow: 0px 20px 50px 0px rgba(0, 0, 0, 0.22)" autoplay="autoplay" loop="loop" preload="auto">
                                  <source src="/uploads/video/nicua_case_files/nic.mp4" alt="" >
                                </video>
                                  </div>
                                

                              <div class="col col-6 text">
                        
                                    <h3>Как это работает? </h3>
                                    <p style="margin-bottom: 10px;">Например, если клиент ищет домен для цветочного магазина, то в результатах поиска будут:</p>
                                    <ul>
                                          <li style="margin-bottom: 18px;">варианты доменов с указанием необходимости зарегистрировать торговую марку (например, для домена cvety.ua);
</li>
                                          <li style="margin-bottom: 18px;">варианты с транслитерацией (например, поиск «cvety» также выдаст домены со словом «цветы»);
</li>
                                          <li style="margin-bottom: 18px;">синонимы (поиск «flowers» также выдаст домены со словом «bouquet-of-red-roses»);
</li>
                                          <li style="margin-bottom: 18px;">переводы (поиск «цветы» также выдаст домены со словом «flowers»).
</li>
                          </ul>
                          <p>Можно использовать удобный фильтр, чтоб выбрать популярные, международные, украинские или региональные домены.</p>
                            </div>
                        </div> 


                    
                    <div class="row">
                            <div class="col col-5"></div>
                            <div class="col col-6 text">
                                <h2>Горизонтальное масштабирование и непрерывная интеграция</h2>
                                <p>Взаимодействие с серверной частью NIC.UA осуществляется посредством API. Мы реализовали логику его работы и настроили беспрерывную интеграцию (Continuous Integration) с помощью GitLab. 
                                        Такой тип интеграции предполагает постоянное формирование основной ветки разработки с кодом от нескольких программистов, создание автоматических сборок и их тестирование. Благодаря беспрерывной интеграции нужные изменения вносятся проще и быстрее.

                                        </p>
                                        <img src="/uploads/images/5b50710f50df8.jpeg"/>
                            </div>
                            <div class="col col-1"></div>
                        </div>
                        

                        <div class="row">
                                <div class="col col-5"></div>
                                <div class="col col-6 text">
                                        <p style="margin-bottom: 10px;">Существует два вида масштабирования: </p>
                                        <ol style="list-style:decimal inside;" >
                                              <li style="margin-bottom: 18px;">Вертикальное — ресурсы добавляются к одному серверу.</li>
                                              <li style="margin-bottom: 18px;">Горизонтальное — расширение происходит за счет увеличения числа серверов. </li>
                              </ol>
                                    <p>Поскольку сайт NIC.UA должен выдерживать большие перепады нагрузок, стояла задача построить горизонтальное масштабирование проекта. Была создана система из нескольких серверов, часть из которых являются балансировщиками и распределяют нагрузку для остальных серверов. Если случится сбой, сервис продолжит работу, поскольку все задачи будут выполняться на исправном сервере, а неработающий будет игнорироваться. Так обеспечивается отказоустойчивость системы.
                                            </p>
                                            <img src="/uploads/images/5b5070927cc54.jpeg"/>
                                
 <p>Мы гордимся своей работой и считаем, что создали лучший в Восточной Европе сайт регистрации доменов и хостинга. Теперь работать с NIC.UA удобно и выгодно: у них действует партнерская программа, бонусная система оплаты и специальные скидки для постоянных клиентов. Мы прислушиваемся к пожеланиям команды  NIC.UA и продолжаем развивать сайт дальше. 
                                            </p><br />
</div>
                                <div class="col col-1"></div>
                            </div>
HTML
            )
            ->addCategory($devCategory)
            ->addCategory($mobileCategory)
            ->setImageFile($this->generateUploadedFile('p4.png'));

        $manager->persist($nicUa);
        $manager->merge($devCategory);
        $manager->merge($mobileCategory);

        $meinFernbus = (new Project())
            ->setName('MeinFernbus')
            ->setSlug('MeinFernbus')
            ->setUrl('https://meinfernbus.de/')
            ->setDate(new \DateTime('now'))
            ->setDescription('
                MeinFernbus is a leading German supplier of transport services in the area of passenger coaches. 
                The company is considered to be a partner and an innovative progenitor of small and medium tourism businesses 
                in Germany. It functions independently of the big tourist corporations.
            ')
            ->setTags('design, development')
            ->setShortDescription('design development')
            ->setOnFrontPage(1)
            ->setBackgroundColor('#4D9CC9')
            ->setUseDarkTextColor(false)
            ->setPublished(true)
            ->setOrderNumber(2)
            ->setShowCase(true)
            ->setCaseContent('TestCase Content')
            ->addCategory($devCategory)
            ->addCategory($mobileCategory)
            ->setImageFile($this->generateUploadedFile('p6.png'));

        $manager->persist($meinFernbus);
        $manager->merge($devCategory);
        $manager->merge($mobileCategory);

        $manager->flush();

        $this->addReference('project-preorder', $novaPochta);
        $this->addReference('project-eprice', $nicUa);
        $this->addReference('project-meinfernbus', $meinFernbus);

        for ($i = 0; $i < 10; ++$i) {
            $example = (new Project())
                ->setName('example.com_'.$i)
                ->setSlug('example-com_'.$i)
                ->setUrl('http://example.com')
                ->setDate(new \DateTime('now'))
                ->setDescription('As described in RFC 2606, we maintain a number of domains such as 
                    EXAMPLE.COM and EXAMPLE.ORG for documentation purposes. These domains may be used as illustrative 
                    examples in documents without prior coordination with us. They are not available for registration.')
                ->setTags('design, HTML markup, development')
                ->setShortDescription('design, HTML markup, development')
                ->setOnFrontPage(0)
                ->setOrderNumber(2 + $i);
            if ($i % 2) {
                $example->addCategory($devCategory);
            } else {
                $example->addCategory($mobileCategory);
            }
            $example->addParticipant($adminUser)
                ->addParticipant($firstUser)
                ->addParticipant($secondUser)
                ->setPublished(true)
                ->setImageFile($this->copyFile($files[array_rand($files)]));
            $manager->persist($example);
            $manager->merge($devCategory);
            $manager->merge($mobileCategory);
        }
        $manager->flush();
    }

    /**
     * Get order number.
     *
     * @return int
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }

    /**
     * Copy file to tmp directory.
     *
     * @param string $originalFileName
     *
     * @return UploadedFile
     */
    protected function copyFile($originalFileName)
    {
        $tempDir = sys_get_temp_dir().'/';

        $absolutePath = dirname(dirname(__FILE__));
        $fs = new Filesystem();
        $tmpFilename = uniqid();
        try {
            $fs->copy($absolutePath.'/Files/'.$originalFileName, $tempDir.$tmpFilename, true);
        } catch (IOException $e) {
            echo 'An error occurred while coping file form '.$absolutePath.'/Files/'.$originalFileName.' to '.$tempDir.'.'.$originalFileName;
        }

        return new UploadedFile($tempDir.$tmpFilename, $originalFileName, null, null, null, true);
    }

    /**
     * Generate UploadedFile object from local file. For VichUploader
     *
     * @param string $filename
     *
     * @return UploadedFile
     */
    private function generateUploadedFile($filename)
    {
        $fullPath = realpath($this->getKernelDir().'/../web/img/'.$filename);
        if ($fullPath) {
            $tmpFile = tempnam(sys_get_temp_dir(), 'event');
            copy($fullPath, $tmpFile);
            return new UploadedFile($tmpFile, $filename, null, null, null, true);
        }
        return null;
    }

    private function getKernelDir()
    {
        return $this->container->get('kernel')->getRootDir();
    }
}
