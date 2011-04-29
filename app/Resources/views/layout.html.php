<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php $view['slots']->output('title', 'Hello Application') ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('bundles/portfolio/vendor/fluid960gs/css/reset.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('bundles/portfolio/vendor/fluid960gs/css/text.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('bundles/portfolio/vendor/fluid960gs/css/960.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('bundles/portfolio/vendor/fluid960gs/css/layout.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('bundles/portfolio/vendor/fluid960gs/css/nav.css'); ?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('bundles/portfolio/css/admin.css'); ?>" media="screen" />
        <!--[if IE 6]><link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('bundles/portfolio/vendor/fluid960gs/css/ie6.css'); ?>" media="screen" /><![endif]-->
        <!--[if IE 7]><link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('bundles/portfolio/vendor/fluid960gs/css/ie.css'); ?>" media="screen" /><![endif]-->
    </head>
    <body>
        <div class="container_12">
            <div class="grid_12">
                <h1 id="branding">
                    <a href="<?php echo $view['router']->generate('homepage') ?>">PortfolioBundle</a>
                </h1>
            </div>
            <div class="clear"></div>
            <?php //if ($view['security']->isGranted('ROLE_ADMIN')): ?>
                <div class="grid_12">
                    <ul class="nav main">
                        <li>
                            <a href="<?php echo $view['router']->generate('portfolioProjectIndex') ?>">Projects</a>
                            <ul>
                                <li>
                                    <a href="<?php echo $view['router']->generate('portfolioProjectCreate') ?>">Create new project</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="<?php echo $view['router']->generate('portfolioCategoryIndex') ?>">Categories</a>
                            <ul>
                                <li>
                                    <a href="<?php echo $view['router']->generate('portfolioCategoryCreate') ?>">Create new category</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="clear"></div>
            <?php //endif; ?>
            <div class="grid_12">
                &nbsp;
            </div>
            <div class="clear"></div>
            <div class="grid_12">
                <?php if ($view['session']->hasFlash('notice')): ?>
                    <div class="notice">
                        <?php echo $view['session']->getFlash('notice'); ?>
                    </div>
                <?php endif; ?>

                <?php $view['slots']->output('_content') ?>
            </div>
            <div class="clear"></div>
            <div class="grid_12" id="site_info">
                <div class="box">
                    <p>
                        Powered by <a href="http://symfony-reloaded.org/">Symfony2 PHP framework</a>
                    </p>
                </div>
            </div>
            <div class="clear"></div>
        </div>

        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/vendor/jquery/jquery-1.4.4.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/vendor/jquery-ui/jquery-ui-1.8.9.custom.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/vendor/fluid960gs/js/jquery-fluid16.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/js/jquery.stf.carousel.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/js/jquery.wresize.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo $view['assets']->getUrl('bundles/portfolio/js/jquery.mousewheel.min.js'); ?>"></script>
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
    </body>
</html>