var Contact = (function() {

    var that = {};

    that.reponse = function(get) {

        var sujet = $("#inputsujet").val();
        var message = $("#inputmessage").val().replace(/\r\n|\r|\n/g, "<br />");
        var contact_id = $(get).data("contact")

        if (sujet && message) {
            $.ajax({
                beforeSend: function(xhr, settings) {
                    if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                        xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                    }
                },
                method: "POST",
                url: App.GetBaseUrl() + "admin/ajax/contact/rep",
                data: {
                    'id': contact_id,
                    'sujet': sujet,
                    'message': message
                },
                dataType: 'json',
                cache: false,
                success: function(data) {
                    if (data.code = 1) {
                        App.NotifToast("success", data.title, data.message);
                    } else {
                        App.NotifToast("error", "Erreur", data.message);
                    }
                },
                error: function(data) {
                    console.log(data.responseText);
                }
            });
        } else {
            App.NotifToast("error", "Erreur", "Merci de remplire tout les champs");
        }

    };

    that.markedview = function(get) {
        var parent_li = get.parent("li");
        var contact_id = parent_li.data("contact");

        $.ajax({
            beforeSend: function(xhr, settings) {
                if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                    xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                }
            },
            method: "POST",
            url: App.GetBaseUrl() + "admin/ajax/contact/markedview",
            data: {
                'id': contact_id
            },
            dataType: 'json',
            cache: false,
            success: function(data) {
                if (data.code = 1) {
                    App.NotifToast("success", data.title, data.message);
                    parent_li.hide("slow");
                } else {
                    App.NotifToast("error", "Erreur", data.message);
                }
            },
            error: function(data) {
                console.log(data.responseText);
            }
        });

    };

    that.delete = function(get) {
        var parent_li = get.parent("li");
        var contact_id = parent_li.data("contact");

        $.ajax({
            beforeSend: function(xhr, settings) {
                if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                    xhr.setRequestHeader("X-CSRFToken", $('meta[name="_token"]').attr('content'));
                }
            },
            method: "POST",
            url: App.GetBaseUrl() + "admin/ajax/contact/del",
            data: {
                'id': contact_id
            },
            dataType: 'json',
            cache: false,
            success: function(data) {
                if (data.code = 1) {
                    App.NotifToast("success", data.title, data.message);
                    parent_li.hide("slow");
                } else {
                    App.NotifToast("error", "Erreur", data.message);
                }
            },
            error: function(data) {
                console.log(data.responseText);
            }
        });

    };

    that.init = function() {
        $(".repcontact").submit(function(e) {
            e.preventDefault();
            that.reponse($(this));
            return false;
        });
        $(".markedview-contact").click(function() {
            that.markedview($(this));
        });
        $(".del-contact").click(function() {
            that.delete($(this));
        });
    };

    return that;

})();

$(document).ready(function() {
    Contact.init();
});