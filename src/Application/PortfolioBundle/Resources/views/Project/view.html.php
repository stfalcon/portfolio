<?php $view->extend('PortfolioBundle::layout.html.php') ?>

<h4>Project "<?php echo $project->getName(); ?>"</h4>

<div>
    <?php echo $project->getDescription(); ?>
</div>