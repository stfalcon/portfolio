<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php $view['slots']->output('title', 'Веб-студия stfalcon.com'); ?></title>
        <link rel="shortcut icon" href="/favicon.ico" />
        <link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('bundles/portfolio/css/style.css'); ?>" media="screen" />
    </head>

    <body>
        <!--header-->
        <div id="header">
            <a href="<?php echo $view['router']->generate('homepage'); ?>">
                <h1 class="logo">
                    Веб-студия stfalcon.com
                </h1>
            </a>

            <div class="langs">
                <ul>
                    <li>рус</li>
                    <li><a href="#">eng</a></li>
                </ul>
            </div>

            <div class="menu">
                <?php echo $view['menu']->get('main')->render(); ?>
            </div>

            <div class="slogan">
                <span>
                    Веб-студия stfalcon.com — это команда профессионалов, которые любят свою работу и нацелены на качественный результат.
                    <br />
                    Сотрудничая с нами, Вы выбираете надежного партнера для бизнеса!
                </span>
                <div class="slogan-right"></div>
                <div class="slogan-left"></div>
            </div>
        </div>
        <!--/header-->

        <!--index content-->
        <div id="content-index">
            <?php $view['slots']->output('_content') ?>

            <div class="shared">
                <div>
                    <h3><span class="feed">Записи в блог</span></h3>
                    <ul>
                        <?php foreach ($feed as $item): ?>
                            <li class="feed-item">
                                <a href="<?php echo $item->{'feedburner:origLink'}; ?>"><?php echo $item->title(); ?></a>
                                <span class="comments"><a href="<?php echo $item->{'comments'}[0]; ?>"><?php echo $item->{'comments'}[1]; ?></a></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="http://blog.stfalcon.com" class="all-posts">все записи</a>
                </div>

                <div>
                    <h3><span class="feed">Статьи</span></h3>
                    <ul>
                        <li>
                            <a href="#">Статистика по IE на wallpaper.in.ua за останній рік</a>
                            <strong>Танасийчук С., 7 марта 2010</strong>
                        </li>
                        <li>
                            <a href="#">Clean .svn folders</a>
                            <strong>Танасийчук С., 7 марта 2010</strong>
                        </li>
                        <li>
                            <a href="#">Конференція присвячена Zend Framework ZFConf 2010</a>
                            <strong>Танасийчук С., 7 марта 2010</strong>
                        </li>
                        <li>
                            <a href="#">Тюнер для гітари WST-523</a>
                            <strong>Танасийчук С., 7 марта 2010</strong>
                        </li>
                        <li>
                            <a href="#">Джоэл. И снова о программировании</a>
                            <strong>Танасийчук С., 7 марта 2010</strong>
                        </li>
                    </ul>

                    <a href="#" class="seeAllPosts">все статьи</a>
                </div>

                <div>
                    <h3><span class="twitter">Twitter</span></h3>
                    <?php echo $view['actions']->render('PortfolioBundle:Default:twitter', array('count' => 7)); ?>
                </div>
            </div>

        </div>
        <!--/index content-->

        <!--footer-->
        <div id="footer" style="background: none;">
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
            </div>
        </div>
        <!--/footer-->

        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/vendor/jquery/jquery-1.4.4.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/vendor/jquery-ui/jquery-ui-1.8.9.custom.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/js/jquery.stf.carousel.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/js/jquery.wresize.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/js/jquery.mousewheel.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/js/jquery.main.js'); ?>"></script>
        <!--[if IE 6]>
            <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/js/DD_belatedPNG.js'); ?>"></script>
        <![endif]-->

        <script type="text/javascript">
            jQuery(document).ready(function() {
                $("#content-index .accordion").accordion({ header: "h2" });
                $("#content-index .carousel").stfCarousel({
                    scroll: 'auto',
                    mousewheel: false,
                    ruler: $("#content-index .accordion"),
                    widthItem: 280,
                    substract: 144
                });
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