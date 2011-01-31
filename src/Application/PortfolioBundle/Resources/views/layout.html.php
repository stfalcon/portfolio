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
        <link rel="stylesheet" type="text/css" href="<?php echo $view['assets']->getUrl('bundles/portfolio/css/style.css'); ?>" media="screen" />
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
            <div class="grid_12">
                &nbsp;
            </div>
            <div class="clear"></div>
            <div class="grid_8">
                <?php if ($view['session']->hasFlash('notice')): ?>
                    <div class="notice">
                        <?php echo $view['session']->getFlash('notice'); ?>
                    </div>
                <?php endif; ?>

                <?php $view['slots']->output('_content') ?>
            </div>
            <div class="grid_4">
                <div class="box">
                    <h2>
                        <a href="#" id="toggle-login-forms">Login Forms</a>
                    </h2>
                    <div class="block" id="login-forms">
                        <form action="">
                            <fieldset class="login">
                                <legend>Login</legend>
                                <p class="notice">Login to complete your purchase.</p>
                                <p>
                                    <label>Username: </label>
                                    <input type="text" name="username">
                                </p>
                                <p>
                                    <label>Password: </label>
                                    <input type="password" name="password">
                                </p>
                                <input class="login button" type="submit" value="Login">
                            </fieldset>
                        </form>
                        <form action="">
                            <fieldset>
                                <legend>Register</legend>
                                <p>If you do not already have an account, please create a new account to register.</p>
                                <input type="submit" value="Create Account">
                            </fieldset>
                        </form>
                    </div>
                </div>
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
    </body>
</html>