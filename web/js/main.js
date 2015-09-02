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

    var yearsCnt = $('.years-count'),
        startPos;
    var yearList = $('.year-slider-wrapp li');

    function updateYear(value) {
        $('#view-year').html(value)
    }

    if($('#yeat-slider').length){
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

    if ($("#scroll").length) {
        $('a[href="#scroll"]').click(function () {
            var el = $(this).attr('href');
            $('body').animate({
                scrollTop: $(el).offset().top + 34
            }, 1000);
            return false;
        });
    }

    if ($("#web-dev").length) {
        $('a[href="#web-dev"]').click(function () {
            $(".detailed-text").show();
            var el = $(this).attr('href');
            $('body').animate({
                scrollTop: $(el).offset().top
            }, 1000);
            return false;
        });
    }

    if ($("#scroll_form").length) {
        $('a[href="#scroll_form"]').click(function () {
            var el = $(this).attr('href');
            $('body').animate({
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


});
