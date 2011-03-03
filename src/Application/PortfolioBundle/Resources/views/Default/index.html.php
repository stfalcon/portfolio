<?php $view->extend('PortfolioBundle::main.html.php') ?>


<?php if ($categories): ?>
    <?php foreach ($categories as $category): ?>
        <?php if (count($category->getProjects())): ?>
            <div>
                <h4>
                    <a href="<?php echo $view['router']->generate('portfolioCategoryView', array('id' => $category->getId())) ?>">
                        <?php echo $category->getName(); ?>
                    </a>
                </h4>

                <?php echo $category->getDescription(); ?>

                <ul>
                    <?php foreach ($category->getProjects() as $project): ?>
                        <li>
                            <a href="<?php echo $view['router']->generate('portfolioProjectView', array('id' => $project->getId())) ?>">
                                <?php echo $project->getName(); ?>
<!--                                <img src="<?php echo '/bundles/portfolio/uploads/projects/' . $project->getImage(); //echo $this['imagine']->filter('/bundles/portfolio/uploads/projects/' . $project->getImage(), 'thumbnail') ?>" />-->
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
