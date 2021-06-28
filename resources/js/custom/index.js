$(document).ready(function () {
    function checkForInput(element) {

        let val = $(element).val();
        if (val) {
            $(element).parent('.form-row').addClass('has-val');
        } else {
            $(element).parent('.form-row').removeClass('has-val');
        }
    }
    $('.form-row input[type=email], .form-row input[type=text], .form-row input[type=password]').each(function() {
        checkForInput(this);
    });
    $('.form-row input[type=email], .form-row input[type=text], .form-row input[type=password]').on('change blur', function() {
        checkForInput(this);
    });

})
$(function() {
    "use strict";


    $.sidebarMenu = function(menu) {
        $('.sidebar-menu .sidebar-submenu > li.active').parents('li').addClass('active');
        var animationSpeed = 300,
            subMenuSelector = '.sidebar-submenu';
        $(menu).on('click', 'li a', function(e) {
            var $this = $(this);
            var checkElement = $this.next();
            if (checkElement.is(subMenuSelector) && checkElement.is(':visible')) {
                checkElement.slideUp(animationSpeed, function() {
                    checkElement.removeClass('menu-open');
                });
                checkElement.parent("li").removeClass("active");
            }
            //If the menu is not visible
            else if ((checkElement.is(subMenuSelector)) && (!checkElement.is(':visible'))) {
                //Get the parent menu
                var parent = $this.parents('ul').first();
                //Close all open menus within the parent
                var ul = parent.find('ul:visible').slideUp(animationSpeed);
                //Remove the menu-open class from the parent
                ul.removeClass('menu-open');
                //Get the parent li
                var parent_li = $this.parent("li");
                //Open the target menu and add the menu-open class
                checkElement.slideDown(animationSpeed);
                checkElement.addClass('menu-open');
                parent.find('li.active').removeClass('active');
                parent_li.addClass('active');
            }
            //if this isn't a link, prevent the page from being redirected
            if (checkElement.is(subMenuSelector)) {
                e.preventDefault();
            }
        });
    }


//sidebar menu js
    $.sidebarMenu($('.sidebar-menu'));

// === toggle-menu js
    $(".toggle-menu").on("click", function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });


    /* Back To Top */

    $(document).ready(function(){
        $(window).on("scroll", function(){
            if ($(this).scrollTop() > 300) {
                $('.back-to-top').fadeIn();
            } else {
                $('.back-to-top').fadeOut();
            }
        });

        $('.back-to-top').on("click", function(){
            $("html, body").animate({ scrollTop: 0 }, 600);
            return false;
        });
    });




    /* url creator */
    $(document).ready(function(){

        function make_slug($string, $separator = '-')
        {

            $string = $string.replace(/^\s+|\s+$/g, '');
            $string = $string.toLowerCase();
            //persian support
            $string = $string.replace(/[^a-z0-9_\s-ءاآؤئبپتثجچحخدذرزژسشصضطظعغفقکكگلمنوهی]/g, '')
                // Collapse whitespace and replace by -
                .replace(/\s+/g, '-')
                // Collapse dashes
                .replace(/-+/g, '-');
            return $string;

        }


        $(".make-slug-from").on("keyup" , function () {
            let dInput = this.value;
            let res = make_slug(dInput);
            $(".make-slug-to").val(res)
        } )




    })




});
