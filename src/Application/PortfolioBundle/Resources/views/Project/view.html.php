<?php $view->extend('PortfolioBundle::internal.html.php') ?>

<!--details of project-->

<div class="detailsOfProject">

    <!--pagination for project-->
    <div class="next-prev-projects">
        <div class="next">
            <?php if (isset($nextProject)): ?>
                <a href="<?php echo $view['router']->generate('portfolioCategoryProjectView', array('categoryId' => $categoryId, 'projectId' => $nextProject->getId())) ?>">
                    <span>Следующий проект</span> →</a>
                <?php echo $nextProject->getName(); ?>
            <?php else: ?>
                <span>Следующий проект</span> →
            <?php endif; ?>
        </div>
        
        <div class="prev">
            <?php if (isset($previousProject)): ?>
                <a href="<?php echo $view['router']->generate('portfolioCategoryProjectView', array('categoryId' => $categoryId, 'projectId' => $previousProject->getId())) ?>">
                    ← <span>Предыдущий проект</span></a>
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
<p>
«Бэби-клуб» — большая сеть детских развивающих клубов в России и странах ближнего зарубежья. На сайте родители узнают о важности раннего развития малышей, знакомятся с книжкой «После трех уже поздно» и выбирают клуб, чтобы отвести туда ребенка.
</p>

<p>
<img alt="" src="http://img.artlebedev.ru/everything/baby-club/site/babyclub-site-main-poehali.jpg" width="720" height="1053" border="0">
</p>

<p>
Каждый партнерский клуб сети получил свой отдельный мини-сайт — родители прочтут там о преподавателях клуба, найдут расписание, фотографии и видео с занятий.
</p>

<p>
<img alt="" src="http://img.artlebedev.ru/everything/baby-club/site/babyclub-site-inner-gorshina.jpg" width="720" height="1053" border="0">
</p>
</div>

</div>

<!--details of project-->