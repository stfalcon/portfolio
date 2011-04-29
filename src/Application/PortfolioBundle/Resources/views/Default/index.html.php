<?php $view->extend('::main.html.php') ?>


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
                                        <a href="<?php echo $view['router']->generate('portfolioCategoryProjectView', array('categorySlug' => $category->getSlug(), 'projectSlug' => $project->getSlug())) ?>">
                                            <span>
                                                <img src="<?php echo '/bundles/portfolio/uploads/projects/' . $project->getImageFilename(); ?>" width="240" height="198"
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

<!--                    <a href="#" class="all-projects">Посмотреть все работы</a>-->
                </div>
                <!--/accordion block-->
            <?php endif; ?>
        <?php endforeach; ?>

                <h2>Администрирование серверов</h2>

                <div class="service">
                    <p>
                        Настройка сервера и всех необходимых служб. Круглосуточный мониторинг работы сервера и оперативное решение возникших проблем. Консультации по вопросам администрирования.
                    </p>

                    <!--carousel-->
                    <div class="carousel">

                        <div class="btnPrev" title="Назад"></div>

                        <div class="carousel-wrapper">
                            <ul>
                                <li>
                                    <a href="#" target="_blank" onclick="return false;">
                                        <span>
                                            <img width="240" height="198" src="/upload/portfolio/projects/previews/freebsd.png"
                                                 alt="Установка и настройка FreeBSD 6.* и выше" title="Установка и настройка FreeBSD 6.* и выше" />
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" target="_blank" onclick="return false;">
                                        <span>
                                            <img width="240" height="198" src="/upload/portfolio/projects/previews/ubuntu.png"
                                                 alt="Установка и настройка Ubuntu Server Edition" title="Установка и настройка Ubuntu Server Edition" />
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" target="_blank" onclick="return false;">
                                        <span>
                                            <img width="240" height="198" src="/upload/portfolio/projects/previews/debian.png"
                                                 alt="Установка и настройка Debian" title="Установка и настройка Debian" />
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" target="_blank" onclick="return false;">
                                        <span>
                                            <img width="240" height="198" src="/upload/portfolio/projects/previews/linux.jpg"
                                                 alt="Установка и настройка других Linux based ОС" title="Установка и настройка других Linux based ОС" />
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" target="_blank" onclick="return false;">
                                        <span>
                                            <img width="240" height="198" src="/upload/portfolio/projects/previews/apache.jpg"
                                                 alt="Установка и настройка веб-сервера Apache и дополнительных модулей к нему" title="Установка и настройка веб-сервера Apache и дополнительных модулей к нему" />
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" target="_blank" onclick="return false;">
                                        <span>
                                            <img width="240" height="198" src="/upload/portfolio/projects/previews/nginx.jpg"
                                                 alt="Установка и настройка веб-сервера nginx и дополнительных модулей к нему" title="Установка и настройка веб-сервера nginx и дополнительных модулей к нему"/>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" target="_blank" onclick="return false;">
                                        <span>
                                            <img width="240" height="198" src="/upload/portfolio/projects/previews/php.png"
                                                 alt="Установка и настройка PHP и дополнительных модулей к нему" title="Установка и настройка PHP и дополнительных модулей к нему" />
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" target="_blank" onclick="return false;">
                                        <span>
                                            <img width="240" height="198" src="/upload/portfolio/projects/previews/mysql.jpg"
                                                 alt="Установка и настройка СУБД MySQL" title="Установка и настройка СУБД MySQL" />
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" target="_blank" onclick="return false;">
                                        <span>
                                            <img width="240" height="198" src="/upload/portfolio/projects/previews/pgsql.png"
                                                   alt="Установка и настройка СУБД PgSQL" title="Установка и настройка СУБД PgSQL" />
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" target="_blank">
                                        <span>
                                            <img width="240" height="198" src="/upload/portfolio/projects/previews/postfix.jpg"
                                                 alt="Установка и настройка MTA Postfix" title="Установка и настройка MTA Postfix" />
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" target="_blank" onclick="return false;">
                                        <span>
                                            <img width="240" height="198" src="/upload/portfolio/projects/previews/exim.png"
                                                 alt="Установка и настройка MTA Exim" title="Установка и настройка MTA Exim" />
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" target="_blank" onclick="return false;">
                                        <span>
                                            <img width="240" height="198" src="/upload/portfolio/projects/previews/munin.png"
                                                 alt="Установка и настройка системы мониторинга Munin" title="Установка и настройка системы мониторинга Munin" />
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" target="_blank" onclick="return false;">
                                        <span>
                                            <img width="240" height="198" src="/upload/portfolio/projects/previews/proftpd.png"
                                                 alt="Установка и настройка ftp сервера ProFTPD" title="Установка и настройка ftp сервера ProFTPD" />
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="btnNext" title="Вперед"></div>
                    </div>
                    <!--/carousel-->

<!--                    <a href="#" class="all-projects">Посмотреть все работы</a>-->
                </div>
                <!--/accordion block-->
    </div>
<?php endif; ?>