<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php $view['slots']->output('title', 'Веб-студия stfalcon.com'); ?></title>
        <link rel="shortcut icon" href="/favicon.ico" />
        <link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('css/style.css'); ?>" media="screen" />
    </head>

    <body>
        <!--header-->
        <div id="header">
            <a href="<?php echo $view['router']->generate('homepage'); ?>">
                <h1 class="logo">
                    Веб-студия stfalcon.com
                </h1>
            </a>

<!--            <ul class="langs">
                <li>рус</li>
                <li><a href="#">eng</a></li>
            </ul>-->

            <div class="menu">
                <?php echo $view['menu']->get('main')->render(); ?>
            </div>
        </div>
        <!--/header-->

        <!--content-->
        <div id="main">
            <div class="breadcrumbs">
                <?php echo $view['menu']->get('breadcrumbs')->render(); ?>
            </div>

            <div id="content">
                <div class="content-wrapper">
                    <?php $view['slots']->output('_content') ?>
                </div>
            </div>

            <!--sidebar-->
            <div id="sidebar">
                <a href="#" class="rss">Подписаться на RSS ленту</a>

                <!--twitter block-->
                <div class="twitter">
                    <?php echo $view['actions']->render('DefaultBundle:Default:twitter', array('count' => 1)/*, array('standalone' => true)*/); ?>
                    <p>Следить за <a href="http://twitter.com/#!/stfalcon" rel="nofollow">@stfalcon</a></p>
                    <div class="twitter-top"></div>
                </div>
                <!--/twitter block-->

                <?php if (isset($project)): ?>
                    <?php echo $view['actions']->render('DefaultBundle:Default:services', array('project' => $project)); ?>
                <?php endif; ?>
<!--                    <h2>Над проектом работали</h2>

                <ul class="comandList">

                    <li>
                        <div><img height="60" width="60" src="pic/pic5.png" alt=""></div>
                        <h5>арт-директор и дизайнер<span>Олег Пащенко</span></h5>
                    </li>
                    <li>
                        <div><img height="60" width="60" src="pic/pic5.png" alt=""></div>
                        <h5>арт-директор и дизайнер<span>Олег Пащенко</span></h5>
                    </li>
                    <li>
                        <div><img height="60" width="60" src="pic/pic5.png" alt=""></div>
                        <h5>арт-директор и дизайнер<span>Олег Пащенко</span></h5>
                    </li>
                    <li>
                        <div><img height="60" width="60" src="pic/pic5.png" alt=""></div>
                        <h5>арт-директор и дизайнер<span>Олег Пащенко</span></h5>
                    </li>
                    <li>
                        <div><img height="60" width="60" src="pic/pic5.png" alt=""></div>
                        <h5>арт-директор и дизайнер<span>Олег Пащенко</span></h5>
                    </li>

                </ul>-->

            </div>
            <!--sidebar-->

        </div>
        <!--/content-->

        <!--footer-->
        <div id="footer">
            <div class="contacts">
                <span>
                    Позвонить:
                    <strong>
                        +380 97 874-03-42
                    </strong>
                    
                    Написать: <a href="mailo:info@stfalcon.com">info@stfalcon.com</a>
                </span>

                <span>
                    Адрес:
                    <br />
                    Старокостантиновское шоссе 26, офис 308, Хмельницкий, Украина, 29000
                </span>
            </div>
            
            <div class="copyright">
                &copy; 2009–<?php echo date('Y'); ?> Веб-студия stfalcon.com
                <br />
                <br />
                Сайт работает на
                <a href="http://symfony.com" rel="nofollow">
                    <img style="vertical-align: middle; padding-left: 5px;" src="/images/footer/symfony-logo.png" alt="Symfony2" title="Symfony2" />
                </a>
            </div>
        </div>
        <!--/footer-->

        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('vendor/jquery/jquery-1.4.4.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('vendor/jquery-ui/jquery-ui-1.8.9.custom.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/jquery.main.js'); ?>"></script>
        <!--[if IE 6]>
            <script type="text/javascript" src="<?php echo $view['assets']->getUrl('js/DD_belatedPNG.js'); ?>"></script>
        <![endif]-->

        <script type="text/javascript">
            jQuery(document).ready(function() {
//                $("#accordion").accordion({ header: "h3", active: 1, autoHeight: false });
                $("#accordion").accordion({ header: "h3", autoHeight: false, collapsible: true });
            });
        </script>

        <script type="text/javascript">

          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', 'UA-5635962-2']);
          _gaq.push(['_trackPageview']);

          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();

        </script>

    </body>
</html>