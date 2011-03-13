<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php $view['slots']->output('title', 'Веб-студия stfalcon.com'); ?></title>
        <link rel="shortcut icon" href="/favicon.ico" />
        <link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('bundles/portfolio/css/main.css'); ?>" media="screen" />
        <!--[if IE 6]>
            <link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('bundles/portfolio/css/main-ie6.css'); ?>" media="screen" />
        <![endif]-->
    </head>

    <body>
        <!--main-->
        <div class="main">

            <!--header-->
            <div class="header">
                <a href="<?php echo $view['router']->generate('homepage'); ?>">
                    <h1>
                        Веб-студия stfalcon.com
                    </h1>
                </a>
                <ul class="headerLang">
                    <li>рус</li>
                    <li><a href="#">eng</a></li>
		</ul>
		<!--header menu-->
		<ul class="headerMenu">
                    <li class="active">Услуги<span></span></li>
                    <li><a href="#">Блог</a></li>
                    <li><a href="#">Компания</a></li>
                    <li><a href="<?php echo $view['router']->generate('portfolioDefaultContacts'); ?>">Контакты</a></li>
		</ul>
                <!--/header menu-->
            </div>
            <!--/header-->

            <!--content-->
            <div class="mainInfo">
                <!--main info block-->
                <ul class="navLang">
                    <li><a href="#">Главная</a></li>
                    <li class="active"><span>Блог</span></li>
                </ul>

                <div class="contentWrap">
                    <div class="content">
                        <?php $view['slots']->output('_content') ?>
                    </div>
                </div>
                <!--/main info block-->

                <!--main share block-->
                <div class="mainShare">
                    <h3><a href="#">Подписаться на RSS ленту</a></h3>

                    <!--twitter block-->
                    <div class="mainShareTwitter">
                        <?php echo $view['actions']->render('PortfolioBundle:Default:twitter'); ?>

                        <p>Следить за <a href="#">@stfalcon</a></p>
                        <div class="mainShareTwitterTop"></div>
                    </div>
                    <!--/twitter block-->

<!--                    <h2>услуги</h2>
                    <ul class="mainShareListOfServ">

                        <li><a href="#">Веб-программирование</a></li>
                        <li>Администрирование

                            <ul>
                                <li><a href="#">партнёрская программа для textbroker.ru</a></li>
                                <li><a href="#">рейтинги персонажей для surlaterre.ru</a></li>
                                <li><a href="#">выделение ключевых слов - textbroker.ru</a></li>
                                <li><a href="#">istc.ru - поддержка сайта (Lotus Notes)</a></li>
                                <li class="active">Сайт «Good Detectives»</li>
                                <li><a href="#">фильтр коллекций для surlaterre.ru</a></li>
                                <li><a href="#">рейтинги персонажей для surlaterre.ru</a></li>
                                <li><a href="#">выделение ключевых слов - textbroker.ru</a></li>
                                <li><a href="#">istc.ru - поддержка сайта (Lotus Notes)</a></li>
                            </ul>

                        </li>
                        <li><a href="#">Веб-дизайн</a></li>
                        <li><a href="#">Копирайтинг</a></li>

                    </ul>

                    <h2>Над проектом работали</h2>

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

                <!--main share block-->

            </div>
            <!--/content-->

            <!--/main-->
        </div>

        <!--footer-->
        <div class="footer">
            <address>
                <span>
                    Контактный телефон:
                    <strong>+380 97 874-03-42</strong>
                    e-mail: <a href="mailo:info@stfalcon.com">info@stfalcon.com</a>
                </span>
                <span class="copyright">
                    &copy; 2009 - <?php echo date('Y'); ?> Веб-студия stfalcon.com
                </span>
            </address>

            <ul>
                <li><a href="#">Услуги</a></li>
                <li><a href="#">Блог</a></li>
                <li><a href="#">Компания</a></li>
                <li><a href="#">Контакты</a></li>
            </ul>

            <form action="#">
                <fieldset>
                    <label for="footerSearch">Поиск по сайту</label>
                    <dl>
                        <dt><input type="text" id="footerSearch" /></dt>
                        <dd><input type="submit" value="" /></dd>
                    </dl>
                </fieldset>
            </form>
        </div>
        <!--/footer-->

        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/vendor/jquery/jquery-1.4.4.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/vendor/jquery-ui/jquery-ui-1.8.9.custom.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/js/jquery.main.js'); ?>"></script>
        <!--[if IE 6]>
            <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/js/DD_belatedPNG.js'); ?>"></script>
        <![endif]-->

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