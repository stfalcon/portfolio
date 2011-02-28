<?php $view->extend('PortfolioBundle::layout.html.php') ?>

<h4>Project "<?php echo $project->getName(); ?>"</h4>

<div>
    <?php echo $project->getDescription(); ?>
</div>
<div>
    <img src="<?php echo '/bundles/portfolio/uploads/projects/' . $project->getImage(); //echo $this['imagine']->filter('/bundles/portfolio/uploads/projects/' . $project->getImage(), 'thumbnail') ?>" />
</div>