<?php $view->extend('::layout.html.php') ?>

<h4>Create new category</h4>

<form action="#" method="post">
    <fieldset>
        <?php echo $view['form']->widget($form); ?>
        <input type="submit" value="Send" />
    </fieldset>
</form>