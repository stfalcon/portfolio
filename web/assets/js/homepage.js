$(function(){
    var $tabsTitle = $('.tabs-header .tab-title'),
        $tabs = $('.tabs-content .tab');

    $(document).on('click', '.tabs-header .tab-title', function(){
        var $item = $(this),
            index = $.inArray(this, $tabsTitle);

        $tabsTitle.removeClass('active');
        $tabs.removeClass('active');

        $item.addClass('active');
        $($tabs[index]).addClass('active');
    });
});

$(document).ready(function(){
    // Show mobile navigation
    $(document).on('click', '.show-mobile-nav', function(){
        if(!$(this).hasClass('current-language')) {
            $('body').addClass('open-navigation open-main-nav');
        } else {
            $('body').addClass('open-navigation open-languages-nav');
        }
    });


    var isSliderInit = false,
        tabSlider,
        sliderSettings = {
            horizontal: 1,
            itemNav: window.matchMedia("(max-width: 500px)").matches ? 'forceCentered' : 'centered',
            smart: 1,
            activateOn: 'click',
            elasticBounds: 1,
            mouseDragging: 1,
            touchDragging: 1,
            releaseSwing: 1,
            startAt: 0,
            scrollBy: 0,
            activatePageOn: 'click',
            speed: 300,
            clickBar: 1
        },
        $frame  = $('.project-tabs-wrap');

    function initMobileSlider(){
        if (window.matchMedia && window.matchMedia("(max-width: 1023px)").matches) {
            if(!isSliderInit){
                tabSlider = new Sly($frame, sliderSettings);

                tabSlider.on('load', function(){
                    var $ul = $frame.find('ul');
                    $ul.width($ul.width()+2);
                });

                tabSlider.init();
                isSliderInit = true;
            } else {
                tabSlider.reload();
            }
        } else {
            if (window.matchMedia && window.matchMedia("(min-width: 1024px)").matches && isSliderInit) {
                tabSlider.destroy();
                isSliderInit = false;
            }
        }
    };

    // initMobileSlider();

    // Hide mobile navigation
    $(document).on('click', '.close-mobile-nav', function(){
        $('body').removeClass('open-navigation open-main-nav open-languages-nav');
    });
    $(window).bind('resize', function () {
        // initMobileSlider();
        if(window.matchMedia && window.matchMedia('(min-width: 800px)').matches){
            $('body').removeClass('open-navigation open-main-nav open-languages-nav');
        }
    });
});

$(document).on('click', '.show-more-info', function(){
   $(this).hide(200);
   $('.hidden-content.hidden').removeClass('hidden');
});
