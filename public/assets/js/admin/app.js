var App = (function(){
    
    var that = {};

    that.GetBaseUrl = function () {
        if(window.location.host == "deathart.dev") {
            return window.location.protocol + "//" + window.location.host + "/";
        }
        else {
            return "https://www.deathart.fr/";
        }
    };

    that.menu = function() {
        $('.collapse').on('show.bs.collapse', function () {
            $(this).parent().addClass("active");
        });

        $('.collapse').on('hide.bs.collapse', function () {
            $(this).parent().removeClass("active");
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

$(document).ready(function () {
    App.init();
});