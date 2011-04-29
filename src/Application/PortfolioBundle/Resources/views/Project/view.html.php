<?php $view->extend('::internal.html.php') ?>
<?php $view['slots']->set('title', 'Веб-студия stfalcon.com - ' . $project->getName()) ?>

<!--details of project-->

<div class="detailsOfProject">

    <?php echo $view['actions']->render('PortfolioBundle:Project:nearbyProjects', array('category' => $category, 'project' => $project)/*, array('standalone' => true)*/); ?>

    <h1>
        <?php echo $project->getName(); ?>
    </h1>

    <?php if ($project->getUrl()): ?>
        <p>
            <a href="<?php echo $project->getUrl(); ?>"><?php echo $project->getUrl(); ?></a>
        </p>
    <?php endif; ?>

<!--    <span class="dateOfProject">
        <span>Дата выпуска: <?php //echo $project->getDate(); ?></span>
        <?php if ($project->getUrl()): ?>
            <a href="<?php echo $project->getUrl(); ?>"><img src="<?php echo $project->getUrl() . '/favicon.ico'; ?>" width="16" height="16" /><?php echo $project->getUrl(); ?></a>
        <?php endif; ?>
    </span>-->

    <div style="padding-bottom: 30px;">
        <?php echo $project->getDescription(); ?>
    </div>

</div>

<!--details of project-->