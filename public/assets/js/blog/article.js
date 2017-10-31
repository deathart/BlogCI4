var Article = (function(){
    
    var that = {};

    that.init = function() {
        that.AddComments();
        that.bloc_share();
    };

    that.GetBaseUrl = function () {
        return window.location.protocol + "//" + window.location.host + "/";
    };

    that.AddComments = function() {

        $.ajaxSetup({
            beforeSend: function (xhr, settings) {
                if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                    xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                }
            }
        });

        $(".add_com").submit(function(e) {
            e.preventDefault();

            var name    = $('#name');
            var email   = $('#email');
            var message = $('#com');
            var post    = $(this).data("post")
            var error   = false;

            if(!name.val()) {
                error = true;
            }

            if(!email.val()) {
                error = true;
            }

            if(!message.val()) {
                error = true;
            }

            if(!error) {
                $.ajax({
                    beforeSend: function (xhr, settings) {
                        if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                            xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                        }
                    },
                    method: "POST",
                    url: that.GetBaseUrl() + "comments/add",
                    data: { 'post_id' : post,'name': name.val(), 'email': email.val(), 'message': message.val() },
                    dataType: 'json',
                    cache: false,
                    success: function (data) {
                        if(data.code == 1) {
                            $(".message_add_coms").html("<span style='color: #1e7e34'>" + data.message + "</span>");
                            setTimeout(function() {
                                location.reload();
                            }, 3000);
                        }
                        else if(data.code == 2) {
                            $(".message_add_coms").html("<span style='color: red'>" + data.message + "</span>");
                        }
                    },
                    error: function (data) {
                        console.log(data.responseText);
                    }
                });
            }
            else {
                $(".message_add_coms").html("<span style='color: red'>Merci de remplire les champs</span>");
            }

            return false;
        });

    };

    that.bloc_share = function() {

        var share = $('#share');

        var vTop = share.offset().top - parseFloat(share.css('margin-top').replace(/auto/, 0));

        $(window).scroll(function(event) {

            var y = $(this).scrollTop();

            if (y >= vTop) {
                share.addClass('fixed');
            } else {
                share.removeClass('fixed');
            }

        });
    };

    return that;

})();

$(document).ready(function () {
    Article.init();
});