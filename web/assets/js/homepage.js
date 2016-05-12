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

    // Hide mobile navigation
    $(document).on('click', '.close-mobile-nav', function(){
        $('body').removeClass('open-navigation open-main-nav open-languages-nav');
    });
    $(window).bind('resize', function () {
        if(window.matchMedia && window.matchMedia('(min-width: 800px)').matches){
            $('body').removeClass('open-navigation open-main-nav open-languages-nav');
        }
    });
});

$(document).on('click', '.show-more-info', function(){
   $(this).hide(200);
   $('.hidden-content.hidden').removeClass('hidden');
});
