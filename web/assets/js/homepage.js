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
    $(document).on('submit', '.subscribe-form', function(e) {
        e.preventDefault();
        var $form = $(this);

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            cache: false,
            async: false,
            success: function(response) {
                if (!response.success) {
                    $form.closest('.subscribe-form-wrap').replaceWith(response.view);
                } else {
                    $form.find('input[type="email"]').val('');
                    $form.find('.error-list').remove();
                }
            }
        })
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
    console.log(window.matchMedia("(max-width: 500px)").matches ? 'forceCentered' : 'centered')

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

    initMobileSlider();

    // Hide mobile navigation
    $(document).on('click', '.close-mobile-nav', function(){
        $('body').removeClass('open-navigation open-main-nav open-languages-nav');
    });
    $(window).bind('resize', function () {
        initMobileSlider();
        if(window.matchMedia && window.matchMedia('(min-width: 800px)').matches){
            $('body').removeClass('open-navigation open-main-nav open-languages-nav');
        }
        console.log('resize');
    });
});


var $projectList = $('.projects-list'),
    heightHuck = 0;

function changeServiceCategory(category){
    var loadedCount = 0;
    var $projectRow = $('<div class="projects-row"/>');

    // Build projects items
    serviceData[category].projects.forEach(function(item, index) {
        var $projectCell = index === 0 ? $('<div class="project-cell project-cell-l"/>') : $('<div class="project-cell"/>');
        var $projectImg = $('<img alt="' + item.name +'"/>').on('load', function(){
            loadedCount++;
            heightHuck = $projectList.height();
            $projectList.css({
                height: 'auto'
            });
        })
            .attr('src', item.projectPreviewURL);
        var $linkToProject = $('<a href="'+item.URL+'"/>')
        var $projectInfo = $('<span class="project-info">' +
        '<span class="project-info-text">' +
        '<span class="project-name">' + item.name + '</span>' +
        '<span class="project-description">' +
        item.description +
        '</span>' +
        '</span>' +
        '<span class="helper"></span>' +
        '</span>' +
        '</span>');

        $linkToProject
            .append($projectImg)
            .append($projectInfo);
        $projectCell.append($linkToProject);
        $projectRow.append($projectCell);
    });

    $projectList.find('.projects-row').remove();
    $projectList.height(heightHuck);
    $projectList.append($projectRow);
};

function showServiceTabs(category){
    changeServiceCategory(category);
};

var servicesTabs = $('.projects-tabs a');

servicesTabs.on('click', function(e){
    var category = $(this).data('category');
    if(category) {
        showServiceTabs(category);
        servicesTabs.removeClass('active');
        $(this).addClass('active');
//                e.preventDefault();
    }
//           return false;
});

function activeTabByHash(hash) {
    $('.projects-tabs a').removeClass('active');

    var $link = $('.projects-tabs').find('a[data-category="'+hash+'"]');
    $link.addClass('active');
    showServiceTabs(hash);
}

function changeTabByHash(hash) {
    if (hash.length>0) {
        activeTabByHash(hash);
    } else {
        //go to default category
        activeTabByHash('development');
    }
}

$(document).ready(function() {
    var hash = window.location.hash;
    hash = hash.substr(2);
    changeTabByHash(hash);
});


$.History.bind(function(state) {
    var hash = state.substr(1);
    changeTabByHash(hash);
});
