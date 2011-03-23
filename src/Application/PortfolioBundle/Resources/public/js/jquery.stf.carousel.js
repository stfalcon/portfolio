/**
 * stfCarousel - jQuery plugin to simple carousel
 */

/**
 * <div class="carousel">
 *     <div class="prev"></div>
 *     <div>
 *         <ul>
 *             <li><img src="first.jpg" alt="fisrt" /></li>
 *             <li><img src="second.jpg" alt="second" /></li>
 *             <li><img src="third.jpg" alt="third" /></li>
 *         </ul>
 *     </div>
 *     <div class="next"></div>
 * </div>
 */
(function($) {

    $.fn.stfCarousel = function(settings) {
        var config = $.extend({
            speed: 600,
            scroll: 1, // 'auto' - прокручивает на видимое кол. елементов
            start: 0,
            mousewheel: false,
            vertical: false,
            ruler: false, // элемент выступающий в роли линейки по которой замеряется ширина карусели.
            // удобно если есть скрытые карусели, ширину которых невозможно определить
            substract: 0, // значение которое будет вычитатся с ширины карусели
            // например, ширина блоков кнопок
            widthItem: null
        }, settings || {});

        this.each(function() {
            var self = this; // для обращения к объекту из вложенных функций
            var scroll = config.scroll;
            var countVisibleItems;
            var countAllItems = $("ul > li", this).size();
            var widthItem = config.widthItem ? config.widthItem : $("ul > li", this).width();
            var indexCurrentItem = config.start;

            $("ul", this).width(widthItem * countAllItems);

            // инициализируем кнопки вперед/назад и цепляем на них обработчики нажатий
            var btnPrev = $('.btnPrev', this);
            if (btnPrev.size()) {
                btnPrev.click(function() {
                    return goTo(indexCurrentItem - scroll);
                });
            }

            var btnNext = $('.btnNext', this);
            if (btnNext.size()) {
                btnNext.click(function() {
                    return goTo(indexCurrentItem + scroll);
                });
            }

            // подключаем скролл мышкой
            if (config.mousewheel && $.fn.mousewheel) {
                $("ul", this).mousewheel(function(e, delta)  {
                    return goTo(delta < 0 ? indexCurrentItem - scroll : indexCurrentItem + scroll);
                });
            }

            // цепляем обработчик на ресайз окна
//            $(window).resize(function() {
            $(window).wresize(function() {
                resize();
            });

            resize(); // установка размеров при запуске
            goTo(config.start); // прокручиваем к месту старта и заодно ставим disable на нужные кнопки

            // флажок анимации
            var running = false;
            // прокрутка карусели
            function goTo(indexScrollItem) {
                if (!running) {
                    if (indexScrollItem < 0 || indexScrollItem > countAllItems) {
                        indexCurrentItem = 0;
                    } else if (indexScrollItem > countAllItems - countVisibleItems) {
                        indexCurrentItem = countAllItems - countVisibleItems;
                    } else {
                        indexCurrentItem = indexScrollItem;
                    }

                    // анимация стартует. блокируем карусель
                    running = true;
                    $("ul", self).animate(
                        {left: - (indexCurrentItem * widthItem)}, config.speed, 'swing',
                        function() {
                            // анимация закончилась
                            running = false;
                        }
                    );

                    checkButtons();
                }

                return false;
            }


            // переключение стилей для крайних кнопок вперед/назад
            function checkButtons() {
                    if (indexCurrentItem == 0) {
                        btnPrev.addClass('btnPrevDisabled');
                        btnPrev.removeClass('btnPrev');
                    } else {
                        btnPrev.removeClass('btnPrevDisabled');
                        btnPrev.addClass('btnPrev');
                    }

                    if (indexCurrentItem >= countAllItems - countVisibleItems) {
                        btnNext.addClass('btnNextDisabled');
                        btnNext.removeClass('btnNext');
                    } else {
                        btnNext.removeClass('btnNextDisabled');
                        btnNext.addClass('btnNext');
                    }
            }

            // меняем размеры карусели и количество отображаемых элементов
            function resize(){
                if (config.ruler) {
                    width = $(config.ruler).width();
                } else {
                    width = $(self).parent().width();
                }

                // считаем количество видимых элементов карусели
                // @todo что делать если элементы разного размера? если видимые, тогда ладно.
                // а вот если они были в скрытом блоке? показывать скрытый блок за границей экрана, считать и прятать обратно?
                countVisibleItems = Math.floor((width - config.substract) / widthItem);

                if (config.scroll == 'auto') {
                    scroll = countVisibleItems;
                }

                if (countAllItems < indexCurrentItem + countVisibleItems) {
                    goTo(countAllItems - countVisibleItems);
                }
                
                $("ul", self).parent().width(countVisibleItems * widthItem); //.css('margin', '0 auto');

                checkButtons();
            }

        });

        return this;
    };

})(jQuery);
