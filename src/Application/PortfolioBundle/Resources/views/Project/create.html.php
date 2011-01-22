<?php $view->extend('PortfolioBundle::layout.html.php') ?>

<h2>Create new project</h2>

<form action="#" method="post">

    <?php echo $view['form']->render($form) ?>

    <input type="submit" value="Send" />
</form>