<?php $view->extend('PortfolioBundle::internal.html.php') ?>

<!--details of project-->

<div class="detailsOfProject">

    <!--pagination for project-->

    <div class="projectPagination">

        <div class="nextProj">
            <a href="#"><span>Следующий проект</span> →</a>
            									Партнёрская программа для textbroker.ru
        </div>

        <div class="prevProj">
            <a href="#">← <span>Предыдущий проект</span></a>
            									Календарь событий для surlaterre.ru
        </div>

    </div>

    <!--pagination for project-->

    <h1>
        Project "<?php echo $project->getName(); ?>"
    </h1>

    <span class="dateOfProject">
        <span>Дата выпуска: <?php echo $project->getDate(); ?></span>
        <a href="#"><img src="<?php echo $project->getUrl() . '/favicon.ico'; ?>" alt="" width="12" height="15"><?php echo $project->getUrl(); ?></a>
    </span>

    <p>
        <?php echo $project->getDescription(); ?>
    </p>

    <div class="screenOfProject">
        <a href="#"><img alt="" width="374" height="569" src="pic/pic10.png"></a>
        <div class="top"></div>
        <div class="bottom"></div>
    </div>

    <h3>С помощью нашего сайта можно:</h3>

    <ul class="listOfGoods">

        <li>Создавать собственные ленты товаров. Вы выбираете только те категории товаров, которые вам интересны. Это позволяет сэкономить время на поиски нужной информации и избавляет от необходимости просматривать сотни страниц с рекламными предложениями, которые вам неинтересны.</li>
        <li>Получать самую свежую информацию о выбранных категориях по подписке. Вы можете выбрать удобный для вас способ получения информации – RSS или e-mail рассылка.</li>
        <li>Быть в курсе последних обновлений ассортимента интернет-магазинов. Магазины могут настроить автоматическое или ручное обновление информации об их ассортименте. Таким образом, пользователи получают возможность незамедлительно узнавать где и по какой цене появляется товар, который их интересует.</li>

    </ul>

    <h3>Функциональныe страницы:</h3>

    <!--BEGIN OF CARUSEL-->

    <div class="innerCarousel">

        <a href="#" class="btnPrev" title="преыддущая"></a>
        <a href="#" class="btnNext" title="следующая"></a>

        <div class="innerCarouselWrap">

            <ul>

                <li>
                    <a href="#"><img alt="" width="140" height="213" src="pic/pic10.png"> Создавать собственные ленты товаров.</a>
                </li>
                <li>
                    <a href="#"><img alt="" width="140" height="213" src="pic/pic10.png"> Вы выбираете только те категории товаров, которые вам интересны.</a>
                </li>
                <li>
                    <a href="#"><img alt="" width="140" height="213" src="pic/pic10.png"> Это позволяет сэкономить время на поиски нужной информации.</a>
                </li>
                <li>
                    <a href="#"><img alt="" width="140" height="213" src="pic/pic10.png"></a>
                </li>
                <li>
                    <a href="#"><img alt="" width="140" height="213" src="pic/pic10.png"></a>
                </li>
                <li>
                    <a href="#"><img alt="" width="140" height="213" src="pic/pic10.png"></a>
                </li>

            </ul>

        </div>

    </div>

    <!--/BEGIN OF CARUSEL-->

    <!--pagination for project-->

    <div class="projectPagination">

        <div class="nextProj">
            <a href="#"><span>Следующий проект</span> →</a>
							Партнёрская программа для textbroker.ru
        </div>

        <div class="prevProj">
            <a href="#">← <span>Предыдущий проект</span></a>
							Календарь событий для surlaterre.ru
        </div>

    </div>

    <!--pagination for project-->

</div>

<!--details of project-->