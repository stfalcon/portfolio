<?php $view->extend('PortfolioBundle::internal.html.php') ?>

<!--details of project-->

<div class="detailsOfProject">

    <!--pagination for project-->

<!--    <div class="projectPagination">

        <div class="nextProj">
            <a href="#"><span>Следующий проект</span> →</a>
            									Партнёрская программа для textbroker.ru
        </div>

        <div class="prevProj">
            <a href="#">← <span>Предыдущий проект</span></a>
            									Календарь событий для surlaterre.ru
        </div>

    </div>-->

    <!--pagination for project-->

    <h1>
        Project "<?php echo $project->getName(); ?>"
    </h1>

    <span class="dateOfProject">
        <span>Дата выпуска: <?php echo $project->getDate(); ?></span>
        <?php if ($project->getUrl()): ?>
            <a href="#"><img src="<?php echo $project->getUrl() . '/favicon.ico'; ?>" /><?php echo $project->getUrl(); ?></a>
        <?php endif; ?>
    </span>

    <p>
        <?php echo $project->getDescription(); ?>
    </p>
    <!--pagination for project-->

</div>

<!--details of project-->