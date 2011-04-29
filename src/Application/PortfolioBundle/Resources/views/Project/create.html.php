<?php $view->extend('PortfolioBundle::layout.html.php') ?>

<h4>Create new project</h4>

<form action="#" method="post" enctype="multipart/form-data">
    <fieldset>
        <?php echo $view['form']->widget($form) ?>
        <input type="submit" value="Send" />
    </fieldset>
</form>