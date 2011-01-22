<?php $view->extend('::base.html.php') ?>

<h1>stfalcon-studio portfolio</h1>

<ul>
    <li>
        <a href="<?php echo $view['router']->generate('portfolioProjectIndex') ?>">Projects list</a>
    </li>
    <li>
        <a href="<?php echo $view['router']->generate('portfolioProjectCreate') ?>">Create new project</a>
    </li>
</ul>

<?php echo $view['session']->getFlash('notice') ?>

<?php $view['slots']->output('_content') ?>
