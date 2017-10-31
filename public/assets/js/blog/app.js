var App = {
    research: function() {
        $(".click_search").click(function(e) {
            e.preventDefault();
            $(".recherche_modal").fadeIn("slow", function() {
                $(".modal").slideDown("slow");
                $(".del_modal").click(function(ev) {
                    $(".modal").slideUp("slow", function() {
                        $(".recherche_modal").fadeOut("slow");
                    });
                });
                $(window).click(function(event) {
                    if (event.target.className == "recherche_modal") {
                        $(".modal").slideUp("slow", function() {
                            $(".recherche_modal").fadeOut("slow");
                        });
                    }
                });
            });
            return false;
        });

        $(".tablinks").click(function(e) {
            var link = $(this).data("tabsnav");
            $(".tabsnavtitle > .active").removeClass("active");
            $(this).addClass("active");
            $(".tabsnavcontent > .show").removeClass("show");
            $(".tabsnavcontent > #" + link).addClass("show")
        });

    },
    avert_cookies: function() {
        if (!Cookies.get('cookie_ok')) {
            $(".eupopup-container").css("display", "block");
            $(".eupopup-button_1").click(function() {
                $(".eupopup-container").slideUp("slow", function() {
                    Cookies.set('cookie_ok', true, { expires: 365 });
                });
            });
        }
    },
    scroll_top: function() {
        $(document).on( 'scroll', function(){
            if ($(window).scrollTop() > 100) {
                $('.scroll-top-wrapper').addClass('show');
            } else {
                $('.scroll-top-wrapper').removeClass('show');
            }
        });

        $('.scroll-top-wrapper').on('click', scrollToTop);
        function scrollToTop() {
            verticalOffset = typeof(verticalOffset) != 'undefined' ? verticalOffset : 0;
            element = $('body');
            offset = element.offset();
            offsetTop = offset.top;
            $('html, body').animate({scrollTop: offsetTop}, 500, 'linear');
        }
    },
    init: function() {
        this.research();
        this.avert_cookies();
        this.scroll_top();
    }
};