<?php $view->extend('Portfolio::main.html.php') ?>


<?php if ($categories): ?>
    <div class="accordion">
        <?php foreach ($categories as $category): ?>
            <?php if (count($category->getProjects())): ?>
                <!--accordion block-->
                <h2>
                    <?php echo $category->getName(); ?>
                </h2>

                <div class="service">
                    <p>
                        <?php echo $category->getDescription(); ?>
                    </p>

                    <!--carousel-->
                    <div class="carousel">

                        <div class="btnPrev" title="Назад"></div>

                        <div class="carousel-wrapper">
                            <ul>
                                <?php foreach ($category->getProjects() as $project): ?>
                                    <li>
                                        <a href="<?php echo $view['router']->generate('portfolioCategoryProjectView', array('categoryId' => $category->getId(), 'projectId' => $project->getId())) ?>">
                                            <span>
                                                <img src="<?php echo '/bundles/portfolio/uploads/projects/' . $project->getImage(); ?>" width="240" height="198"
                                                     alt="<?php echo $view->escape($project->getName()); ?>" title="<?php echo $view->escape($project->getName()); ?>" />
                                            </span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="btnNext" title="Вперед"></div>
                    </div>
                    <!--/carousel-->

                    <a href="#" class="all-projects">Посмотреть все работы</a>
                </div>
                <!--/accordion block-->
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>