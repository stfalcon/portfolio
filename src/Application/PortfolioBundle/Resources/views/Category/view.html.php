<?php $view->extend('PortfolioBundle::layout.html.php') ?>

<h4>Category "<?php echo $category->getName(); ?>"</h4>

<?php echo $category->getDescription(); ?>

<?php if (count($category->getProjects())): ?>
    <ul>
        <?php foreach ($category->getProjects() as $project): ?>
            <li>
                <a href="<?php echo $view['router']->generate('portfolioProjectView', array('id' => $project->getId())) ?>">
                    <?php echo $project->getName(); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <div class="info">
        This category is empty
    </div>
<?php endif; ?>
