$(document).ready(function () {
    function bgScroll() {
        var bgClass = $(".bg-item"),
            heightWindow = $(window).outerHeight(),
            bgWrapper = $('#bg-wrapper');

        bgClass.each(function (i, elem) {
            if ($(window).scrollTop() + heightWindow - $(elem).outerHeight() / 1.5 >= $(elem).offset().top) {
                bgWrapper.css('background', $(elem).attr('data-bg'));

                if(window.innerWidth > 1023) {
                    $(elem).find('.project-item__description').addClass('project-item__description--visible');
                }

                if ($(elem).attr('data-bg') === ('#FFFFFF')) {
                    $(elem).parent('.our-services').siblings('.projects-list').find('.bg-item--last').css('color', '#2c373c');
                }

                if ($(elem).hasClass('project-item--dark-text')) {
                    $(elem).prev('.project-item--white-text').css('color', '#2c373c');
                } else {
                    $(elem).prev('.project-item--dark-text').css('color', '#FFFFFF');
                }
            } else {
                $(elem).prev('.project-item--white-text').css('color', '#FFFFFF');
                $(elem).prev('.project-item--dark-text').css('color', '#2c373c');
                $(elem).find('.project-item__description').removeClass('project-item__description--visible');
                $(elem).parent('.our-services').siblings('.projects-list').find('.bg-item--last').css('color', '#FFFFFF');
            }
        });
    }

    $(window).on('scroll load', function () {
        bgScroll();
    });
});
