<?php $view->extend('::layout.html.php') ?>

<h4>Edit category "<?php echo $category->getName(); ?>"</h4>

<form action="#" method="post">
    <fieldset>
        <?php echo $view['form']->widget($form) ?>
        <input type="submit" value="Save" />
    </fieldset>
</form>