<?php $view->extend('PortfolioBundle::layout.html.php') ?>

<h4>Edit category "<?php echo $category->getName(); ?>"</h4>

<form action="#" method="post">
    <fieldset>
        <?php echo $view['form']->render($form) ?>
        <input type="submit" value="Save" />
    </fieldset>
</form>