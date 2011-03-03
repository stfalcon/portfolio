<?xml version="1.0" encoding="utf-8"?>
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
                    <li><a href="#">Услуги</a></li>
                    <li><a href="#">Блог</a></li>
                    <li><a href="#">Компания</a></li>
                    <li><a href="#">Контакты</a></li>
                </ul>
                <!--/header menu-->
                <div class="headerSlogan">
                    <span>
                        Веб-студия stfalcon.com — это команда профессионалов, которые любят свою работу и нацелены на качественный результат.
                        <br />
                        Сотрудничая с нами, Вы выбираете надежного партнера для бизнеса!
                    </span>

                    <div class="headerSloganRight"></div>
                    <div class="headerSloganLeft"></div>
                </div>
            </div>
            <!--/header-->

            <!--index content-->
            <div class="contentIndex">
                <?php $view['slots']->output('_content') ?>
            </div>
            <!--/index content-->

        </div>
        <!--/main-->

        <!--footer-->
        <div class="footer indexFooter">
<!--        <br />
        <br />
        <br />
        <div class="footer">-->
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
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/js/jquery.stf.carousel.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/js/jquery.wresize.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/js/jquery.mousewheel.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/js/jquery.main.js'); ?>"></script>
        <!--[if IE 6]>
            <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/js/DD_belatedPNG.js'); ?>"></script>
        <![endif]-->

        <script type="text/javascript">
            jQuery(document).ready(function() {
                $("#accordion").accordion({ header: "h2" });
                $(".indexCarousel").stfCarousel({
                    scroll: 'auto',
                    mousewheel: false,
                    ruler: $("#accordion"),
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