var Contact = (function(){

    var that = {};

    that.GetBaseUrl = function () {
        if(window.location.host == "deathart.dev") {
            return window.location.protocol + "//" + window.location.host + "/";
        }
        else {
            return "https://www.deathart.fr/";
        }
    };

    that.contactform = function() {

        var name = $("#name").val();
        var email = $("#email").val();
        var sujet = $("#sujet").val();
        var msg = $("#msg").val();

        if(name && email && sujet && msg) {

            $.ajax({
                beforeSend: function (xhr, settings) {
                    if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                        xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                    }
                },
                method: "POST",
                url: that.GetBaseUrl() + "contact/add",
                data: { 'name': name, 'email': email, 'sujet': sujet, 'message': msg },
                dataType: 'json',
                cache: false,
                success: function (data) {
                    if(data.code == 1) {
                        $(".message_contact").html("<span style='color: #1e7e34'>" + data.message + "</span>");
                        $(".contact").hide();
                    }
                    else if(data.code == 2) {
                        $(".message_contact").html("<span style='color: red'>" + data.message + "</span>");
                    }
                },
                error: function (data) {
                    console.log(data.responseText);
                }
            });

        }
        else {
            $(".message_contact").html("<span style='color: red'>Merci de remplir tout les champs</span>");
        }

    };

    that.init = function() {
        $(".contact").submit(function(e) {
            e.preventDefault();
            that.contactform();
            return false;
        });
    };

    return that;

})();

$(document).ready(function () {
    Contact.init();
});