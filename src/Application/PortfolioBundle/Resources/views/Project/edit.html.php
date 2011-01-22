<?php $view->extend('PortfolioBundle::layout.html.php') ?>

<h2>Edit project "<?php echo $project->getName(); ?>"</h2>

<form action="#" method="post">

    <?php echo $view['form']->render($form) ?>

    <input type="submit" value="Send" />
</form>