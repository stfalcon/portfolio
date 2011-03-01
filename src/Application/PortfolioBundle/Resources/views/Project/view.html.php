<?php $view->extend('PortfolioBundle::layout.html.php') ?>

<h4>Project "<?php echo $project->getName(); ?>"</h4>

<dl>
    <?php if ($project->getUrl()): ?>
    <dt>Url:</dt>
    <dd>
        <?php echo $project->getUrl(); ?>
    </dd>
    <?php endif; ?>

    <?php if ($project->getDescription()): ?>
    <dt>Description:</dt>
    <dd>
        <?php echo $project->getDescription(); ?>
    </dd>
    <?php endif; ?>

    <?php if ($project->getImage()): ?>
    <dt>Image:</dt>
    <dd>
        <img src="<?php echo '/bundles/portfolio/uploads/projects/' . $project->getImage(); //echo $this['imagine']->filter('/bundles/portfolio/uploads/projects/' . $project->getImage(), 'thumbnail') ?>" alt="" />
    </dd>
    <?php endif; ?>
</dl>