<?php $view->extend('Portfolio::layout.html.php') ?>

<h4>Create new category</h4>

<form action="#" method="post">
    <fieldset>
        <?php echo $view['form']->render($form) ?>
        <input type="submit" value="Send" />
    </fieldset>
</form>