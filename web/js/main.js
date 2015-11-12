$(function () {




    $(document).on('submit', '#direct-order-form', function(e) {
        e.preventDefault();
        var $directOrderForm = $(this);

        $.ajax({
            url: window.location.href,
            type: 'post',
            data: new FormData( this ),
            processData: false,
            contentType: false,
            success: function(response) {
                $directOrderForm.replaceWith(response.view);
                if (response.result == 'success' && window.ga && window.yaCounter27048220) {
                    ga('send', 'event', 'order', 'contacts');
                    yaCounter27048220.reachGoal('contacts');
                }
            }
        });
    });

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
    $(window).on('resize', function(){
        if(window.matchMedia && window.matchMedia('(min-width: 800px)').matches){
            $('body').removeClass('open-navigation open-main-nav open-languages-nav');
        }
    });

    //Show hire us
    $(document).on('click', '.hire_us', function(){
        $('body').addClass('open-hire_us');
    });



    //Show search form
    $(document).on('click', '.search_button', function(){
        if ($(window).width() < 838) {
            $('.search-form-wrap').animate({display:'block' ,width:'100%','height':'toggle',top:'0'},450,function() {
                //$('#search_searchPhrase').focus();
                setTimeout(function(){
                    $('#search_searchPhrase').focus();
                },1);

            })
            $('.search_button').prop("disabled",true).css('cursor','default');
            var windowWidth = ((window.innerWidth - 915)/2) - 49.5;
            $('.search_button').addClass('search_button-animate').animate({left: windowWidth, top:'77px'},500);
            $('body').addClass('open-search-form');
            setTimeout(function(){
                $('#search-form, #search-results').animate({opacity:'1'},500);
            },450)
        }
        else {
            $('.search-form-wrap').animate({width:'toggle','height':'toggle',top:'0'},450,function() {
                //$('#search_searchPhrase').focus();
                setTimeout(function(){
                    $('#search_searchPhrase').focus();
                },1);
            })
            $('.search_button').prop("disabled",true).css('cursor','default');
            var windowWidth = ((window.innerWidth - 915)/2) - 49.5;
            $('.search_button').addClass('search_button-animate').animate({left: windowWidth, top:'77px'},500);
            setTimeout(function(){
                $('body').addClass('open-search-form');
                $('#search-form, #search-results').animate({opacity:'1'},500);
            },450)
        }

    });

    //Hide search form
    $(document).on('click', '.close-search-form', function(){
        if($(window).width() < 838){
            $('body').removeClass('open-search-form');
            $('.search-form-wrap').animate({width:'100%','height':'toggle',top:'92px'},450);
            $('#search-form,#search-results').animate({opacity:'0'},100);
            $('.search_button').animate({left:'0px', top:'92px'},500, function(){
                $('.search_button').removeClass('search_button-animate');
                $('.search_button').prop("disabled",false).css('cursor','pointer');
            });
        }
        else{
            $('body').removeClass('open-search-form');
            $('.search-form-wrap').animate({width:'toggle','height':'toggle',top:'92px'},450);
            $('#search-form,#search-results').animate({opacity:'0'},100);
            $('.search_button').animate({left:'0px', top:'92px'},500, function(){
                $('.search_button').removeClass('search_button-animate');
                $('.search_button').prop("disabled",false).css('cursor','pointer');
            });
        }
    });

    var yearsCnt = $('.years-count'),
        startPos;
    var yearList = $('.year-slider-wrapp li');

    function updateYear(value) {
        $('#view-year').html(value)
    }

    if($('#year-slider').length){
        $("#year-slider").slider({
            min: 0,
            max: yearList.length - 1,
            value: yearList.length - 1,
            step: 1,
            range: 'min',
            start: function (event, ui) {
                startPos = $(yearList[ui.value]).data('val');
            },
            change: function (event, ui) {
                counter(startPos, $(yearList[ui.value]).data('val'));
                updateYear($(yearList[ui.value]).data('text'));
            }
        });
        $('#year-slider').draggable(); // Enable toush dragging
    }


    function counter(start, end) {
        var i = start;
        var t = setInterval(function () {
            if (i == end) {
                clearInterval(t);
            } else if (i > end) {
                i--
            } else {
                i++
            }
            yearsCnt.html(i);
        }, 20);
    }



    var filter;
    var teamList = $('.avatar'),
        teamCnt = $('.team-list'),
        interestsList = $('.interests a');

    if($('.team-list').length){
        (function() {
            interestsList.click(function(){

                if($(this).hasClass('active')){
                    toDefaultState();
                } else {
                    teamCnt.addClass('active-filter');
                    interestsList.removeClass('active');
                    $(this).addClass('active');
                    filter = $(this).data('filter');

                    if(filter === "drinks") {
                        teamCnt.addClass('show-drinks');
                        showItems();
                    } else {
                        teamCnt.removeClass('show-drinks');
                        teamList.each(function(index, value){
                            var el = $(value);
                            var interests = eval(el.data('interests'));

                            if(el.hasClass('disabled')) {
                                if($.inArray(filter, interests) >= 0) {
                                    el.removeClass('disabled').animate({opacity: '1'}, 0);
                                } else {
                                    el.addClass('disabled').animate({opacity: '0.2'}, 0);
                                }
                            } else {
                                if($.inArray(filter, interests) < 0) {
                                    el.addClass('disabled').animate({opacity: '0.2'}, 0);
                                } else {
                                    el.removeClass('disabled').animate({opacity: '1'}, 0);
                                }
                            }
                        });
                    }
                }
            });

            $(document).click(function(e){
                if($.inArray(e.target, interestsList) < 0) {
                    toDefaultState();
                }
            });

            function toDefaultState(){
                teamCnt.removeClass('show-drinks').removeClass('active-filter');
                showItems();
                interestsList.removeClass('active');
            }

            function showItems() {
                teamList.each(function(index, value){
                    $(value).stop(true, true).animate({opacity: '1'}, 0);
                });
            }
        })();

    };


    var projectSlider = false,
        projectSliderCnt = $('.project-slider');

    if (projectSliderCnt.length) {
        projectSlider = new Sly(projectSliderCnt, {
            horizontal: 1,
            itemNav: 'basic',
            smart: 1,
            mouseDragging: 1,
            touchDragging: 1,
            releaseSwing: 0,
            activateOn: 'mousedown',
            sweengSpeed: 0.8,
            startAt: 0,
            scrollBy: 0,
            speed: 300,
            pagesBar: '.slider-pages',
            pageBuilder: function (index) {
                return '<span></span>';
            },
            activatePageOn: 'click',

            // Buttons
            prev: '.prev-slide',
            next: '.next-slide'
        });


        projectSlider.init();

        $(window).load(function(){
            projectSliderCnt.height(projectSliderCnt.find('li.active img').height()).addClass('loaded');
        });

        if (projectSlider.items.length > 1) {
            $('.project-slider-cnt').removeClass('no-controls');
        }


        projectSlider.on('moveStart', function () {
            projectSliderCnt.addClass('moving');
        });

        projectSlider.on('load', function(){
            disableNavigation();
        });

        projectSlider.on('moveEnd', function () {
            projectSliderCnt.removeClass('moving');
            projectSlider.activate(projectSlider.rel.activePage);
            projectSliderCnt.height(projectSliderCnt.find('li.active img').height());

            // Hide navigation button
            disableNavigation();
        });

        projectSlider.on('change', function(){
            projectSliderCnt.height(projectSliderCnt.find('li.active img').height());
        });

        var $sliderWrapper = $('.project-slider-cnt');
        function disableNavigation(){
            $sliderWrapper.removeClass('hide-next-button hide-prev-button');
            if(projectSlider.rel.activePage == 0){
                $sliderWrapper.addClass('hide-prev-button');
            } else if(projectSlider.rel.activePage == projectSlider.items.length - 1){
                $sliderWrapper.addClass('hide-next-button');
            }
        }

        function resizeSliderItems(cnt) {
            sliderWidth = cnt.width();
            slides.each(function (index, value) {
                $(value).css({width: sliderWidth + 'px'});
                $('.project-slider').css({width: sliderWidth + 'px'});
            });
            projectSlider.reload();
        }

        var forSliderWidth = $('.project-info'),
            slides = projectSliderCnt.find('li');

        // reload project slider when mediaqueries breakpoint
        if (!$('html').hasClass('lt-ie10')) {
            (function(){
                var teamList = $('.team-list');
                enquire.register("screen and (max-width:840px)", {
                    match: function () {
                        var sliderWidth = $('.project-info').width();
                        resizeSliderItems(forSliderWidth);

                        $(window).bind('resize', function () {
                            resizeSliderItems(forSliderWidth);
                        });
                    },
                    unmatch: function () {
                        $(window).unbind('resize');
                        projectSliderCnt[0].style.width = '';
                        slides.each(function (idnex, value) {
                            value.style.width = '';
                        });
                    }
                }).register("screen and (max-width:620px)", {
                    match: function () {
                        $('.work-on-project h2').bind('click', function () {
                            teamList.slideToggle(100);
                        });
                        teamList.hide();
                    },
                    unmatch: function () {
                        $('.work-on-project h2').unbind('click');
                        teamList.show();
                    }
                });
                $(window).on("resize orientationchange", function(){
                    projectSlider.reload();
                });
            })();
        }
    }

    // Set fixed slider buttons
    var $sliderCnt = $('.project-slider-cnt');
    if($sliderCnt.length){
        var $scrollingCnt = ( navigator.userAgent.match(/(iPad|iPhone|iPod|Macintosh|Android)/g) ? $('body') : $('html') ),
            $fixedNav = $('.fixed-nav'),
            middleScreenPoint = $(window).height() / 2 - $sliderCnt.offset().top,
            leftSlideHeight;

        $(window).resize(function(){
            middleScreenPoint = $(window).height() / 2 - $('.project-slider-cnt').offset().top;
        });

        window.addEventListener("scroll", function(event) {
            setButtonsPosition();
        });

        $(document).on('click', '.fix-prev-slide', function(){
            $('.slider-nav .prev-slide').trigger('click');
            $scrollingCnt.animate({scrollTop: 0}, 300);
            //$scrollingCnt.scrollTop(0);
        });

        $(document).on('click', '.fix-next-slide', function(){
            $('.slider-nav .next-slide').trigger('click');
            $scrollingCnt.animate({scrollTop: 0}, 300);
        });

        function setButtonsPosition(){
            leftSlideHeight = $sliderCnt.height() - ($scrollingCnt.scrollTop() - $sliderCnt.offset().top) - middleScreenPoint;

            if($scrollingCnt.scrollTop() >= $sliderCnt.offset().top){
                $sliderCnt.addClass('show-fixed-nav');
            } else {
                $sliderCnt.removeClass('show-fixed-nav');
            }

            if(leftSlideHeight >= 280){
                $fixedNav[0].style.top = $scrollingCnt.scrollTop() + middleScreenPoint + 'px';
            }
        };
        // Add navigation from keyboard
        $(window).on('keyup', function(e){
            if(e.keyCode == 37){
                $('.fix-prev-slide').trigger('click');
            } else if(e.keyCode == 39){
                $('.fix-next-slide').trigger('click');
            }
        });
    }

    //enquire.register("screen and (max-width: 670px)", {
    //    match: function(){
    //        var promoBannerCnt = $('.promo-banner-wrapper');
    //        if(promoBannerCnt.length) {
    //            var bannerImg = promoBannerCnt.find('.img-wrapper');
    //            promoBannerCnt.append(bannerImg);
    //        }
    //    },
    //    unmatch: function(){
    //        var promoBannerCnt = $('.promo-banner-wrapper');
    //        if(promoBannerCnt.length) {
    //            var bannerImg = promoBannerCnt.find('.img-wrapper');
    //            promoBannerCnt.prepend(bannerImg);
    //        }
    //    }
    //});

    $(document).on("change", '.file-input input',  function () {
        var fullPath = $(this).val();
        if ( fullPath == '' || fullPath == '&nbsp;' ) {
            fullPath = '&nbsp;';
            $(this).closest('.file-input').find('.filesize').fadeIn(700);
        } else {
            $(this).closest('.file-input').find('.filesize').fadeOut(700);
        };
        var pathArray = fullPath.split(/[/\\]/);
        $(this).closest('.file-input').find('.filename').html(pathArray[pathArray.length - 1]);
    });

    /*Services page*/
    if ($(".link-more").length) {
        $(".link-more").click(function () {
            $(".detailed-text").toggle();
        });
    }

    if ($("#feedback-form").length) {

        $('a[href="#feedback-form"]').click(function () {
            var el = $(this).attr('href');
            $('html, body').animate({
                scrollTop: $(el).offset().top
            }, 1000);
            return false;
        });
    }

    if ($("#scroll").length) {

        $('a[href="#scroll"]').click(function () {
            var el = $(this).attr('href');
            $('html, body').animate({
                scrollTop: $(el).offset().top + 34
            }, 1000);
            return false;
        });
    }

    if ($("#web-dev").length) {
        $('a[href="#web-dev"]').click(function () {
            $(".detailed-text").show();
            var el = $(this).attr('href');
            $('html, body').animate({
                scrollTop: $(el).offset().top
            }, 1000);
            return false;
        });
    }

    if ($("#feedback-line").length) {
        $('a[href="#feedback-line"]').click(function () {
            var el = $(this).attr('href');
            $('html, body').animate({
                scrollTop: $(el).offset().top
            }, 1000);
            return false;
        });
    }

    if($(".project-cell").length) {
        var a = $(".project-cell:eq(2)").height();
        $(".container_load").height(a);
        $(window).resize(function () {
            a = $(".project-cell:eq(2)").height();
            $(".container_load").height(a);
        });
    }

    /*
     * form animation
     */
    var formDelay = 3000;

    /*
     * form validation
     */
    if($('#feedback-form').length) {
        $('#feedback-form').validate({
            rules: {
                'name': {
                    required: true,
                    minlength: 3
                },
                'email': {
                    required: true,
                    minlength: 3
                },
                'message': {
                    required: true,
                    minlength: 30
                }
            },
            messages: {
                'name': {
                    required: "Пожалуйста, введите ваше имя",
                    minlength: jQuery.validator.format("Введите имя не меньше 3 символов")
                },
                'email': {
                    required: "Пожалуйста, введите адрес вашей эл.почты",
                    email: "Ваша эл.адрес должен быть формата name@domain.com"
                },
                'message': {
                    required: "Пожалуйста, введите сообщение",
                    minlength: jQuery.validator.format("Введите сообщение не меньше 30 символов")
                }
            },
            errorPlacement: function (label, element) {
                label.addClass('error-pad');
                label.insertAfter(element);
            },
            wrapper: 'div',
            debug: true,
            submitHandler: function () {
                $('#feedback-form').find('.form-pad').animate({opacity: 0}, 300).delay(formDelay).animate({opacity: 1}, 300);
                $('#feedback-form').find('.form-success').fadeIn(300).delay(formDelay).fadeOut(300);
                return false;
            }
        });

        $.validator.addClassRules({
            name: {
                required: true,
                minlength: 3
            },
            email: {
                required: true,
                minlength: 3
            },
            messageText: {
                required: true,
                minlength: 30
            }
        });
    }


    $(function () {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 200) {
                $('.scroll-to-top').fadeIn();
            } else {
                $('.scroll-to-top').fadeOut();
            }

            if ($(window).width()<=320){
                $('.scroll-to-top').hide();
                return false;
            }
        });
        $(".scroll-to-top").click(function () {
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
    });

    function fixedButtonUp() {
        if($('.scroll-to-top').offset().top + $('.scroll-to-top').height()
            >= $('.footer').offset().top - 69)
            $('.scroll-to-top').addClass('fixed-scroll-top');
        if($(document).scrollTop() + window.innerHeight < $('.footer').offset().top)
            $('.scroll-to-top').removeClass('fixed-scroll-top');
    }

    function fixedHireUs() {
        if($('#hire_us-js').offset().top + $('.hire_us').height()
            >= $('.footer').offset().top - 186)
            $('#hire_us-js').addClass('fixed-hire-us');
        if($(document).scrollTop() + window.innerHeight < $('.footer').offset().top)
            $('#hire_us-js').removeClass('fixed-hire-us');
    }

    $(document).scroll(function() {
        fixedButtonUp();
        fixedHireUs();
    });

    function placeholderReplace() {
        if ($(window).width() < 540) {
            $("#subscribe_email").attr("placeholder", subscribeShortText);
        } else {
            $("#subscribe_email").attr("placeholder", subscribeFullText);
        }
    }
    placeholderReplace();
    $( window ).resize(function() {
        placeholderReplace();
    });

    autosize(document.querySelectorAll('#order_promotion_message'));
});




