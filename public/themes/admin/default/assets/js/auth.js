var App = (function() {

    var that = {};

    that.GetBaseUrl = function() {
        return window.location.protocol + "//" + window.location.host + "/";
    };

    that.Alertmessage = function(type, message) {
        $(".message").remove();
        $(".login_form").prepend('<div class="message ' + type + '">' + message + '</div>');
    };

    that.Form = function() {

        $(".login_form").submit(function(e) {

            e.preventDefault();

            var mail_input = $(".mail").val();
            var pass_input = $(".password").val();
            var remb_input = $(".remember").is(':checked') ? 1 : 0;

            if (mail_input && pass_input) {

                $.ajax({
                    beforeSend: function(xhr, settings) {
                        if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                            xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                        }
                    },
                    method: "POST",
                    url: that.GetBaseUrl() + "admin/auth/login_ajax",
                    data: {
                        email: mail_input,
                        password: pass_input,
                        'remember': remb_input
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data) {
                        if (data.result == 1) {
                            that.Alertmessage("success", "<strong>Succès :</strong> Connexion réussit, redirection en cours...");
                            $(".mail").css("border", "1px solid green");
                            $(".password").css("border", "1px solid green");
                            setTimeout(function() {
                                window.location.href = that.GetBaseUrl() + "admin";
                            }, 3000);
                        } else {
                            that.Alertmessage("error", "<strong>Erreur :</strong> Merci de vérifier vos informations");
                            $(".mail").css("border", "1px solid red");
                            $(".password").css("border", "1px solid red");
                        }
                    },
                    error: function(data) {
                        console.log(data.responseText);
                    }
                });
            } else {
                that.Alertmessage("error", "<strong>Erreur :</strong> Merci de remplir correctement les champs");
                $(".mail").css("border", "1px solid red");
                $(".password").css("border", "1px solid red");
            }

            return false;
        });


    };

    that.init = function() {
        that.Form();
    };

    return that;

})();