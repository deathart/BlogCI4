var App = (function() {

    var that = {};

    that.GetBaseUrl = function() {
        return window.location.protocol + "//" + window.location.host + "/";
    };

    that.menu = function() {

        //$('#menu li.active').children('ul').show();

        $('#menu li.has-sub>a').click(function(e) {

            e.preventDefault();

            var element = $(this).parents('li');

            if (element.hasClass('active')) {
                element.find('ul').slideUp("slow", function() {
                    element.removeClass('active');
                });
            } else {
                element.children('ul').slideDown("slow", function() {
                    element.addClass('active');
                    element.siblings('li').removeClass('active');
                });
                element.siblings('li').children('ul').slideUp("slow");
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