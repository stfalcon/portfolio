<?php $view->extend('PortfolioBundle::layout.html.php') ?>

<h4>Projects list</h4>

<?php if ($projects): ?>
    <ul>
        <?php foreach ($projects as $project): ?>
            <li>
                <a href="<?php echo $view['router']->generate('portfolioProjectView', array('id' => $project->getId())) ?>">
                    <?php echo $project->getName(); ?>
                </a>
                <?php // if ($view['security']->isGranted('ROLE_ADMIN')): ?>
                    (<a href="<?php echo $view['router']->generate('portfolioProjectEdit', array('slug' => $project->getSlug())) ?>">Edit project</a>
                    / <a href="<?php echo $view['router']->generate('portfolioProjectDelete', array('slug' => $project->getSlug())) ?>">Delete project</a>)
                <?php // endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <div class="info">
        List of projects is empty
    </div>
<?php endif; ?>
