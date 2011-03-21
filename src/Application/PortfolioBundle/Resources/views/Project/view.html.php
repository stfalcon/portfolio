<?php $view->extend('PortfolioBundle::internal.html.php') ?>

<!--details of project-->

<div class="detailsOfProject">

    <!--pagination for project-->
    <div class="projectPagination">
        <div class="nextProj">
            <?php if (isset($nextProject)): ?>
                <a href="<?php echo $view['router']->generate('portfolioCategoryProjectView', array('categoryId' => $categoryId, 'projectId' => $nextProject->getId())) ?>">
                    <span>Следующий проект</span> →
                </a>
                <?php echo $nextProject->getName(); ?>
            <?php else: ?>
                    <span>Следующий проект</span> →
            <?php endif; ?>
        </div>
        <div class="prevProj">
            <?php if (isset($previousProject)): ?>
                <a href="<?php echo $view['router']->generate('portfolioCategoryProjectView', array('categoryId' => $categoryId, 'projectId' => $previousProject->getId())) ?>">
                    ← <span>Предыдущий проект</span>
                </a>
                <?php echo $previousProject->getName(); ?>
            <?php else: ?>
                    ← <span>Предыдущий проект</span>
            <?php endif; ?>
        </div>
    </div>
    <!--pagination for project-->

    <h1>
        <?php echo $currentProject->getName(); ?>
    </h1>

    <span class="dateOfProject">
        <span>Дата выпуска: <?php echo $currentProject->getDate(); ?></span>
        <?php if ($currentProject->getUrl()): ?>
            <a href="<?php echo $currentProject->getUrl(); ?>"><img src="<?php echo $currentProject->getUrl() . '/favicon.ico'; ?>" width="16" height="16" /><?php echo $currentProject->getUrl(); ?></a>
        <?php endif; ?>
    </span>

    <p>
        <?php echo $currentProject->getDescription(); ?>
    </p>

    <!--pagination for project-->
    <div class="projectPagination">
        <div class="nextProj">
            <?php if (isset($nextProject)): ?>
                <a href="<?php echo $view['router']->generate('portfolioCategoryProjectView', array('categoryId' => $categoryId, 'projectId' => $nextProject->getId())) ?>">
                    <span>Следующий проект</span> →
                </a>
                <?php echo $nextProject->getName(); ?>
            <?php else: ?>
                    <span>Следующий проект</span> →
            <?php endif; ?>
        </div>
        <div class="prevProj">
            <?php if (isset($previousProject)): ?>
                <a href="<?php echo $view['router']->generate('portfolioCategoryProjectView', array('categoryId' => $categoryId, 'projectId' => $previousProject->getId())) ?>">
                    ← <span>Предыдущий проект</span>
                </a>
                <?php echo $previousProject->getName(); ?>
            <?php else: ?>
                    ← <span>Предыдущий проект</span>
            <?php endif; ?>
        </div>
    </div>
    <!--pagination for project-->

</div>

<!--details of project-->