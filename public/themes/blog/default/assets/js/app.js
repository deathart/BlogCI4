var App = (function() {

    var that = {};

    that.GetBaseUrl = function() {
        return window.location.protocol + "//" + window.location.host + "/";
    };

    that.init = function() {
        that.research();
        that.avert_cookies();
        that.scroll_top();
        $(".add_email_news").submit(function(e) {
            e.preventDefault();
            that.newsletter($(this));
            return false;
        });
    };

    that.research = function() {
        $(".click_search").click(function(e) {
            e.preventDefault();
            $(".recherche_modal").fadeIn("slow", function() {
                $(".modal").slideDown("slow");
                $(".del_modal").click(function() {
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

        $(".tablinks").click(function() {
            var link = $(this).data("tabsnav");
            $(".tabsnavtitle > .active").removeClass("active");
            $(this).addClass("active");
            $(".tabsnavcontent > .show").removeClass("show");
            $(".tabsnavcontent > #" + link).addClass("show")
        });

    };

    that.avert_cookies = function() {
        if (!Cookies.get('cookie_ok')) {
            $(".eupopup-container").css("display", "block");
            $(".eupopup-button_1").click(function() {
                $(".eupopup-container").slideUp("slow", function() {
                    Cookies.set('cookie_ok', true, {
                        expires: 365
                    });
                });
            });
        }
    };

    that.scroll_top = function() {
        $(document).on('scroll', function() {
            if ($(window).scrollTop() > 100) {
                $('.scroll-top-wrapper').addClass('show');
                $("header").addClass("fixed");
                $("#wrapper > #content").css("margin-top", "25px");
            } else {
                $('.scroll-top-wrapper').removeClass('show');
                $("header").removeClass("fixed");
                $("#wrapper > #content").css("margin-top", "0");
            }
        });

        $('.scroll-top-wrapper').on('click', scrollToTop);

        function scrollToTop() {
            verticalOffset = typeof(verticalOffset) != 'undefined' ? verticalOffset : 0;
            element = $('body');
            offset = element.offset();
            offsetTop = offset.top;
            $('html, body').animate({
                scrollTop: offsetTop
            }, 500, 'linear');
        }
    };

    that.newsletter = function(form_data) {
        var email = form_data.children("input").val();
        if (email) {
            $.ajax({
                beforeSend: function(xhr, settings) {
                    if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                        xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                    }
                },
                method: "POST",
                url: that.GetBaseUrl() + "newsletter",
                data: {
                    'email': email
                },
                dataType: 'json',
                cache: false,
                success: function(data_cap) {
                    if (data_cap.code == 1) {
                        form_data.html('<div class="message success">' + data_cap.message + '</div>');
                    } else if (data_cap.code == 2) {
                        form_data.prepend('<div class="message error">' + data_cap.message + '</div>');
                        setTimeout(function() {
                            $(".message").hide();
                        }, 3000);
                    }
                },
                error: function(data_cap) {
                    console.log(data_cap.responseText);
                }
            });
        }
    };

    return that;

})();

$(document).ready(function() {
    App.init();
});