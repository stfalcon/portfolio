<h2>УСЛУГИ</h2>
<div id="accordion" class="servises">
    <?php foreach ($categories as $category): ?>
        <?php if (count($category->getProjects())): ?>
            <h3><?php echo $category->getName(); ?></h3>
            <ul>
            <?php foreach ($category->getProjects() as $project): ?>
                <?php if ($currentProjectId == $project->getId()): ?>
                    <li class="active">
                        <?php echo $project->getName(); ?>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="<?php echo $view['router']->generate('portfolioCategoryProjectView', array('categoryId' => $category->getId(), 'projectId' => $project->getId())) ?>">
                            <?php echo $project->getName(); ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endforeach; ?>
    <div class="clear"></div>
</div>