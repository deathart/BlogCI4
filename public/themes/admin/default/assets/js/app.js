var App = (function() {

    var that = {};

    that.GetBaseUrl = function() {
        return window.location.protocol + "//" + window.location.host + "/";
    };

    that.menu = function() {
        /*$('.collapse').on('show.bs.collapse', function () {
            $(this).parent().addClass("active");
        });

        $('.collapse').on('hide.bs.collapse', function () {
            $(this).parent().removeClass("active");
        });*/

        $('#menu li.active').addClass('active').children('ul').show();

        $('#menu li.has-sub>a').click(function(e) {

            e.preventDefault();

            var element = $(this).parents('li');

            if (element.hasClass('active')) {
                element.find('ul').slideUp("slow", function() {
                    element.removeClass('active');
                    element.find('li').removeClass('active');
                });
            } else {
                element.addClass('active');
                element.children('ul').slideDown("slow");
                element.siblings('li').children('ul').slideUp("slow", function() {
                    element.siblings('li').removeClass('active');
                    element.siblings('li').find('li').removeClass('active');
                    element.siblings('li').find('ul').slideUp("slow");
                });
            }

            return false;

        });
    };

    that.NotifToast = function(type, title, content) {
        return $.toast({
            text: content,
            heading: title,
            icon: type,
            showHideTransition: 'fade',
            allowToastClose: true,
            hideAfter: 5000,
            stack: 5,
            position: 'bottom-center',
            textAlign: 'center',
            loader: true,
            loaderBg: '#9EC600'
        });
    };

    that.init = function() {
        that.menu();
    };

    return that;

})();

$(document).ready(function() {
    App.init();
});