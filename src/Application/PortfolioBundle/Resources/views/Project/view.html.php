<?php $view->extend('PortfolioBundle::internal.html.php') ?>
<?php $view['slots']->set('title', 'Веб-студия stfalcon.com - ' . $currentProject->getName()) ?>

<!--details of project-->

<div class="detailsOfProject">

    <!--pagination for project-->
    <div class="next-prev-projects">
        <div class="next">
            <?php if (isset($nextProject)): ?>
                <a href="<?php echo $view['router']->generate('portfolioCategoryProjectView', array('categoryId' => $categoryId, 'projectId' => $nextProject->getId())) ?>">
                    Следующий проект →
                </a>
                <br />
                <?php echo $nextProject->getName(); ?>
            <?php else: ?>
                Следующий проект →
            <?php endif; ?>
        </div>
        
        <div class="prev">
            <?php if (isset($previousProject)): ?>
                <a href="<?php echo $view['router']->generate('portfolioCategoryProjectView', array('categoryId' => $categoryId, 'projectId' => $previousProject->getId())) ?>">
                    ← Предыдущий проект
                </a>
                <br />
                <?php echo $previousProject->getName(); ?>
            <?php else: ?>
                ← Предыдущий проект
            <?php endif; ?>
        </div>
    </div>
    <!--pagination for project-->

    <h1>
        <?php echo $currentProject->getName(); ?>
    </h1>

    <?php if ($currentProject->getUrl()): ?>
        <p>
            <a href="<?php echo $currentProject->getUrl(); ?>"><?php echo $currentProject->getUrl(); ?></a>
        </p>
    <?php endif; ?>

<!--    <span class="dateOfProject">
        <span>Дата выпуска: <?php echo $currentProject->getDate(); ?></span>
        <?php if ($currentProject->getUrl()): ?>
            <a href="<?php echo $currentProject->getUrl(); ?>"><img src="<?php echo $currentProject->getUrl() . '/favicon.ico'; ?>" width="16" height="16" /><?php echo $currentProject->getUrl(); ?></a>
        <?php endif; ?>
    </span>-->

    <div style="padding-bottom: 30px;">
        <?php echo $currentProject->getDescription(); ?>
    </div>

    <!--pagination for project-->
    <div class="next-prev-projects">
        <div class="next">
            <?php if (isset($nextProject)): ?>
                <a href="<?php echo $view['router']->generate('portfolioCategoryProjectView', array('categoryId' => $categoryId, 'projectId' => $nextProject->getId())) ?>">
                    Следующий проект →
                </a>
                <br />
                <?php echo $nextProject->getName(); ?>
            <?php else: ?>
                Следующий проект →
            <?php endif; ?>
        </div>

        <div class="prev">
            <?php if (isset($previousProject)): ?>
                <a href="<?php echo $view['router']->generate('portfolioCategoryProjectView', array('categoryId' => $categoryId, 'projectId' => $previousProject->getId())) ?>">
                    ← Предыдущий проект
                </a>
                <br />
                <?php echo $previousProject->getName(); ?>
            <?php else: ?>
                ← Предыдущий проект
            <?php endif; ?>
        </div>
    </div>
    <!--pagination for project-->

</div>

<!--details of project-->