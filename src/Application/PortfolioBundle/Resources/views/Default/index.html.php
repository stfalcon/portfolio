<?php $view->extend('PortfolioBundle::layout.html.php') ?>

<?php if ($categories): ?>
    <?php foreach ($categories as $category): ?>
        <?php if (count($category->getProjects())): ?>
            <div>
                <h4><?php echo $category->getName(); ?></h4>

                <?php echo $category->getDescription(); ?>

                <ul>
                    <?php foreach ($category->getProjects() as $project): ?>
                        <li>
                            <a href="<?php echo $view['router']->generate('portfolioProjectView', array('id' => $project->getId())) ?>">
                                <?php echo $project->getName(); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    <div class="info">
        Portfolio is empty
    </div>
<?php endif; ?>
