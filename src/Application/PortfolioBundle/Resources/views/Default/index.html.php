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


<!--twitter, rss etc.-->

<div class="indexSharedBlock">

    <!--rss column-->

    <div>

        <h3><span class="rss">Записи в блог</span></h3>

        <ul>

            <li>
                <a href="#">Статистика по IE на wallpaper.in.ua за останній рік</a>
                <span class="counComents">5</span>
            </li>
            <li>
                <a href="#">Clean .svn folders</a>
                <span class="counComents">14</span>
            </li>
            <li>
                <a href="#">Конференція присвячена Zend Framework ZFConf 2010</a>
                <span class="counComents">111</span>
            </li>
            <li>
                <a href="#">Тюнер для гітари WST-523</a>
                <span class="counComents">5</span>
            </li>
            <li>
                <a href="#">Джоэл. И снова о программировании</a>
            </li>
            <li>
                <a href="#">Конференція присвячена Zend Framework ZFConf 2010</a>
                <span class="counComents">13</span>
            </li>

        </ul>

        <a href="#" class="seeAllPosts">все записи</a>

    </div>

    <!--/rss column-->

    <!--articles column-->

    <div>

        <h3>Статьи</h3>

        <ul>

            <li>
                <a href="#">Статистика по IE на wallpaper.in.ua за останній рік</a>
                <strong>Танасийчук С., 7 марта 2010</strong>
            </li>
            <li>
                <a href="#">Clean .svn folders</a>
                <strong>Танасийчук С., 7 марта 2010</strong>
            </li>
            <li>
                <a href="#">Конференція присвячена Zend Framework ZFConf 2010</a>
                <strong>Танасийчук С., 7 марта 2010</strong>
            </li>
            <li>
                <a href="#">Тюнер для гітари WST-523</a>
                <strong>Танасийчук С., 7 марта 2010</strong>
            </li>
            <li>
                <a href="#">Джоэл. И снова о программировании</a>
                <strong>Танасийчук С., 7 марта 2010</strong>
            </li>

        </ul>

        <a href="#" class="seeAllPosts">все статьи</a>

    </div>

    <!--/articles column-->


    <!--twitter column-->

    <div>

        <h3><span class="twitter">twitter</span></h3>

        <ul>

            <li>
                мене лякають блоги які пишуть по кілька постів в день і рсс агрегатори. а вас?
                <em>11:50 PM Mar 14th</em>
            </li>
            <li>
                прогрес. 2ві сторінки зробив...
                <em>11:50 PM Mar 14th</em>
            </li>
            <li>
                єс. нарешті я сідаю робити презентації для @<a href="#">zfconf </a>:)
                <em>11:50 PM Mar 14th</em>
            </li>
            <li>
                зробив для Кукорами іморт статистики з Google Analitycs <a href="#">http://cookorama.net/page/statistics/</a>
                <em>11:50 PM Mar 14th</em>
            </li>

        </ul>

    </div>

    <!--/twitter column-->

</div>

<!--/twitter, rss etc.-->