<?php $view->extend('::layout.html.php') ?>

<h4>Edit project "<?php echo $project->getName(); ?>"</h4>

<form action="#" method="post" enctype="multipart/form-data">
    <fieldset>
        <?php echo $view['form']->widget($form) ?>
        <input type="submit" value="Save" />
    </fieldset>
</form>