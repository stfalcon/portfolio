<?php $view->extend('PortfolioBundle::main.html.php') ?>


<?php if ($categories): ?>
    <div id="accordion">
        <?php foreach ($categories as $category): ?>
            <?php if (count($category->getProjects())): ?>
                <!--index tabbed block-->
                <h2>
                    <?php echo $category->getName(); ?>
                </h2>

                <div class="indexTabCont">
                    <p>
                        <?php echo $category->getDescription(); ?>
                    </p>

                    <!--BEGIN OF CARUSEL-->
                    <div class="indexCarousel">

                        <div class="btnPrev" title="Назад"></div>

                        <div class="indexCarouselWrap">
                            <ul>
                                <?php foreach ($category->getProjects() as $project): ?>
                                    <li>
                                        <a href="<?php echo $view['router']->generate('portfolioProjectView', array('id' => $project->getId())) ?>">
                                            <span>
                                                <img src="<?php echo '/bundles/portfolio/uploads/projects/' . $project->getImage(); ?>" width="240" height="198"
                                                     alt="<?php echo $project->getName(); ?>" title="<?php echo $project->getName(); ?>" />
                                            </span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="btnNext" title="Вперед"></div>
                    </div>
                    <!--/BEGIN OF CARUSEL-->

                </div>
                <!--/index tabbed block-->
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>