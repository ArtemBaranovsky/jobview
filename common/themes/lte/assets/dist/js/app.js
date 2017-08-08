/*! AdminLTE app.js
 * ================
 * Main JS application file for AdminLTE v2. This file
 * should be included in all pages. It controls some layout
 * options and implements exclusive AdminLTE plugins.
 *
 * @Author  Almsaeed Studio
 * @Support <http://www.almsaeedstudio.com>
 * @Email   <support@almsaeedstudio.com>
 * @version 2.3.0
 * @license MIT <http://opensource.org/licenses/MIT>
 */

//Make sure jQuery has been loaded before app.js
if (typeof jQuery === "undefined") {
    throw new Error("AdminLTE requires jQuery");
}

/* AdminLTE
 *
 * @type Object
 * @description $.AdminLTE is the main object for the template's app.
 *              It's used for implementing functions and options related
 *              to the template. Keeping everything wrapped in an object
 *              prevents conflict with other plugins and is a better
 *              way to organize our code.
 */
$.AdminLTE = {};

/* --------------------
 * - AdminLTE Options -
 * --------------------
 * Modify these options to suit your implementation
 */
$.AdminLTE.options = {
    //Add slimscroll to navbar menus
    //This requires you to load the slimscroll plugin
    //in every page before app.js
    navbarMenuSlimscroll: true,
    navbarMenuSlimscrollWidth: "3px", //The width of the scroll bar
    navbarMenuHeight: "200px", //The height of the inner menu
    //General animation speed for JS animated elements such as box collapse/expand and
    //sidebar treeview slide up/down. This options accepts an integer as milliseconds,
    //'fast', 'normal', or 'slow'
    animationSpeed: 500,
    //Sidebar push menu toggle button selector
    sidebarToggleSelector: "[data-toggle='offcanvas']",
    //Activate sidebar push menu
    sidebarPushMenu: true,
    //Activate sidebar slimscroll if the fixed layout is set (requires SlimScroll Plugin)
    sidebarSlimScroll: true,
    //Enable sidebar expand on hover effect for sidebar mini
    //This option is forced to true if both the fixed layout and sidebar mini
    //are used together
    sidebarExpandOnHover: false,
    //BoxRefresh Plugin
    enableBoxRefresh: true,
    //Bootstrap.js tooltip
    enableBSToppltip: true,
    BSTooltipSelector: "[data-toggle='tooltip']",
    //Enable Fast Click. Fastclick.js creates a more
    //native touch experience with touch devices. If you
    //choose to enable the plugin, make sure you load the script
    //before AdminLTE's app.js
    enableFastclick: true,
    //Control Sidebar Options
    enableControlSidebar: true,
    controlSidebarOptions: {
        //Which button should trigger the open/close event
        toggleBtnSelector: "[data-toggle='control-sidebar']",
        //The sidebar selector
        selector: ".control-sidebar",
        //Enable slide over content
        slide: true
    },
    //Box Widget Plugin. Enable this plugin
    //to allow boxes to be collapsed and/or removed
    enableBoxWidget: true,
    //Box Widget plugin options
    boxWidgetOptions: {
        boxWidgetIcons: {
            //Collapse icon
            collapse: 'fa-minus',
            //Open icon
            open: 'fa-plus',
            //Remove icon
            remove: 'fa-times'
        },
        boxWidgetSelectors: {
            //Remove button selector
            remove: '[data-widget="remove"]',
            //Collapse button selector
            collapse: '[data-widget="collapse"]'
        }
    },
    //Direct Chat plugin options
    directChat: {
        //Enable direct chat by default
        enable: true,
        //The button to open and close the chat contacts pane
        contactToggleSelector: '[data-widget="chat-pane-toggle"]'
    },
    //Define the set of colors to use globally around the website
    colors: {
        lightBlue: "#3c8dbc",
        red: "#f56954",
        green: "#00a65a",
        aqua: "#00c0ef",
        yellow: "#f39c12",
        blue: "#0073b7",
        navy: "#001F3F",
        teal: "#39CCCC",
        olive: "#3D9970",
        lime: "#01FF70",
        orange: "#FF851B",
        fuchsia: "#F012BE",
        purple: "#8E24AA",
        maroon: "#D81B60",
        black: "#222222",
        gray: "#d2d6de"
    },
    //The standard screen sizes that bootstrap uses.
    //If you change these in the variables.less file, change
    //them here too.
    screenSizes: {
        xs: 480,
        sm: 768,
        md: 992,
        lg: 1200
    }
};

/* ------------------
 * - Implementation -
 * ------------------
 * The next block of code implements AdminLTE's
 * functions and plugins as specified by the
 * options above.
 */
$(function () {
    "use strict";

    //Fix for IE page transitions
    $("body").removeClass("hold-transition");

    //Extend options if external options exist
    if (typeof AdminLTEOptions !== "undefined") {
        $.extend(true,
            $.AdminLTE.options,
            AdminLTEOptions);
    }

    //Easy access to options
    var o = $.AdminLTE.options;

    //Set up the object
    _init();

    //Activate the layout maker
    $.AdminLTE.layout.activate();

    //Enable sidebar tree view controls
    $.AdminLTE.tree('.sidebar');

    //Enable control sidebar
    if (o.enableControlSidebar) {
        $.AdminLTE.controlSidebar.activate();
    }

    //Add slimscroll to navbar dropdown
    if (o.navbarMenuSlimscroll && typeof $.fn.slimscroll != 'undefined') {
        $(".navbar .menu").slimscroll({
            height: o.navbarMenuHeight,
            alwaysVisible: false,
            size: o.navbarMenuSlimscrollWidth
        }).css("width", "100%");
    }

    //Activate sidebar push menu
    if (o.sidebarPushMenu) {
        $.AdminLTE.pushMenu.activate(o.sidebarToggleSelector);
    }

    //Activate Bootstrap tooltip
    if (o.enableBSToppltip) {
        $('.hint-block[data-toggle=tooltip]').tooltip({
            delay: 200,
            trigger: "click"
        });
        $(':not(.hint-block)[data-toggle=tooltip]').tooltip({
            delay: 200
        });
    }

    //Activate box widget
    if (o.enableBoxWidget) {
        $.AdminLTE.boxWidget.activate();
    }

    //Activate fast click
    if (o.enableFastclick && typeof FastClick != 'undefined') {
        FastClick.attach(document.body);
    }

    //Activate direct chat widget
    if (o.directChat.enable) {
        $(document).on('click', o.directChat.contactToggleSelector, function () {
            var box = $(this).parents('.direct-chat').first();
            box.toggleClass('direct-chat-contacts-open');
        });
    }

    /*
     * INITIALIZE BUTTON TOGGLE
     * ------------------------
     */
    $('.btn-group[data-toggle="btn-toggle"]').each(function () {
        var group = $(this);
        $(this).find(".btn").on('click', function (e) {
            group.find(".btn.active").removeClass("active");
            $(this).addClass("active");
            e.preventDefault();
        });

    });
});

/* ----------------------------------
 * - Initialize the AdminLTE Object -
 * ----------------------------------
 * All AdminLTE functions are implemented below.
 */
function _init() {
    'use strict';
    /* Layout
     * ======
     * Fixes the layout height in case min-height fails.
     *
     * @type Object
     * @usage $.AdminLTE.layout.activate()
     *        $.AdminLTE.layout.fix()
     *        $.AdminLTE.layout.fixSidebar()
     */
    $.AdminLTE.layout = {
        activate: function () {
            var _this = this;
            _this.fix();
            _this.fixSidebar();
            $(window, ".wrapper").resize(function () {
                _this.fix();
                _this.fixSidebar();
            });
        },
        fix: function () {
            //Get window height and the wrapper height
            var neg = $('.main-header').outerHeight() + $('.main-footer').outerHeight();
            var window_height = $(window).height();
            var sidebar_height = $(".sidebar").height();
            //Set the min-height of the content and sidebar based on the
            //the height of the document.
            if ($("body").hasClass("fixed")) {
                $(".content-wrapper, .right-side").css('min-height', window_height - $('.main-footer').outerHeight());
            } else {
                var postSetWidth;
                if (window_height >= sidebar_height) {
                    $(".content-wrapper, .right-side").css('min-height', window_height - neg);
                    postSetWidth = window_height - neg;
                } else {
                    $(".content-wrapper, .right-side").css('min-height', sidebar_height);
                    postSetWidth = sidebar_height;
                }

                //Fix for the control sidebar height
                var controlSidebar = $($.AdminLTE.options.controlSidebarOptions.selector);
                if (typeof controlSidebar !== "undefined") {
                    if (controlSidebar.height() > postSetWidth)
                        $(".content-wrapper, .right-side").css('min-height', controlSidebar.height());
                }

            }
        },
        fixSidebar: function () {
            //Make sure the body tag has the .fixed class
            if (!$("body").hasClass("fixed")) {
                if (typeof $.fn.slimScroll != 'undefined') {
                    $(".sidebar").slimScroll({destroy: true}).height("auto");
                }
                return;
            } else if (typeof $.fn.slimScroll == 'undefined' && window.console) {
                window.console.error("Error: the fixed layout requires the slimscroll plugin!");
            }
            //Enable slimscroll for fixed layout
            if ($.AdminLTE.options.sidebarSlimScroll) {
                if (typeof $.fn.slimScroll != 'undefined') {
                    //Destroy if it exists
                    $(".sidebar").slimScroll({destroy: true}).height("auto");
                    //Add slimscroll
                    $(".sidebar").slimscroll({
                        height: ($(window).height() - $(".main-header").height()) + "px",
                        color: "rgba(0,0,0,0.2)",
                        size: "3px"
                    });
                }
            }
        }
    };

    /* PushMenu()
     * ==========
     * Adds the push menu functionality to the sidebar.
     *
     * @type Function
     * @usage: $.AdminLTE.pushMenu("[data-toggle='offcanvas']")
     */
    $.AdminLTE.pushMenu = {
        activate: function (toggleBtn) {
            //Get the screen sizes
            var screenSizes = $.AdminLTE.options.screenSizes;

            //Enable sidebar toggle
            $(toggleBtn).on('click', function (e) {
                e.preventDefault();

                //Enable sidebar push menu
                if ($(window).width() > (screenSizes.sm - 1)) {
                    if ($("body").hasClass('sidebar-collapse')) {
                        $("body").removeClass('sidebar-collapse').trigger('expanded.pushMenu');
                    } else {
                        $("body").addClass('sidebar-collapse').trigger('collapsed.pushMenu');
                    }
                }
                //Handle sidebar push menu for small screens
                else {
                    if ($("body").hasClass('sidebar-open')) {
                        $("body").removeClass('sidebar-open').removeClass('sidebar-collapse').trigger('collapsed.pushMenu');
                    } else {
                        $("body").addClass('sidebar-open').trigger('expanded.pushMenu');
                    }
                }
            });

            $(".content-wrapper").click(function () {
                //Enable hide menu when clicking on the content-wrapper on small screens
                if ($(window).width() <= (screenSizes.sm - 1) && $("body").hasClass("sidebar-open")) {
                    $("body").removeClass('sidebar-open');
                }
            });

            //Enable expand on hover for sidebar mini
            if ($.AdminLTE.options.sidebarExpandOnHover
                || ($('body').hasClass('fixed')
                && $('body').hasClass('sidebar-mini'))) {
                this.expandOnHover();
            }
        },
        expandOnHover: function () {
            var _this = this;
            var screenWidth = $.AdminLTE.options.screenSizes.sm - 1;
            //Expand sidebar on hover
            $('.main-sidebar').hover(function () {
                if ($('body').hasClass('sidebar-mini')
                    && $("body").hasClass('sidebar-collapse')
                    && $(window).width() > screenWidth) {
                    _this.expand();
                }
            }, function () {
                if ($('body').hasClass('sidebar-mini')
                    && $('body').hasClass('sidebar-expanded-on-hover')
                    && $(window).width() > screenWidth) {
                    _this.collapse();
                }
            });
        },
        expand: function () {
            $("body").removeClass('sidebar-collapse').addClass('sidebar-expanded-on-hover');
        },
        collapse: function () {
            if ($('body').hasClass('sidebar-expanded-on-hover')) {
                $('body').removeClass('sidebar-expanded-on-hover').addClass('sidebar-collapse');
            }
        }
    };

    /* Tree()
     * ======
     * Converts the sidebar into a multilevel
     * tree view menu.
     *
     * @type Function
     * @Usage: $.AdminLTE.tree('.sidebar')
     */
    $.AdminLTE.tree = function (menu) {
        var _this = this;
        var animationSpeed = $.AdminLTE.options.animationSpeed;
        $(document).on('click', menu + ' li a', function (e) {
            //Get the clicked link and the next element
            var $this = $(this);
            var checkElement = $this.next();

            //Check if the next element is a menu and is visible
            if ((checkElement.is('.treeview-menu')) && (checkElement.is(':visible'))) {
                //Close the menu
                checkElement.slideUp(animationSpeed, function () {
                    checkElement.removeClass('menu-open');
                    //Fix the layout in case the sidebar stretches over the height of the window
                    //_this.layout.fix();
                });
                checkElement.parent("li").removeClass("active");
            }
            //If the menu is not visible
            else if ((checkElement.is('.treeview-menu')) && (!checkElement.is(':visible'))) {
                //Get the parent menu
                var parent = $this.parents('ul').first();
                //Close all open menus within the parent
                var ul = parent.find('ul:visible').slideUp(animationSpeed);
                //Remove the menu-open class from the parent
                ul.removeClass('menu-open');
                //Get the parent li
                var parent_li = $this.parent("li");

                //Open the target menu and add the menu-open class
                checkElement.slideDown(animationSpeed, function () {
                    //Add the class active to the parent li
                    checkElement.addClass('menu-open');
                    parent.find('li.active').removeClass('active');
                    parent_li.addClass('active');
                    //Fix the layout in case the sidebar stretches over the height of the window
                    _this.layout.fix();
                });
            }
            //if this isn't a link, prevent the page from being redirected
            if (checkElement.is('.treeview-menu')) {
                e.preventDefault();
            }
        });
    };

    /* ControlSidebar
     * ==============
     * Adds functionality to the right sidebar
     *
     * @type Object
     * @usage $.AdminLTE.controlSidebar.activate(options)
     */
    $.AdminLTE.controlSidebar = {
        //instantiate the object
        activate: function () {
            //Get the object
            var _this = this;
            //Update options
            var o = $.AdminLTE.options.controlSidebarOptions;
            //Get the sidebar
            var sidebar = $(o.selector);
            //The toggle button
            var btn = $(o.toggleBtnSelector);

            //Listen to the click event
            btn.on('click', function (e) {
                e.preventDefault();
                //If the sidebar is not open
                if (!sidebar.hasClass('control-sidebar-open')
                    && !$('body').hasClass('control-sidebar-open')) {
                    //Open the sidebar
                    _this.open(sidebar, o.slide);
                } else {
                    _this.close(sidebar, o.slide);
                }
            });

            //If the body has a boxed layout, fix the sidebar bg position
            var bg = $(".control-sidebar-bg");
            _this._fix(bg);

            //If the body has a fixed layout, make the control sidebar fixed
            if ($('body').hasClass('fixed')) {
                _this._fixForFixed(sidebar);
            } else {
                //If the content height is less than the sidebar's height, force max height
                if ($('.content-wrapper, .right-side').height() < sidebar.height()) {
                    _this._fixForContent(sidebar);
                }
            }
        },
        //Open the control sidebar
        open: function (sidebar, slide) {
            //Slide over content
            if (slide) {
                sidebar.addClass('control-sidebar-open');
            } else {
                //Push the content by adding the open class to the body instead
                //of the sidebar itself
                $('body').addClass('control-sidebar-open');
            }
        },
        //Close the control sidebar
        close: function (sidebar, slide) {
            if (slide) {
                sidebar.removeClass('control-sidebar-open');
            } else {
                $('body').removeClass('control-sidebar-open');
            }
        },
        _fix: function (sidebar) {
            var _this = this;
            if ($("body").hasClass('layout-boxed')) {
                sidebar.css('position', 'absolute');
                sidebar.height($(".wrapper").height());
                $(window).resize(function () {
                    _this._fix(sidebar);
                });
            } else {
                sidebar.css({
                    'position': 'fixed',
                    'height': 'auto'
                });
            }
        },
        _fixForFixed: function (sidebar) {
            sidebar.css({
                'position': 'fixed',
                'max-height': '100%',
                'overflow': 'auto',
                'padding-bottom': '50px'
            });
        },
        _fixForContent: function (sidebar) {
            $(".content-wrapper, .right-side").css('min-height', sidebar.height());
        }
    };

    /* BoxWidget
     * =========
     * BoxWidget is a plugin to handle collapsing and
     * removing boxes from the screen.
     *
     * @type Object
     * @usage $.AdminLTE.boxWidget.activate()
     *        Set all your options in the main $.AdminLTE.options object
     */
    $.AdminLTE.boxWidget = {
        selectors: $.AdminLTE.options.boxWidgetOptions.boxWidgetSelectors,
        icons: $.AdminLTE.options.boxWidgetOptions.boxWidgetIcons,
        animationSpeed: $.AdminLTE.options.animationSpeed,
        activate: function (_box) {
            var _this = this;
            if (!_box) {
                _box = document; // activate all boxes per default
            }
            //Listen for collapse event triggers
            $(_box).on('click', _this.selectors.collapse, function (e) {
                e.preventDefault();
                _this.collapse($(this));
            });

            //Listen for remove event triggers
            $(_box).on('click', _this.selectors.remove, function (e) {
                e.preventDefault();
                _this.remove($(this));
            });
        },
        collapse: function (element) {
            var _this = this;
            //Find the box parent
            var box = element.parents(".box").first();
            //Find the body and the footer
            var box_content = box.find("> .box-body, > .box-footer, > form  >.box-body, > form > .box-footer");
            if (!box.hasClass("collapsed-box")) {
                //Convert minus into plus
                element.children(":first")
                    .removeClass(_this.icons.collapse)
                    .addClass(_this.icons.open);
                //Hide the content
                box_content.slideUp(_this.animationSpeed, function () {
                    box.addClass("collapsed-box");
                });
            } else {
                //Convert plus into minus
                element.children(":first")
                    .removeClass(_this.icons.open)
                    .addClass(_this.icons.collapse);
                //Show the content
                box_content.slideDown(_this.animationSpeed, function () {
                    box.removeClass("collapsed-box");
                });
            }
        },
        remove: function (element) {
            //Find the box parent
            var box = element.parents(".box").first();
            box.slideUp(this.animationSpeed);
        }
    };
}

/* ------------------
 * - Custom Plugins -
 * ------------------
 * All custom plugins are defined below.
 */

/*
 * BOX REFRESH BUTTON
 * ------------------
 * This is a custom plugin to use with the component BOX. It allows you to add
 * a refresh button to the box. It converts the box's state to a loading state.
 *
 * @type plugin
 * @usage $("#box-widget").boxRefresh( options );
 */
(function ($) {

    "use strict";

    $.fn.boxRefresh = function (options) {

        // Render options
        var settings = $.extend({
            //Refresh button selector
            trigger: ".refresh-btn",
            //File source to be loaded (e.g: ajax/src.php)
            source: "",
            //Callbacks
            onLoadStart: function (box) {
                return box;
            }, //Right after the button has been clicked
            onLoadDone: function (box) {
                return box;
            } //When the source has been loaded

        }, options);

        //The overlay
        var overlay = $('<div class="overlay"><div class="fa fa-refresh fa-spin"></div></div>');

        return this.each(function () {
            //if a source is specified
            if (settings.source === "") {
                if (window.console) {
                    window.console.log("Please specify a source first - boxRefresh()");
                }
                return;
            }
            //the box
            var box = $(this);
            //the button
            var rBtn = box.find(settings.trigger).first();

            //On trigger click
            rBtn.on('click', function (e) {
                e.preventDefault();
                //Add loading overlay
                start(box);

                //Perform ajax call
                box.find(".box-body").load(settings.source, function () {
                    done(box);
                });
            });
        });

        function start(box) {
            //Add overlay and loading img
            box.append(overlay);

            settings.onLoadStart.call(box);
        }

        function done(box) {
            //Remove overlay and loading img
            box.find(overlay).remove();

            settings.onLoadDone.call(box);
        }

    };

})(jQuery);

/*
 * EXPLICIT BOX ACTIVATION
 * -----------------------
 * This is a custom plugin to use with the component BOX. It allows you to activate
 * a box inserted in the DOM after the app.js was loaded.
 *
 * @type plugin
 * @usage $("#box-widget").activateBox();
 */
(function ($) {

    'use strict';

    $.fn.activateBox = function () {
        $.AdminLTE.boxWidget.activate(this);
    };

})(jQuery);

/*
 * TODO LIST CUSTOM PLUGIN
 * -----------------------
 * This plugin depends on iCheck plugin for checkbox and radio inputs
 *
 * @type plugin
 * @usage $("#todo-widget").todolist( options );
 */
(function ($) {

    'use strict';

    $.fn.todolist = function (options) {
        // Render options
        var settings = $.extend({
            //When the user checks the input
            onCheck: function (ele) {
                return ele;
            },
            //When the user unchecks the input
            onUncheck: function (ele) {
                return ele;
            }
        }, options);

        return this.each(function () {

            if (typeof $.fn.iCheck != 'undefined') {
                $('input', this).on('ifChecked', function () {
                    var ele = $(this).parents("li").first();
                    ele.toggleClass("done");
                    settings.onCheck.call(ele);
                });

                $('input', this).on('ifUnchecked', function () {
                    var ele = $(this).parents("li").first();
                    ele.toggleClass("done");
                    settings.onUncheck.call(ele);
                });
            } else {
                $('input', this).on('change', function () {
                    var ele = $(this).parents("li").first();
                    ele.toggleClass("done");
                    if ($('input', ele).is(":checked")) {
                        settings.onCheck.call(ele);
                    } else {
                        settings.onUncheck.call(ele);
                    }
                });
            }
        });
    };
}(jQuery));

/**
 * Get cookie by name
 */
function get_cookie(cookie_name){
    var results = document.cookie.match('(^|;) ?' + cookie_name + '=([^;]*)(;|$)');

    if (results){
        return (unescape(results[2]));
    }

    return null;
}

$(document).ready(function (){
    $("#task-type").change(function () {
        var type_value = this.value;

        if (type_value == "following" || type_value == "like") {
            $("#task-un-following").slideUp(400);
            $("#task-like-or-direct").slideUp(400);
            $(".task-direct").slideUp(400);
            $("#task-follow_like_count").slideDown(400);
            $("#task-has-type").slideDown(400);
            $("#task-following").slideDown(400);
            $("#task-filter").slideDown(400);
            $("#params-like_count").trigger("mouseup");
        } else if ($(this).val() == "un-following") {
            $("#task-following").slideUp(400);
            $("#task-filter").slideUp(400);
            $(".task-direct").slideUp(400);
            $("#task-un-following").slideDown(400);
        }

        if (type_value == "like") {
            $("#task-has-type").slideUp(400);
            $(".task-direct").slideUp(400);
            $("#task-like-or-direct").slideDown(400);
            $("#params-like_count").attr("min", 1);

            if ($("#params-like_count").val() == 0) {
                $("#params-like_count").val(1);
                $("#params-like_count").trigger("mouseup");
            }
        } else if (type_value == "direct"){
            $("#task-un-following").slideUp(400);
            $("#task-follow_like_count").slideUp(400);
            $("#task-follow_like_limit").slideUp(400);
            $("#task-has-type").slideUp(400);

            $("#task-like-or-direct").slideDown(400);
            $(".task-direct").slideDown(400);
            $("#task-filter").slideDown(400);
            $("#task-following").slideDown(400);
        }else{
            $(".task-direct").slideUp(400);
            $("#task-like-or-direct").slideUp(400);
            $("#params-like_count").attr("min", 0);
            $("#task-has-type").slideDown(400);
        }

        if (type_value == ""){
            $("#task-following, #task-un-following, #task-filter, .task-direct").slideUp(450);
        }
    });

    $("#params-like_count").on("mouseup keyup", function () {
        if ($(this).val() > 0){
            $("#task-follow_like_limit").slideDown(200);
        }else{
            $("#task-follow_like_limit").slideUp(200);
        }
    });
    
    $("#params-follow_type_to").change(function () {
        var v = this.value;

        if (v == 1){
            $(".task-following-user").slideDown(200);
            $(".task-following-hash-tags").slideUp(200);
            $(".task-following-location").slideUp(200);
            $(".task-following-list").slideUp(200);
        } else if (v == 2){
            $(".task-following-hash-tags").slideDown(200);
            $(".task-following-user").slideUp(200);
            $(".task-following-location").slideUp(200);
            $(".task-following-list").slideUp(200);
        } else if (v == 3) {
            $(".task-following-location").slideDown(200);
            $(".task-following-hash-tags").slideUp(200);
            $(".task-following-user").slideUp(200);
            $(".task-following-list").slideUp(200);
        } else {
            $(".task-following-list").slideDown(200);
            $(".task-following-location").slideUp(200);
            $(".task-following-hash-tags").slideUp(200);
            $(".task-following-user").slideUp(200);
        }
    });

    $("#task-form").on("beforeSubmit", function () {
        var task_type    = $("#task-type").val();
        var like_count   = $("#params-like_count").val();
        var task_type_to = $("#params-follow_type_to").val();
        var min_items    = $("#params-filter_items_min").val();
        var account_type = $("#params-filter_account_type").val();
        like_count       = task_type == 'direct' ? 0 : like_count;

        if (
            task_type == 'un-following' ||
            (
                (task_type_to == 1 || task_type_to == 4) &&
                (like_count == 0 || min_items > 0 && account_type == 1)
            ) ||
            (like_count == 0 || min_items > 0) && account_type == 1
        ){
            return true;
        }

        if (like_count > 0){
            if (min_items > 0){
                $("#task-form-modal #task-fail-items_min").hide();
                $("#task-form-modal #task-success-items_min").show();
            }else{
                $("#task-form-modal #task-success-items_min").hide();
                $("#task-form-modal #task-fail-items_min").show();
            }
        }

        if (account_type == 1){
            $("#task-form-modal #task-fail-account_type").hide();
            $("#task-form-modal #task-success-account_type").show();
        }else{
            $("#task-form-modal #task-success-account_type").hide();
            $("#task-form-modal #task-fail-account_type").show();
        }

        if (task_type == 'like' || task_type_to == 1 || task_type_to == 4){
            $("#task-info-type-like").show();
            $("#task-info-type-other").hide();
        }else{
            $("#task-info-type-other").show();
            $("#task-info-type-like").hide();
        }

        if (like_count > 0){
            $("#task-info-items-min").show();
            $("#task-step-items_min").show();
        }else{
            $("#task-info-items-min").hide();
            $("#task-step-items_min").hide();
        }

        $("#task-form-modal").modal("show");
        return false;
    });

    $("#vk-task-type").change(function () {
        var v = this.value;

        if (v == 'mention'){
            $(".vk-task-account_auth").slideUp(200);
            $(".vk-task-profile").slideUp(200);
            $(".vk-task-like").slideUp(200);
            $(".vk-task-clear").slideUp(200);
            $(".vk-task-posting").slideUp(200);
            $(".vk-task-mention").slideDown(200);
            $(".vk-task-account").slideDown(200);
            $(".vk-task-attachments").slideDown(200);
            $("#task-filter").slideDown(200);
        } else if (v == 'like') {
            $(".vk-task-account_auth").slideUp(200);
            $(".vk-task-account").slideDown(200);
            $(".vk-task-profile").slideUp(200);
            $(".vk-task-mention").slideUp(200);
            $(".vk-task-clear").slideUp(200);
            $(".vk-task-posting").slideUp(200);
            $("#task-filter").slideDown(200);
            $(".vk-task-like").slideDown(200);
            $(".vk-task-attachments").slideUp(200);
        } else if (v == 'clear') {
            $(".vk-task-account_auth").slideUp(200);
            $(".vk-task-account").slideDown(200);
            $(".vk-task-profile").slideUp(200);
            $(".vk-task-mention").slideUp(200);
            $(".vk-task-like").slideUp(200);
            $("#task-filter").slideUp(200);
            $(".vk-task-posting").slideUp(200);
            $(".vk-task-clear").slideDown(200);
            $(".vk-task-attachments").slideUp(200);
        } else if (v == 'profile') {
            $(".vk-task-account_auth").slideUp(200);
            $(".vk-task-like").slideUp(200);
            $(".vk-task-clear").slideUp(200);
            $(".vk-task-mention").slideUp(200);
            $("#task-filter").slideUp(200);
            $(".vk-task-posting").slideUp(200);
            $(".vk-task-account").slideDown(200);
            $(".vk-task-attachments").slideDown(200);
            $(".vk-task-profile").slideDown(200);
        }else if (v == 'posting') {
            $(".vk-task-account_auth").slideUp(200);
            $(".vk-task-like").slideUp(200);
            $(".vk-task-clear").slideUp(200);
            $(".vk-task-mention").slideUp(200);
            $("#task-filter").slideUp(200);
            $(".vk-task-profile").slideUp(200);
            $(".vk-task-account").slideDown(200);
            $(".vk-task-attachments").slideDown(200);
            $(".vk-task-posting").slideDown(200);
        }else if (v == 'password') {
            $(".vk-task-account_auth").slideUp(200);
            $(".vk-task-like").slideUp(200);
            $(".vk-task-clear").slideUp(200);
            $(".vk-task-mention").slideUp(200);
            $(".vk-task-attachments").slideUp(200);
            $("#task-filter").slideUp(200);
            $(".vk-task-profile").slideUp(200);
            $(".vk-task-posting").slideUp(200);
            $(".vk-task-account").slideDown(200);
        }else if (v == 'add_account') {
            $(".vk-task-like").slideUp(200);
            $(".vk-task-clear").slideUp(200);
            $(".vk-task-mention").slideUp(200);
            $(".vk-task-attachments").slideUp(200);
            $("#task-filter").slideUp(200);
            $(".vk-task-profile").slideUp(200);
            $(".vk-task-posting").slideUp(200);
            $(".vk-task-account").slideUp(200);
            $(".vk-task-account_auth").slideDown(200);
        }else {
            $(".vk-task-account_auth").slideUp(200);
            $(".vk-task-like").slideUp(200);
            $(".vk-task-profile").slideUp(200);
            $(".vk-task-clear").slideUp(200);
            $(".vk-task-posting").slideUp(200);
            $(".vk-task-account").slideUp(200);
            $(".vk-task-attachments").slideUp(200);
            $(".vk-task-mention").slideUp(200);
            $("#task-filter").slideUp(200);
        }
    });

    $(".tag").tagsInput({
        height: '100%',
        width: 'auto',
        defaultText: ''
    });

    $("textarea").autoResize({
        animate: false,
        limit: 300
    });

    var taskAllowMessage = {};

    $(".task-run").click(function () {
        var selector = $(this);
        var id       = selector.data("task-id");
        var type     = selector.data("task-type");

        $.ajax("/task/allow-" + type + "/" + id, {
            async: false,
            cache: false,
            method: "POST",
            dataType: "json",
            complete: function (data) {
                if (data.responseJSON.allow === false){
                    // if (taskAllowMessage[id] == undefined){
                    //     taskAllowMessage[id] = {
                    //         title: selector.data('title'),
                    //         description: selector.data('description')
                    //     };
                    // }

                    selector.attr('data-title', data.responseJSON.title)
                        .attr('data-description', data.responseJSON.message)
                        .attr('data-confirm', 'Все равно запустить')
                        .attr('data-cancel', 'Отмена')
                        .attr('data-confirm-class', 'btn-danger task-warning-confirm')
                        .attr('data-cancel-class', 'btn-default task-warning-cancel');
                } else {
                    // if (taskAllowMessage[id] != undefined){
                    //     selector.attr('data-title', taskAllowMessage[id].title)
                    //         .attr('data-description', taskAllowMessage[id].description);
                    // }

                    selector.removeAttr('data-confirm')
                        .removeAttr('data-cancel')
                        .removeAttr('data-confirm-class')
                        .removeAttr('data-cancel-class');
                }
            }
        });
    });

    $("body").on("click", "a[data-toggle=confirm]", function(){
        var selector = $(this);

        if (selector.attr('disabled') == undefined){
            $.confirmBox({
                title:              selector.data('title'),
                description:        selector.data('description'),
                url:                selector.attr('href'),
                method:             selector.data('method'),
                buttonConfirm:      selector.data('confirm') ? selector.data('confirm') : "Да",
                buttonCancel:       selector.data('cancel') ? selector.data('cancel') : "Нет",
                buttonConfirmClass: selector.data('confirm-class') ? selector.data('confirm-class') : null,
                buttonCancelClass:  selector.data('cancel-class') ? selector.data('cancel-class') : null
            });
        }

        return false;
    });

    $("form").on("afterValidate", function (event, messages, errorAttributes) {
        var button = $(this).find("[data-loading-text]");

        if (errorAttributes.length == 0 && button){
            button.button("loading");
        }
    });

    $(".select2").select2({
        language: "ru",
        minimumResultsForSearch: Infinity
    });

    $(".vk-task-clear .select2").on("select2:select", function(e){
        if (e.params.data.id == 'wall'){
            $(".vk-task-clear-type-wall").slideDown(200);
        }
    });
    $(".vk-task-clear .select2").on("select2:unselect", function(e){
        if (e.params.data.id == 'wall'){
            $(".vk-task-clear-type-wall").slideUp(200);
        }
    });

    $(".vk-task-like .select2").on("select2:select", function(e){
        if (e.params.data.id == 'wall'){
            $(".vk-task-like-source-wall").slideDown(200);
        }
    });
    $(".vk-task-like .select2").on("select2:unselect", function(e){
        if (e.params.data.id == 'wall'){
            $(".vk-task-like-source-wall").slideUp(200);
        }
    });

    $(".select2-ajax").select2({
        language: "ru",
        minimumInputLength: 2,
        templateResult: function (data) {
            var $state = $(
                '<div>' +
                data.text +
                '</div>'
            );

            return $state;
        },
        ajax: {
            cache: true,
            dataType: 'json',
            delay: 400,
            type: "POST",
            timeout: 6000,
            data: function (params) {
                return {
                    query: params.term,
                    _csrf: yii.getCsrfToken()
                };
            },
            processResults: function (data, params) {
                if (data.status == 'ok'){
                    return {
                        results: data.items
                    };
                }

                return {
                    results: {}
                };
            }
        }
    });

    /**
     * Responsive table
     */
    responsiveTable();

    $(
        "#params-filter_followers_min," +
        "#params-filter_followers_max," +
        "#params-filter_following_min," +
        "#params-filter_following_max," +
        "#params-filter_items_min," +
        "#params-filter_items_max"
    ).bind("change keyup input click", function(a) {
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9]/g, '');
        }
    });

    $("a.loader").on("click", function () {
        $(this).button('loading');
    });

    /*
     *
     * Date Time Picker
     *
     */

    var picker_tooltips = {
        today: 'Go to today',
        clear: 'Очистить',
        close: 'Закрыть',
        selectMonth: 'Выбрать месяц',
        prevMonth: 'Предыдущий месяц',
        nextMonth: 'Следующий месяц',
        selectYear: 'Выбрать год',
        prevYear: 'Предыдущий год',
        nextYear: 'Следующий год',
        selectDecade: 'Выбрать десятилетие',
        prevDecade: 'Следующие десятилетие',
        nextDecade: 'Предыдущие десятилетие',
        prevCentury: 'Предыдущий век',
        nextCentury: 'Следующий век'
    };

    var picker_max_date = new Date($("#params-filter_last_item_max").val());
    if (picker_max_date.getTime()){
        picker_max_date.setDate(picker_max_date.getDate() - 1);
    }else{
        picker_max_date = false;
    }

    var picker_min_date = new Date($("#params-filter_last_item_min").val());
    if (picker_min_date.getTime()){
        picker_min_date.setHours(0, 0, 0, 0);
        picker_min_date.setDate(picker_min_date.getDate() + 1);
    }else{
        picker_min_date = false;
    }

    $('.picker-date').datetimepicker({
        locale: "ru",
        showClear: true,
        showClose: true,
        useCurrent: false,
        format: "YYYY/MM/DD",
        toolbarPlacement: "top",
        tooltips: picker_tooltips
    });

    $('#params-filter_last_item_min').datetimepicker({
        locale: "ru",
        showClear: true,
        showClose: true,
        useCurrent: false,
        format: "YYYY/MM/DD",
        toolbarPlacement: "top",
        maxDate: picker_max_date,
        tooltips: picker_tooltips,
        defaultDate: picker_max_date,
    });
    $('#params-filter_last_item_max').datetimepicker({
        locale: "ru",
        showClear: true,
        showClose: true,
        useCurrent: false,
        format: "YYYY/MM/DD",
        toolbarPlacement: "top",
        minDate: picker_min_date,
        tooltips: picker_tooltips,
        defaultDate: picker_min_date
    });

    $("#params-filter_last_item_min").on("dp.change", function (e) {
        var date = new Date(e.date);

        if (date.getTime()){
            date.setDate(date.getDate() + 1);
        }else{
            date = false;
        }

        $('#params-filter_last_item_max').data("DateTimePicker").minDate(date);
        $('#params-filter_last_item_max').data("DateTimePicker").viewDate(date);
    });
    $("#params-filter_last_item_max").on("dp.change", function (e) {
        var date = new Date(e.date);

        if (date.getTime()){
            date.setDate(date.getDate() - 1);
        }else{
            date = false;
        }

        $('#params-filter_last_item_min').data("DateTimePicker").maxDate(date);
        $('#params-filter_last_item_min').data("DateTimePicker").viewDate(date);
    });
    /*
     *
     * End Date Time Picker
     *
     */

    $('.task-info').on('shown.bs.modal', function () {
        $('.task-info').css({
            padding: "0 15px"
        })
    });

    new Clipboard('.clipboard');

    showHideHeaderCount();
    $(window).on("resize", function () {
        showHideHeaderCount();
    });

    $("body").on("expanded.pushMenu", function () {
        var cookie_expire = new Date();
        cookie_expire.setYear(2020);
        cookie_expire = cookie_expire.toUTCString();

        $(".navbar-static-top .sidebar-toggle .label").hide();

        if ($(window).width() > $.AdminLTE.options.screenSizes.sm){
            document.cookie = "push_menu=open; expires=" + cookie_expire + "; path=/;";
        }
    });

    $("body").on("collapsed.pushMenu", function () {
        var cookie_expire = new Date();
        cookie_expire.setYear(2020);
        cookie_expire = cookie_expire.toUTCString();

        $(".navbar-static-top .sidebar-toggle .label").show();

        if ($(window).width() > $.AdminLTE.options.screenSizes.sm){
            document.cookie = "push_menu=close; expires=" + cookie_expire + "; path=/;";
        }
    });

    $('.hint-block').on("click", function () {
        return false;
    });

    /**
     * Пополнение баланса
     */
    var paymentFormTitle  = $("#payment-currency option:selected").text();
    var paymentModalTitle = $("#payment-balance .modal-title").text();
    /**
     * Выбрали валюту
     */
    $('#payment-currency').on("change", function () {
        $(".payment-type").slideUp(500);
        $("#" + this.value).slideDown(500);
        paymentFormTitle = $("#payment-currency option:selected").text();
    });
    /**
     * Выбрали способ оплаты
     */
    $("a.set-payment-type").click(function () {
        var selector = $(this);

        if (selector.attr("href") == '#walletone'){
            $('#walletone-payment').val(selector.data('type'));
        }else{
            $('input[name="ik_pw_via"]').val(selector.data('type'));
        }

        $(selector.attr("href")).show();
        $(".payment-type-all").slideUp(500);
        $(".payment-form-all").slideDown(500);
        $("#payment-balance .modal-title").html('<i class="fa fa-angle-double-right"></i> ' + selector.data("original-title"));
//        $("#payment-balance .modal-title").html(paymentFormTitle + ' <i class="fa fa-angle-right"></i> ' + selector.data("original-title"));
    });

    /**
     * Возвращаемся к выбору способа оплаты
     */
    $(".update-payment-type a").click(function () {
        $(".payment-form").slideUp(500);
        $(".payment-form-all").slideUp(500);
        $(".payment-type-all").slideDown(500);
        $("#payment-balance .modal-title").text(paymentModalTitle);
    });

    $(".chart-select-account .select2").on("change", function () {
        loadAccountAnalytics($(this).val());
    });

    $('input.iCheck').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });

    /**
     * Mobile task filter
     */
    function formatStateResult(data) {
        var count = $(data.element).data('count');
        count = count ? count : 0;

        var $state = $(
            '<div>' +
                data.text +
                '<span class="label label-warning pull-right">' + count + '</span>' +
            '</div>'
        );

        return $state;
    }

    $(".task-view-filter select").select2({
        templateResult: formatStateResult,
        minimumResultsForSearch: Infinity
    });

    /**
     * Rate accounts
     */
    $("input.rate-select-account").on("change", function(){
        var max   = $("#rate-account-checked-max").text();
        var total = $("input.rate-select-account:checked").length;
        $("#rate-account-checked-total").text(total);

        if (total >= max){
            $("input.rate-select-account:not(:checked)").attr("disabled", 1);
        }else{
            $("input.rate-select-account:not(:checked)").removeAttr("disabled");
        }
    });

    $(".rate-account").on("click", function () {
        var selector = $(this);
        var name     = selector.data("name");
        var action   = selector.data("action");
        var rateNull = selector.data("rate-null");
        var max      = selector.data("accounts-max");
        var title    = selector.data("confirm-title");

        $("#rate-confirm-title").text(title);
        $("#rate-account-checked-max").text(max);
        $("#open-rate-block-confirm-account").attr("data-rate", name);
        $("#rate-confirm form").attr("action", action);

        $("#rate-null").css({display: (rateNull ? "block" : "none")});

        $(".rate-confirm-table input[type=checkbox]").removeAttr('checked').removeAttr('disabled');

        $("#rate-block-confirm-account").hide(200);
        $("#rate-block-select-account").show(200);

        $("#rate-confirm .alert").remove();
        $("#rate-confirm").modal("show");

        return false;
    });

    $("#open-rate-block-confirm-account").on("click", function () {
        var accounts = [];
        var totalAccounts = $(".rate-confirm-table table td input[type=checkbox]").length;

        $(".rate-confirm-table table input[data-login]:checked").each(function () {
            var login = $(this).data("login");
            if (login){
                accounts.push('<a target="_blank" href="/redirect?url=https://instagram.com/' + login + '/">@' + login + '</a>');
            }
        });

        $("#rate-block-confirm-account .rl").hide();
        $(".rate-confirm-name").text($(this).attr("data-rate"));

        if (accounts.length == 0){
            $("#rate-confirm-title-empty").show();
        }else if (accounts.length == 1){
            $("#rate-confirm-title-one").show();
            $("#rate-confirm-title-one .rate-block-confirm-account-list").html(accounts.join(''));
        }else{
            $("#rate-confirm-title-many").show();
            $("#rate-confirm-title-many .rate-block-confirm-account-list").html(accounts.join(', '));
        }

        if (accounts.length > 0 && accounts.length < totalAccounts){
            $("#rate-block-confirm-account #rate-null").show();
        }else{
            $("#rate-block-confirm-account #rate-null").hide();
        }

        $("#rate-block-select-account").hide(200);
        $("#rate-block-confirm-account").show(200);

        return false;
    });

    $("#rate-confirm-cancel").on("click", function () {
        $("#rate-block-confirm-account").hide(200);
        $("#rate-block-select-account").show(200);

        return false;
    });

    setTimeout(function (){
        $("#support-widget").show(500);
    }, 3000);
});

/**
 * Responsive table
 */
function responsiveTable(selector) {
    if (selector == undefined){
        selector = "table.responsive";
    }

    var breakpointDefinition = {
        xs: 550,
        sm: 750,
        md: 970,
        lg: 1170,
        pc: 1900,
        other: 10000
    };

    $(selector).each(function(i, elem){
        var responsiveHelper_dt_basic = undefined;

        $(elem).dataTable({
            "autoWidth" : true,
            "paginate": false,
            "bSort" : false,
            "bInfo" : false,
            "searching" : false,
            "sessionStorage": false,
            "preDrawCallback" : function() {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_dt_basic) {
                    responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($(elem), breakpointDefinition);
                }
            },
            "rowCallback" : function(nRow) {
                responsiveHelper_dt_basic.createExpandIcon(nRow);
            },
            "drawCallback" : function(oSettings) {
                responsiveHelper_dt_basic.respond();
            },
        });
    });
}

function showHideHeaderCount() {
    if ($(window).width() > $.AdminLTE.options.screenSizes.sm && $("body").hasClass('sidebar-collapse') === false){
        $(".navbar-static-top .sidebar-toggle .label").hide();
    }else if ($("body").hasClass('sidebar-open') === false){
        $(".navbar-static-top .sidebar-toggle .label").show();
    }
}

/**
 * Account Analytics
 */
var morris     = false;
var morrisAjax = false;

function loadAccountAnalytics(account_id) {
    $("#account-analytics svg").remove();
    $("#account-analytics .morris-hover").remove();
    $("#account-analytics .load").show();

    if (morrisAjax !== false){
        morrisAjax.abort();
    }

    morrisAjax = $.ajax({
        url: "/account/" + account_id + "/load-analytics",
        method: "POST",
        dataType: "json",
        success: function (data) {
            $("#account-analytics .load").hide();

            morris = new Morris.Area({
                element: 'account-analytics',
                resize: true,
                data: data,
                xkey: 'date',
                ykeys: ['count'],
                labels: ['Подписчиков'],
                lineColors: ['#00A65A'],
                xLabels: 'day',
                hideHover: 'auto',
                smooth: false,
                fillOpacity: 0.8,
                hoverCallback: function (index, options, content, row) {
                    var rise = row.rise > 0 ? '+' + row.rise : row.rise;

                    if (index == 0){
                        return 'Подписчиков: ' + row.count;
                    }

                    return 'Подписчиков: ' + row.count + '<br><span class="text-green">Отдача: ' + rise + '</span>';
                }
            });
        },
        error: function () {
            $("#account-analytics .load").hide();
            $("#account-analytics .load-error").show();
        }
    });
}

/**
 * Notification
 * @param options
 */
$.confirmBox = function (options) {
    var confirmClass = options.buttonConfirmClass ? options.buttonConfirmClass : "btn-default";
    var cancelClass = options.buttonCancelClass ? options.buttonCancelClass : "btn-default";

    var box =
        '<div class="confirmBox" id="confirmBox">' +
            '<div class="confirmBox-container">' +
                '<div class="confirmBox-middle">' +
                    '<span class="confirmBox-title">' +
                        options.title +
                    '</span>' +
                    '<p class="confirmBox-content">' +
                        options.description +
                    '</p>' +
                    '<div class="confirmBox-section">' +
                        '<a href="' + options.url + '" data-method="' + options.method + '" id="confirmBoxButton-confirm" class="btn btn-sm confirmBoxButton ' + confirmClass + '">' +
                            options.buttonConfirm +
                        '</a>' +
                        '<a href="#" id="confirmBoxButton-cancel" class="btn btn-sm confirmBoxButton ' + cancelClass + '">' +
                            options.buttonCancel +
                        '</a>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';
    $("body").append(box);
    $("#confirmBox").animate({opacity: 1}, 500);

    $("body").on("click", "#confirmBoxButton-cancel", function () {
        $("#confirmBox").animate({opacity: 0}, 500, function () {
            $("#confirmBox").remove();
        });
    });
};