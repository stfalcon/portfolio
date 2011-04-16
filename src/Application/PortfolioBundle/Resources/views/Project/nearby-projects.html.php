<!--pagination for project-->
<div class="next-prev-projects">
    <div class="next">
        <?php if (isset($nextProject)): ?>
            <a href="<?php echo $view['router']->generate('portfolioCategoryProjectView', array('categorySlug' => $category->getSlug(), 'projectSlug' => $nextProject->getSlug())) ?>">
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
            <a href="<?php echo $view['router']->generate('portfolioCategoryProjectView', array('categorySlug' => $category->getSlug(), 'projectSlug' => $previousProject->getSlug())) ?>">
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