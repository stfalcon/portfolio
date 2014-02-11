$(function () {
    var nav = $('header nav');

    // show mobile navigation
    $('.show-mobile-nav').on('click', function () {
        nav.fadeIn(200);
    });

    $('.close-mobile-nav').on('click', function () {
        nav.fadeOut(200);
    });

    if (!$('html').hasClass('lt-ie10')) {
        enquire.register("screen and (min-width:620px)", {
            match: function () {
                nav.show();
            },
            unmatch: function () {
                nav.hide();
            }
        })
    }

    var yearsCnt = $('.years-count'),
        startPos;
    var yearList = $('.year-slider-wrapp li');

    function updateYear(value) {
        $('#view-year').html(value)
    }

    $("#year-slider").slider({
        min: 0,
        max: yearList.length - 1,
        value: 0,
        step: 1,
        range: 'min',
        start: function (event, ui) {
            startPos = $(yearList[ui.value]).data('val');
        },
        change: function (event, ui) {
            counter(startPos, $(yearList[ui.value]).data('val'));
            updateYear($(yearList[ui.value]).html());
        }
    });
    $('#year-slider').draggable(); // Enable toush dragging

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
        drinks = $('.drinks');

    $('.interests a').hover(function () {
        filter = $(this).data('filter');
        if (filter === "drinks") {
            $(teamList).stop(true, true).animate({opacity: '0.2'}, 400);
            drinks.fadeIn(400);
        }
        teamList.each(function (index, value) {
            var interests = eval($(value).data('interests'));
            if ($.inArray(filter, interests) < 0) {
                $(value).stop(true, true).animate({opacity: '0.2'}, 200);
            }
        });

    }, function () {
        teamList.stop(true, true).animate({opacity: '1'}, 200);
        if (filter === "drinks") {
            drinks.stop(true, true).fadeOut(200);
        }
    });
    if (!$('html').hasClass('lt-ie10')) {
        enquire.register("screen and (min-width:620px)", {
            match: function () {
                nav.show();
            },
            unmatch: function () {
                nav.hide();
            }
        })
    }
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
        }).init();

        projectSlider.on('moveStart', function () {
            projectSliderCnt.addClass('moving');
        });

        projectSlider.on('moveEnd', function () {
            projectSliderCnt.removeClass('moving');
            projectSlider.activate(projectSlider.rel.activePage);
        });

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
            var teamList = $('.team-list');
            enquire.register("screen and (max-width:1222px)", {
                match: function () {
                    projectSlider.reload();
                },
                unmatch: function () {
                    projectSlider.reload();
                }
            }).register("screen and (max-width:1184px) and (min-width:1024px)", {
                    match: function () {
                        projectSlider.reload();
                    },
                    unmatch: function () {
                        projectSlider.reload();
                    }
                }).register("screen and (max-width:840px)", {
                    match: function () {
                        var sliderWidth = $('.project-info').width();
                        resizeSliderItems(forSliderWidth);
                        projectSlider.reload();

                        $(window).bind('resize', function () {
                            resizeSliderItems(forSliderWidth);
                        });
                    },
                    unmatch: function () {
                        $(window).unbind('resize');
                        projectSlider.reload();
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
                        projectSlider.reload();
                    }
                });
        }
    }
    var images = $('.services-tabs .img'),
        accordionTabs = $('.accordion-wrapper'),
        isVisible = true,
        activeTab = 0;

    if (!$('html').hasClass('lt-ie10')) {
        enquire.register("screen and (min-width:670px)", {
            match: function () {
                accordionTabs.show();
                $(".services-tabs").tabs({
                    active: 0,
                    create: function (event, ui) {
                        if (window.innerWidth > 1005) {
                            console.log('init ', window.innerWidth)
                            $(images[0]).fadeIn(200);
                        }
                    },
                    activate: function (event, ui) {
                        console.log(ui);
                        images.fadeOut(100);
                        if (isVisible) {
                            switch (ui.newPanel.selector) {
                                case "#development":
                                    $(images[0]).fadeIn(200);
                                    break;
                                case "#design":
                                    $(images[1]).fadeIn(200);
                                    break;
                                case "#mobile":
                                    $(images[2]).fadeIn(200);
                                    break;
                                case "#games":
                                    $(images[3]).fadeIn(200);
                                    break;
                            }
                        }
                    }
                });
            },
            unmatch: function () {
                $(".services-tabs").tabs("destroy");
                closeAccordion(0)
            }
        }).register("screen and (max-width:1005px)", {
                match: function () {
                    isVisible = false;
                    images.hide();
                },
                unmatch: function () {
                    isVisible = true;
                    activeTab = $(".services-tabs").tabs("option", "active");
                    $(images[activeTab]).show();
                }
            });
    }
    ;

    $('.tab-title').on('click', function (event) {
        var tab = $(this).parent();
        if ($(tab).hasClass('open')) {
            closeAccordion(200)
        } else {
            closeAccordion(200)
            tab.find('.accordion-wrapper').slideDown(200);
            tab.addClass('open');
        }
        console.log('click');
    });

    function closeAccordion(speed) {
        accordionTabs.slideUp(speed);
        accordionTabs.each(function (index, value) {
            $(value).parent().removeClass('open');
        });
    }
});
