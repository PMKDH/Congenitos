/**
 * Theme functions file.
 *
 * Contains handlers for navigation and widget area.
 */

(function ($) {

    "use strict";

    var body, masthead, menuToggle, siteNavigation, socialNavigation, siteHeaderMenu, resizeTimer;

    function initMainNavigation(container) {
        // Add dropdown toggle that displays child menu items.
        var dropdownToggle = $('<button />', {
            'class': 'dropdown-toggle',
            'aria-expanded': false
        }).append($('<span />', {
            'class': 'screen-reader-text',
            text: screenReaderText.expand
        }));

        container.find('.menu-item-has-children > a').after(dropdownToggle);

        // Toggle buttons and submenu items with active children menu items.
        container.find('.current-menu-ancestor > button').addClass('toggled-on');
        container.find('.current-menu-ancestor > .sub-menu').addClass('toggled-on');

        // Add menu items with submenus to aria-haspopup="true".
        container.find('.menu-item-has-children').attr('aria-haspopup', 'true');

        container.find('.dropdown-toggle').click(function (e) {
            var _this = $(this),
                screenReaderSpan = _this.find('.screen-reader-text');

            e.preventDefault();
            _this.toggleClass('toggled-on');
            _this.next('.children, .sub-menu').toggleClass('toggled-on');

            // jscs:disable
            _this.attr('aria-expanded', _this.attr('aria-expanded') === 'false' ? 'true' : 'false');
            // jscs:enable
            screenReaderSpan.text(screenReaderSpan.text() === screenReaderText.expand ? screenReaderText.collapse : screenReaderText.expand);
        });
    }

    initMainNavigation($('.main-navigation'));

    masthead = $('#masthead');
    menuToggle = masthead.find('#menu-toggle');

    // Enable menuToggle.
    (function () {

        // Return early if menuToggle is missing.
        if (!menuToggle.length) {
            return;
        }

        // Add an initial values for the attribute.
        menuToggle.add(siteNavigation).add(socialNavigation).attr('aria-expanded', 'false');

        menuToggle.on('click', function () {
            $(this).add(siteHeaderMenu).toggleClass('toggled-on');

            // jscs:disable
            $(this).add(siteNavigation).add(socialNavigation).attr('aria-expanded', $(this).add(siteNavigation).add(socialNavigation).attr('aria-expanded') === 'false' ? 'true' : 'false');
            // jscs:enable
        });
    })();

    function subMenuPosition() {
        $('.sub-menu').each(function () {
            $(this).removeClass('toleft');
            if (($(this).parent().offset().left + $(this).parent().width() - $(window).width() + 178) > 0) {
                $(this).addClass('toleft');
            }
        });
    }

    function topMenuPosition() {
        $('.top-navigation-right').each(function () {
            if ($('.main-navigation').size() > 0) {
                $('.site-header-main').removeClass('top-line');
                if (( ($(this).position().top) - $('.main-navigation').position().top) > 10) {
                    $('.site-header-main').addClass('top-line');
                }
            }
        });
    }


    topMenuPosition();
    subMenuPosition();
    /*
     *Search modal
     */
    $('body').on("click", ".search-icon", function (e) {
        e.preventDefault();
        $(".search-modal").slideDown(300);
    });
    $('body').on("click", ".close-search-modal", function (e) {
        e.preventDefault();
        $(".search-modal").slideUp('fast');
    });
    $(window).resize(function () {
        subMenuPosition();
        topMenuPosition();
    });
    // Enable menuToggle.
    $(window).load(function () {
        topMenuPosition();
        var slideshow = (mphbSlider.slideshow === '1') ? true : false;
        $('.page-template-template-front-page .widget_mphb_rooms_widget').each(function () {
            $(this).flexslider({
                animation: mphbSlider.animation,
                directionNav: false,
                keyboard: false,
                slideshow: slideshow,
                slideshowSpeed: parseInt(mphbSlider.autoplayTimeout),
                selector: '.mphb_widget_rooms-wrapper > .mphb-room-type',
                start: function (slider) {
                    $(slider).find('.flex-control-paging a').each(function () {
                        var num = parseInt($(this).text());
                        if (num < 10) {
                            $(this).text('0' + num);
                        }
                    });
                    $(slider).addClass('loaded');
                }
            });
        });
    });


})
(jQuery);
